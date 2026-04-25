<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MicrosaasController;
use App\Http\Controllers\Admin\SeoFactoryController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\FrontendConfigController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\HomeController;
use App\Models\Article;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;

$adminPath = trim(config('portal.admin_path', 'studio-panel'), '/');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/articles/{article:slug}/cover.svg', [ArticleController::class, 'cover'])->name('articles.cover');
Route::get('/articles/{article:slug}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/microsaas/{microsaas:slug}/config.json', FrontendConfigController::class)->name('microsaas.config');
Route::get('/robots.txt', function () use ($adminPath) {
    $content = implode("\n", [
        'User-agent: *',
        'Allow: /',
        "Disallow: /{$adminPath}",
        '',
        'Sitemap: '.url('/sitemap.xml'),
    ]);

    return Response::make($content, 200, [
        'Content-Type' => 'text/plain; charset=UTF-8',
    ]);
})->name('robots');

Route::get('/sitemap.xml', function () {
    $articles = Article::query()
        ->where('status', 'published')
        ->latest('published_at')
        ->get();

    $xml = view('seo.sitemap', [
        'articles' => $articles,
    ])->render();

    return Response::make($xml, 200, [
        'Content-Type' => 'application/xml; charset=UTF-8',
    ]);
})->name('sitemap');

Route::prefix($adminPath)->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'create'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'store'])->name('login.store');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('logout');

        Route::get('/microsaas', [MicrosaasController::class, 'index'])->name('microsaas.index');
        Route::post('/microsaas', [MicrosaasController::class, 'store'])->name('microsaas.store');
        Route::post('/microsaas/{microsaas}/activate', [MicrosaasController::class, 'activate'])->name('microsaas.activate');
        Route::delete('/microsaas/{microsaas}', [MicrosaasController::class, 'destroy'])->name('microsaas.destroy');

        Route::get('/seo', [SeoFactoryController::class, 'index'])->name('seo.index');
        Route::post('/seo/topics', [SeoFactoryController::class, 'storeTopic'])->name('seo.topics.store');
        Route::put('/seo/topics/{topic}', [SeoFactoryController::class, 'updateTopic'])->name('seo.topics.update');
        Route::delete('/seo/topics/{topic}', [SeoFactoryController::class, 'destroyTopic'])->name('seo.topics.destroy');
        Route::post('/seo/banners', [SeoFactoryController::class, 'storeBanner'])->name('seo.banners.store');
        Route::put('/seo/banners/{banner}', [SeoFactoryController::class, 'updateBanner'])->name('seo.banners.update');
        Route::delete('/seo/banners/{banner}', [SeoFactoryController::class, 'destroyBanner'])->name('seo.banners.destroy');
        Route::post('/seo/generate', [SeoFactoryController::class, 'generate'])->name('seo.generate');
        Route::put('/seo/articles/{article}', [SeoFactoryController::class, 'updateArticle'])->name('seo.articles.update');
        Route::delete('/seo/articles/{article}', [SeoFactoryController::class, 'destroyArticle'])->name('seo.articles.destroy');
    });
});
