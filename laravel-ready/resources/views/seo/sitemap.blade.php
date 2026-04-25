<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    @foreach ($articles as $article)
        <url>
            <loc>{{ route('articles.show', $article) }}</loc>
            <lastmod>{{ optional($article->published_at ?? $article->updated_at)->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
            <image:image>
                <image:loc>{{ route('articles.cover', $article) }}</image:loc>
                <image:title>{{ $article->title }}</image:title>
            </image:image>
        </url>
    @endforeach
</urlset>
