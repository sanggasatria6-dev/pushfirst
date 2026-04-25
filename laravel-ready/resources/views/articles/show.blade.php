<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->title }}</title>
    <meta name="description" content="{{ $article->meta_description }}">
    <style>
        :root {
            --bg: #f2e7d9;
            --surface: rgba(252, 247, 240, 0.92);
            --surface-strong: #fffaf3;
            --line: rgba(107, 79, 56, 0.18);
            --text: #26180f;
            --muted: #6d594a;
            --accent: #49653a;
            --accent-soft: #e8eddc;
            --warm: #a76337;
            --shadow: 0 26px 70px rgba(58, 35, 18, 0.1);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            color: var(--text);
            font-family: "Iowan Old Style", "Palatino Linotype", "Book Antiqua", Georgia, serif;
            background:
                radial-gradient(circle at top right, rgba(73, 101, 58, 0.16), transparent 22%),
                radial-gradient(circle at top left, rgba(167, 99, 55, 0.14), transparent 20%),
                linear-gradient(180deg, #fbf3e8 0%, var(--bg) 100%);
        }
        .page { width: min(920px, calc(100vw - 28px)); margin: 0 auto; padding: 28px 0 64px; }
        .article-shell {
            padding: 30px;
            border-radius: 32px;
            border: 1px solid var(--line);
            background:
                linear-gradient(180deg, rgba(255, 251, 245, 0.96), rgba(247, 238, 226, 0.88));
            box-shadow: var(--shadow);
            backdrop-filter: blur(12px);
        }
        .eyebrow {
            display: inline-flex;
            padding: 7px 12px;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--accent-soft), #efe4d1);
            color: var(--accent);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }
        h1 {
            margin: 16px 0 12px;
            font-size: clamp(2.2rem, 5vw, 4rem);
            line-height: 1.02;
        }
        .meta {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            color: var(--muted);
            margin-bottom: 20px;
            font-size: .96rem;
        }
        .deck {
            margin: 0 0 28px;
            color: var(--muted);
            font-size: 1.05rem;
            line-height: 1.8;
        }
        .content { color: var(--text); }
        .content h2, .content h3 {
            line-height: 1.15;
            margin-top: 34px;
            margin-bottom: 14px;
        }
        .content h2 { font-size: clamp(1.6rem, 3vw, 2.2rem); }
        .content h3 { font-size: clamp(1.2rem, 2.4vw, 1.55rem); }
        .content h2 { color: #2f4428; }
        .content h3 { color: #8d542f; }
        .content p, .content li {
            font-size: 1.08rem;
            line-height: 1.9;
            color: #2f241e;
        }
        .content ul { padding-left: 22px; }
        .content strong { color: #1b130f; }
        .back-link {
            display: inline-flex;
            margin-bottom: 18px;
            color: var(--muted);
            padding: 10px 14px;
            border-radius: 999px;
            background: rgba(255, 249, 242, 0.8);
            border: 1px solid var(--line);
        }
        @media (max-width: 720px) {
            .article-shell { padding: 22px; border-radius: 24px; }
        }
    </style>
</head>
<body>
    <div class="page">
        <a href="{{ route('home') }}" class="back-link">Kembali</a>

        @if ($headerBanner)
            @include('components.affiliate-banner', ['banner' => $headerBanner])
        @endif

        <article class="article-shell">
            <span class="eyebrow">Artikel</span>
            <h1>{{ $article->title }}</h1>
            <div class="meta">
                <span>{{ optional($article->published_at)->format('d M Y') }}</span>
                <span>{{ strtoupper($article->topic?->country_code ?? 'ID') }}</span>
            </div>
            <p class="deck">{{ $article->excerpt }}</p>
            <div class="content">{!! $contentHtml !!}</div>
        </article>

        @if ($footerBanner)
            @include('components.affiliate-banner', ['banner' => $footerBanner])
        @endif
    </div>
</body>
</html>
