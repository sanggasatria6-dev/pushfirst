<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Microsaas;
use App\Services\VertexSeoFactoryService;
use App\Support\ArticleMediaLibrary;
use App\Support\PortalSettings;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(
        VertexSeoFactoryService $seoFactory,
        PortalSettings $settings,
        ArticleMediaLibrary $articleMediaLibrary,
    ): View
    {
        $articles = Article::query()
            ->where('status', 'published')
            ->latest('published_at')
            ->limit(8)
            ->get();

        $articleCoverUrls = $articles
            ->mapWithKeys(function (Article $article) use ($articleMediaLibrary): array {
                $image = $articleMediaLibrary->pickForArticle($article);

                return [$article->id => $image['url'] ?? null];
            })
            ->all();

        return view('home', [
            'featuredMicrosaas' => Microsaas::query()
                ->where('status', 'active')
                ->orderByDesc('is_featured')
                ->latest()
                ->limit(6)
                ->get(),
            'latestArticles' => $articles,
            'articleCount' => Article::query()->where('status', 'published')->count(),
            'themeLabels' => collect($seoFactory->themeOptions())->mapWithKeys(
                fn (array $theme, string $key) => [$key => $theme['label'] ?? $key]
            )->all(),
            'themeDescriptions' => collect($seoFactory->themeOptions())->mapWithKeys(
                fn (array $theme, string $key) => [$key => $theme['description'] ?? null]
            )->all(),
            'portalBranding' => $settings->branding(),
            'homepageSettings' => $settings->homepage(),
            'articleCoverUrls' => $articleCoverUrls,
        ]);
    }
}
