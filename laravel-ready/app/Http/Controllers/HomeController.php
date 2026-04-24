<?php

namespace App\Http\Controllers;

use App\Models\AffiliateBanner;
use App\Models\Article;
use App\Models\Microsaas;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'heroBanners' => AffiliateBanner::query()
                ->where('placement', 'home_hero')
                ->where('is_active', true)
                ->inRandomOrder()
                ->limit(3)
                ->get(),
            'featuredMicrosaas' => Microsaas::query()
                ->where('status', 'active')
                ->orderByDesc('is_featured')
                ->latest()
                ->limit(12)
                ->get(),
            'latestArticles' => Article::query()
                ->where('status', 'published')
                ->latest('published_at')
                ->limit(6)
                ->get(),
        ]);
    }
}
