<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAffiliateBannerRequest;
use App\Http\Requests\StoreSeoTopicRequest;
use App\Http\Requests\UploadArticleImagesRequest;
use App\Http\Requests\UploadBrandLogoRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Jobs\GenerateSeoArticleJob;
use App\Models\AffiliateBanner;
use App\Models\Article;
use App\Models\ArticleTopic;
use App\Services\VertexSeoFactoryService;
use App\Support\ArticleMediaLibrary;
use App\Support\PortalSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SeoFactoryController extends Controller
{
    public function __construct(
        private readonly VertexSeoFactoryService $seoFactory,
        private readonly PortalSettings $portalSettings,
        private readonly ArticleMediaLibrary $articleMediaLibrary,
    ) {
    }

    public function index(Request $request): View
    {
        $articleSearch = trim((string) $request->string('article_search'));
        $articlesQuery = Article::query()->latest();

        if ($articleSearch !== '') {
            $articlesQuery->where(function ($query) use ($articleSearch) {
                $query
                    ->where('title', 'like', "%{$articleSearch}%")
                    ->orWhere('slug', 'like', "%{$articleSearch}%")
                    ->orWhere('meta_description', 'like', "%{$articleSearch}%")
                    ->orWhere('excerpt', 'like', "%{$articleSearch}%");
            });
        }

        $articles = $articlesQuery
            ->select(['id', 'topic_id', 'title', 'slug', 'excerpt', 'status', 'published_at', 'updated_at'])
            ->with('topic:id,category')
            ->paginate(20)
            ->withQueryString();

        $selectedArticleId = $request->integer('article');
        $selectedArticle = $selectedArticleId > 0
            ? Article::with('topic')->find($selectedArticleId)
            : null;

        if (! $selectedArticle && $articles->count() > 0) {
            $selectedArticle = Article::with('topic')->find($articles->first()->id);
        }

        return view('admin.seo.index', [
            'topics' => ArticleTopic::latest()->get(),
            'articles' => $articles,
            'articlesPublishedCount' => Article::query()->where('status', 'published')->count(),
            'articleSearch' => $articleSearch,
            'selectedArticle' => $selectedArticle,
            'banners' => AffiliateBanner::latest()->get(),
            'themes' => $this->seoFactory->themeOptions(),
            'settings' => $this->portalSettings->all(),
            'articleImagesByCategory' => $this->articleMediaLibrary->gallery(),
        ]);
    }

    public function storeTopic(StoreSeoTopicRequest $request): RedirectResponse
    {
        ArticleTopic::create($this->seoFactory->prepareTopicPayload($request->validated()));

        return back()->with('status', 'Topik SEO berhasil ditambahkan.');
    }

    public function storeBanner(StoreAffiliateBannerRequest $request): RedirectResponse
    {
        AffiliateBanner::create($request->validated());

        return back()->with('status', 'Placement affiliate berhasil ditambahkan.');
    }

    public function generate(): RedirectResponse
    {
        $topics = $this->seoFactory->pickTopicSlotsForBatch(
            (int) config('portal.vertex.articles_per_run', 12)
        );

        foreach ($topics as $topic) {
            GenerateSeoArticleJob::dispatch($topic->id);
        }

        return back()->with('status', "{$topics->count()} job artikel dikirim ke queue.");
    }

    public function updateTopic(StoreSeoTopicRequest $request, ArticleTopic $topic): RedirectResponse
    {
        $topic->update($this->seoFactory->prepareTopicPayload($request->validated(), $topic));

        return back()->with('status', 'Topik SEO berhasil diperbarui.');
    }

    public function destroyTopic(ArticleTopic $topic): RedirectResponse
    {
        $topic->delete();

        return back()->with('status', 'Topik SEO berhasil dihapus.');
    }

    public function updateBanner(StoreAffiliateBannerRequest $request, AffiliateBanner $banner): RedirectResponse
    {
        $banner->update($request->validated());

        return back()->with('status', 'Placement affiliate berhasil diperbarui.');
    }

    public function destroyBanner(AffiliateBanner $banner): RedirectResponse
    {
        $banner->delete();

        return back()->with('status', 'Placement affiliate berhasil dihapus.');
    }

    public function updateArticle(UpdateArticleRequest $request, Article $article): RedirectResponse
    {
        $payload = $request->validated();
        $payload['source_references'] = $this->parseSourceReferences($payload['source_references_text'] ?? null);
        $payload['published_at'] = $payload['status'] === 'published'
            ? ($article->published_at ?? now())
            : null;
        unset($payload['source_references_text']);

        $article->update($payload);

        return back()->with('status', 'Artikel berhasil diperbarui.');
    }

    public function destroyArticle(Article $article): RedirectResponse
    {
        $article->delete();

        return back()->with('status', 'Artikel berhasil dihapus.');
    }

    public function updateBranding(Request $request): RedirectResponse
    {
        $payload = $request->validate([
            'site_name' => ['required', 'string', 'max:120'],
            'tagline' => ['nullable', 'string', 'max:180'],
            'hero_title' => ['required', 'string', 'max:180'],
            'hero_description' => ['required', 'string', 'max:320'],
            'footer_note' => ['nullable', 'string', 'max:220'],
            'affiliate_disclosure' => ['nullable', 'string', 'max:220'],
        ]);

        $this->portalSettings->update([
            'branding' => [
                'site_name' => $payload['site_name'],
                'tagline' => $payload['tagline'] ?? null,
            ],
            'homepage' => [
                'hero_title' => $payload['hero_title'],
                'hero_description' => $payload['hero_description'],
                'footer_note' => $payload['footer_note'] ?? null,
            ],
            'affiliate' => [
                'disclosure' => $payload['affiliate_disclosure'] ?? null,
            ],
        ]);

        return back()->with('status', 'Branding portal berhasil diperbarui.');
    }

    public function uploadLogo(UploadBrandLogoRequest $request): RedirectResponse
    {
        $file = $request->file('logo');
        $filename = 'portal-logo-'.now()->format('YmdHis').'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs('branding', $filename, 'public');

        $currentLogo = $this->portalSettings->branding()['logo_url'] ?? null;

        if (is_string($currentLogo) && str_starts_with($currentLogo, '/storage/')) {
            $oldPath = ltrim(substr($currentLogo, strlen('/storage/')), '/');
            if ($oldPath !== $path) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        $this->portalSettings->update([
            'branding' => [
                'logo_url' => Storage::disk('public')->url($path),
                'logo_alt' => $request->input('logo_alt') ?: $this->portalSettings->branding()['site_name'] ?? config('app.name'),
            ],
        ]);

        return back()->with('status', 'Logo portal berhasil diupload.');
    }

    public function destroyLogo(): RedirectResponse
    {
        $currentLogo = $this->portalSettings->branding()['logo_url'] ?? null;

        if (is_string($currentLogo) && str_starts_with($currentLogo, '/storage/')) {
            Storage::disk('public')->delete(ltrim(substr($currentLogo, strlen('/storage/')), '/'));
        }

        $this->portalSettings->forgetLogo();

        return back()->with('status', 'Logo portal berhasil dihapus.');
    }

    public function uploadArticleImages(UploadArticleImagesRequest $request): RedirectResponse
    {
        $stored = $this->articleMediaLibrary->storeMany(
            $request->string('category')->toString(),
            $request->file('images', [])
        );

        return back()->with('status', count($stored).' gambar artikel berhasil diupload.');
    }

    public function destroyArticleImage(Request $request): RedirectResponse
    {
        $payload = $request->validate([
            'path' => ['required', 'string'],
        ]);

        $this->articleMediaLibrary->delete($payload['path']);

        return back()->with('status', 'Gambar artikel berhasil dihapus.');
    }

    private function parseSourceReferences(?string $text): array
    {
        if (! filled($text)) {
            return [];
        }

        return collect(preg_split('/\r\n|\r|\n/', $text) ?: [])
            ->map(function (string $line): ?array {
                $line = trim($line);

                if ($line === '') {
                    return null;
                }

                [$title, $publisher, $url, $year] = array_pad(array_map('trim', explode('|', $line)), 4, null);

                if (! $title) {
                    return null;
                }

                return [
                    'title' => $title,
                    'publisher' => $publisher ?: null,
                    'url' => filter_var((string) $url, FILTER_VALIDATE_URL) ? $url : null,
                    'year' => $year ?: null,
                ];
            })
            ->filter()
            ->values()
            ->all();
    }
}
