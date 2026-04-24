<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->title }}</title>
    <meta name="description" content="{{ $article->meta_description }}">
    <style>
        body { margin: 0; background: #f9f5ee; color: #201813; font-family: Georgia, serif; }
        .container { width: min(860px, calc(100vw - 32px)); margin: 0 auto; padding: 32px 0 64px; }
        article { background: rgba(255,255,255,.9); border: 1px solid #dfcfba; border-radius: 24px; padding: 28px; box-shadow: 0 16px 48px rgba(70,35,15,.06); }
        h1 { font-size: clamp(34px, 4vw, 50px); line-height: 1.1; margin-bottom: 12px; }
        p, li { font-size: 18px; line-height: 1.8; color: #2b241f; }
        .meta { color: #72675c; margin-bottom: 24px; }
    </style>
</head>
<body>
    <div class="container">
        @if ($headerBanner)
            @include('components.affiliate-banner', ['banner' => $headerBanner])
        @endif

        <article>
            <div class="meta">{{ optional($article->published_at)->format('d M Y') }}</div>
            <h1>{{ $article->title }}</h1>
            {!! $contentHtml !!}
        </article>

        @if ($footerBanner)
            @include('components.affiliate-banner', ['banner' => $footerBanner])
        @endif
    </div>
</body>
</html>
