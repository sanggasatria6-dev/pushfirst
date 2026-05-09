<?php

namespace App\Http\Controllers;

use App\Models\AffiliateBanner;
use App\Models\Article;
use App\Services\VertexSeoFactoryService;
use App\Support\ArticleMediaLibrary;
use App\Support\PortalSettings;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends Controller
{
    public function __construct(
        private readonly ArticleMediaLibrary $articleMediaLibrary,
        private readonly PortalSettings $portalSettings,
        private readonly VertexSeoFactoryService $seoFactory,
    ) {
    }

    public function show(Article $article): View
    {
        $inlineBanners = AffiliateBanner::pickForPlacement('article_inline', 1);
        $contentHtml = $this->injectInlineBlocks($article->content_html, $inlineBanners->all());
        $theme = $this->seoFactory->themeOptions()[$article->topic?->category ?? ''] ?? [];
        $coverImage = $this->articleMediaLibrary->pickForArticle($article);

        return view('articles.show', [
            'article' => $article,
            'contentHtml' => $contentHtml,
            'coverImageUrl' => $coverImage['url'] ?? null,
            'portalBranding' => $this->portalSettings->branding(),
            'affiliateSettings' => $this->portalSettings->affiliate(),
            'themeLabel' => $theme['label'] ?? 'Artikel',
            'sourceReferences' => $article->source_references ?? [],
        ]);
    }

    public function cover(Article $article): Response
    {
        $image = $this->articleMediaLibrary->pickForArticle($article);

        if (! $image) {
            abort(404);
        }

        if (($image['storage'] ?? null) === 'public') {
            return response()->file(Storage::disk('public')->path($image['path']));
        }

        return response()->file(public_path($image['path']));
    }

    private function injectInlineBlocks(string $html, array $banners): string
    {
        if (count($banners) === 0) {
            return $html;
        }

        $parts = preg_split('/(<\/p>)/i', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
        $result = '';
        $paragraphCount = 0;

        for ($index = 0; $index < count($parts); $index += 2) {
            $paragraph = $parts[$index] ?? '';
            $closingTag = $parts[$index + 1] ?? '';

            if (Str::of(strip_tags($paragraph))->trim()->isEmpty()) {
                continue;
            }

            $paragraphCount++;
            $result .= $paragraph.$closingTag;

            if ($paragraphCount === 3 && isset($banners[0])) {
                $result .= view('components.affiliate-banner', ['banner' => $banners[0]])->render();
            }
        }

        return $result;
    }
}
