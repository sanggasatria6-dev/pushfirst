<?php

namespace App\Http\Controllers;

use App\Models\AffiliateBanner;
use App\Models\Article;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function show(Article $article): View
    {
        $headerBanner = AffiliateBanner::pickOneForPlacement('article_header');
        $inlineBanners = AffiliateBanner::pickForPlacement('article_inline', 2);
        $footerBanner = AffiliateBanner::pickOneForPlacement('article_footer');

        $contentHtml = $this->injectInlineBanners($article->content_html, $inlineBanners->all());

        return view('articles.show', compact('article', 'headerBanner', 'footerBanner', 'contentHtml'));
    }

    private function injectInlineBanners(string $html, array $banners): string
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

            if ($paragraphCount === 2 && isset($banners[0])) {
                $result .= view('components.affiliate-banner', ['banner' => $banners[0]])->render();
            }

            if ($paragraphCount === 5 && isset($banners[1])) {
                $result .= view('components.affiliate-banner', ['banner' => $banners[1]])->render();
            }
        }

        return $result;
    }
}
