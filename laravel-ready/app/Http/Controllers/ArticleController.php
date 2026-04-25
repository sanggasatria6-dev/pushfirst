<?php

namespace App\Http\Controllers;

use App\Models\AffiliateBanner;
use App\Models\Article;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

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

    public function cover(Article $article): Response
    {
        $category = $article->topic?->category ?? 'default';
        $palette = match ($category) {
            'urban_farming' => ['#314a2a', '#6e8b58', '#efe6d5'],
            'informatics_learning' => ['#263f4b', '#5e7f8e', '#edf3ef'],
            'business_growth' => ['#4b3829', '#a66b3f', '#f2e2cf'],
            default => ['#32482a', '#8f623a', '#f1e4d4'],
        };
        $label = Str::limit($article->title, 72, '');
        $escapedTitle = e($label);
        $escapedCategory = e(Str::title(str_replace('_', ' ', $category)));

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 630" role="img" aria-label="{$escapedTitle}">
  <rect width="1200" height="630" fill="{$palette[2]}"/>
  <path d="M0 460 C230 360 355 560 590 450 C820 342 930 202 1200 260 L1200 630 L0 630 Z" fill="{$palette[1]}" opacity=".82"/>
  <path d="M0 360 C190 260 360 390 575 275 C790 160 1000 95 1200 155 L1200 0 L0 0 Z" fill="{$palette[0]}" opacity=".95"/>
  <circle cx="965" cy="430" r="126" fill="#ffffff" opacity=".16"/>
  <circle cx="1042" cy="360" r="52" fill="#ffffff" opacity=".18"/>
  <text x="82" y="108" fill="#fff8ef" font-family="Georgia, serif" font-size="34" letter-spacing="2">{$escapedCategory}</text>
  <foreignObject x="78" y="168" width="820" height="270">
    <div xmlns="http://www.w3.org/1999/xhtml" style="font-family:Georgia,serif;font-size:58px;line-height:1.08;font-weight:700;color:#fff8ef;">
      {$escapedTitle}
    </div>
  </foreignObject>
</svg>
SVG;

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=86400',
        ]);
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
