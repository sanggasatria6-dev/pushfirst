<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleTopic;
use App\Models\Microsaas;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'microsaas_total' => Microsaas::count(),
                'microsaas_active' => Microsaas::where('status', 'active')->count(),
                'articles_total' => Article::count(),
                'topics_total' => ArticleTopic::where('is_active', true)->count(),
            ],
            'latestMicrosaas' => Microsaas::latest()->limit(5)->get(),
            'latestArticles' => Article::latest()->limit(5)->get(),
        ]);
    }
}
