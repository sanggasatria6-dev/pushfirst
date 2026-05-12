<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->title }}</title>
    <meta name="description" content="{{ $article->meta_description }}">
    <meta name="robots" content="index,follow">
    <link rel="canonical" href="{{ route('articles.show', $article) }}">
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $article->title }}">
    <meta property="og:description" content="{{ $article->meta_description }}">
    <meta property="og:url" content="{{ route('articles.show', $article) }}">
    <meta property="og:site_name" content="{{ $portalBranding['site_name'] ?? config('app.name', 'Arena Nalar') }}">
    @if (!empty($portalBranding['logo_url']))
        <link rel="icon" type="image/png" href="{{ $portalBranding['logo_url'] }}">
        <link rel="apple-touch-icon" href="{{ $portalBranding['logo_url'] }}">
    @endif
    <style>
        :root {
            --bg: #f8f6f1;
            --surface: rgba(255, 255, 255, 0.92);
            --line: rgba(83, 78, 66, 0.16);
            --text: #22211e;
            --muted: #676258;
            --accent: #1f5c4d;
            --accent-strong: #173f36;
            --accent-soft: #deeee8;
            --warm: #9a6231;
            --shadow: 0 22px 54px rgba(18, 28, 24, 0.08);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            color: var(--text);
            font-family: "Iowan Old Style", "Palatino Linotype", Georgia, serif;
            background:
                radial-gradient(circle at top right, rgba(31, 92, 77, 0.08), transparent 20%),
                radial-gradient(circle at top left, rgba(154, 98, 49, 0.08), transparent 20%),
                linear-gradient(180deg, #fcfbf8 0%, var(--bg) 100%);
        }
        a { color: inherit; text-decoration: none; }
        .page { width: min(920px, calc(100vw - 28px)); margin: 0 auto; padding: 28px 0 72px; }
        .topbar, .brand {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }
        .brand {
            justify-content: flex-start;
        }
        .logo-box {
            width: 58px;
            height: 58px;
            display: grid;
            place-items: center;
            border-radius: 16px;
            border: 1px solid var(--line);
            background: var(--surface);
            overflow: hidden;
        }
        .logo-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 6px;
        }
        .logo-fallback {
            color: var(--accent-strong);
            font-weight: 700;
        }
        .brand-link {
            display: inline-flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
            color: inherit;
        }
        .shell {
            margin-top: 22px;
            padding: 30px;
            border-radius: 22px;
            border: 1px solid var(--line);
            background: var(--surface);
            box-shadow: var(--shadow);
        }
        .eyebrow {
            display: inline-flex;
            padding: 8px 12px;
            border-radius: 999px;
            background: var(--accent-soft);
            color: var(--accent-strong);
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
            gap: 12px;
            color: var(--muted);
            margin-bottom: 18px;
            font-size: .96rem;
        }
        .deck {
            margin: 0 0 24px;
            color: var(--muted);
            font-size: 1.05rem;
            line-height: 1.85;
        }
        .cover {
            width: 100%;
            display: block;
            margin-bottom: 28px;
            border-radius: 22px;
            border: 1px solid var(--line);
            aspect-ratio: 1200 / 630;
            object-fit: cover;
            background: #f0ede7;
        }
        .content h2, .content h3 {
            margin-top: 32px;
            margin-bottom: 14px;
            line-height: 1.15;
        }
        .content h2 { font-size: clamp(1.6rem, 3vw, 2.2rem); color: #21473d; }
        .content h3 { font-size: clamp(1.2rem, 2.4vw, 1.55rem); color: #8a572d; }
        .content p, .content li {
            font-size: 1.08rem;
            line-height: 1.9;
            color: #2d2a25;
        }
        .content ul { padding-left: 22px; }
        .reference-box {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid rgba(83, 78, 66, 0.14);
        }
        .reference-box h2 {
            margin: 0 0 14px;
            font-size: 1.45rem;
        }
        .reference-list {
            display: grid;
            gap: 18px;
        }
        .reference-item {
            padding: 0;
            overflow: visible;
        }
        .reference-item strong {
            display: block;
            margin-bottom: 6px;
        }
        .reference-link {
            display: inline-block;
            margin-top: 8px;
            color: var(--accent);
            text-decoration: underline;
            text-underline-offset: 3px;
            overflow-wrap: anywhere;
            word-break: break-word;
        }
        .affiliate-banner {
            margin: 26px 0;
        }
        .affiliate-banner-link {
            display: block;
            text-decoration: none;
        }
        .affiliate-banner-image {
            width: 100%;
            display: block;
            border-radius: 16px;
            border: 1px solid rgba(83, 78, 66, 0.14);
            box-shadow: 0 16px 34px rgba(18, 28, 24, 0.08);
            object-fit: cover;
        }
        .affiliate-banner-card {
            padding: 18px;
            border: 1px solid rgba(83, 78, 66, 0.14);
            border-radius: 18px;
            background: linear-gradient(180deg, rgba(247,250,247,.96), rgba(244,240,232,.9));
            box-shadow: 0 18px 42px rgba(18,28,24,.08);
            overflow: hidden;
        }
        .affiliate-banner-card-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
        }
        .affiliate-banner-eyebrow {
            font-size: 12px;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #8a572d;
            font-weight: 700;
        }
        .affiliate-banner-title {
            display: block;
            margin-top: 4px;
        }
        .affiliate-banner-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            max-width: 100%;
            padding: 11px 16px;
            border-radius: 12px;
            background: linear-gradient(135deg, #173f36, #1f5c4d);
            color: #fff;
            text-decoration: none;
            font-weight: 700;
            box-shadow: 0 12px 26px rgba(23,63,54,.22);
            text-align: center;
        }
        .muted { color: var(--muted); }
        @media (max-width: 720px) {
            .page { width: min(100vw - 20px, 920px); padding: 20px 0 44px; }
            .topbar {
                align-items: flex-start;
            }
            .shell { padding: 18px; border-radius: 18px; }
            .logo-box {
                width: 52px;
                height: 52px;
            }
            .content p, .content li { font-size: 1rem; line-height: 1.78; }
            .affiliate-banner {
                margin: 22px -6px;
            }
            .affiliate-banner-image {
                border-radius: 12px;
            }
            .affiliate-banner-card {
                padding: 16px;
                border-radius: 14px;
            }
            .affiliate-banner-button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="topbar">
            <a href="{{ route('home') }}" class="brand-link">
                <div class="logo-box">
                    @if (!empty($portalBranding['logo_url']))
                        <img src="{{ $portalBranding['logo_url'] }}" alt="{{ $portalBranding['logo_alt'] ?? $portalBranding['site_name'] }}">
                    @else
                        <span class="logo-fallback">{{ $portalBranding['mark_text'] ?? 'AN' }}</span>
                    @endif
                </div>
                <div>
                    <strong>Serbainfo</strong>
                </div>
            </a>
        </div>

        <article class="shell">
            <span class="eyebrow">{{ $themeLabel }}</span>
            <h1>{{ $article->title }}</h1>
            <div class="meta">
                <span>{{ optional($article->published_at)->format('d M Y') }}</span>
                <span>{{ strtoupper($article->topic?->country_code ?? 'ID') }}</span>
            </div>
            <p class="deck">{{ $article->excerpt }}</p>
            @if (!empty($headerBanner))
                @include('components.affiliate-banner', ['banner' => $headerBanner])
            @endif
            @if ($coverImageUrl)
                <img class="cover" src="{{ $coverImageUrl }}" alt="{{ $article->title }}" loading="eager">
            @endif

            <div class="content">{!! $contentHtml !!}</div>

            @if (!empty($sourceReferences))
                <section class="reference-box">
                    <h2>Referensi</h2>
                    <div class="reference-list">
                        @foreach ($sourceReferences as $reference)
                            <div class="reference-item">
                                <strong>{{ $reference['title'] ?? 'Sumber' }}</strong>
                                <div class="muted">
                                    {{ $reference['publisher'] ?? 'Sumber tepercaya' }}
                                    @if (!empty($reference['year']))
                                        &middot; {{ $reference['year'] }}
                                    @endif
                                </div>
                                @if (!empty($reference['url']))
                                    <a class="reference-link" href="{{ $reference['url'] }}" target="_blank" rel="nofollow noopener">{{ $reference['url'] }}</a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if (!empty($footerBanner))
                @include('components.affiliate-banner', ['banner' => $footerBanner])
            @endif
        </article>
    </div>
</body>
</html>
