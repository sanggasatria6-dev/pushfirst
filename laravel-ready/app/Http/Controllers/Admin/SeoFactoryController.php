<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAffiliateBannerRequest;
use App\Http\Requests\StoreSeoTopicRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Jobs\GenerateSeoArticleJob;
use App\Models\AffiliateBanner;
use App\Models\Article;
use App\Models\ArticleTopic;
use App\Services\VertexSeoFactoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SeoFactoryController extends Controller
{
    public function __construct(private readonly VertexSeoFactoryService $seoFactory)
    {
    }

    public function index(): View
    {
        return view('admin.seo.index', [
            'topics' => ArticleTopic::latest()->get(),
            'articles' => Article::latest()->limit(20)->get(),
            'banners' => AffiliateBanner::latest()->get(),
        ]);
    }

    public function storeTopic(StoreSeoTopicRequest $request): RedirectResponse
    {
        ArticleTopic::create($request->validated());

        return back()->with('status', 'Topik SEO berhasil ditambahkan.');
    }

    public function storeBanner(StoreAffiliateBannerRequest $request): RedirectResponse
    {
        AffiliateBanner::create($request->validated());

        return back()->with('status', 'Banner affiliate berhasil ditambahkan.');
    }

    public function generate(): RedirectResponse
    {
        $topics = $this->seoFactory->pickTopicsForBatch(
            (int) config('portal.vertex.articles_per_run', 5)
        );

        foreach ($topics as $topic) {
            GenerateSeoArticleJob::dispatch($topic->id);
        }

        return back()->with('status', "{$topics->count()} job artikel dikirim ke queue.");
    }

    public function updateTopic(StoreSeoTopicRequest $request, ArticleTopic $topic): RedirectResponse
    {
        $topic->update($request->validated());

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

        return back()->with('status', 'Banner affiliate berhasil diperbarui.');
    }

    public function destroyBanner(AffiliateBanner $banner): RedirectResponse
    {
        $banner->delete();

        return back()->with('status', 'Banner affiliate berhasil dihapus.');
    }

    public function updateArticle(UpdateArticleRequest $request, Article $article): RedirectResponse
    {
        $payload = $request->validated();
        $payload['published_at'] = $payload['status'] === 'published'
            ? ($article->published_at ?? now())
            : null;

        $article->update($payload);

        return back()->with('status', 'Artikel berhasil diperbarui.');
    }

    public function destroyArticle(Article $article): RedirectResponse
    {
        $article->delete();

        return back()->with('status', 'Artikel berhasil dihapus.');
    }
}
