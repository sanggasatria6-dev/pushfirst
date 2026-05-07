<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->title }}</title>
    <meta name="description" content="{{ $article->meta_description }}">
    <meta name="robots" content="index,follow">
    <link rel="canonical" href="{{ route('articles.show', $article) }}">
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
            width: 46px;
            height: 46px;
            display: grid;
            place-items: center;
            border-radius: 14px;
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
        .back-link {
            display: inline-flex;
            padding: 10px 14px;
            border-radius: 999px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.8);
            color: var(--muted);
        }
        .shell {
            margin-top: 22px;
            padding: 30px;
            border-radius: 30px;
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
            padding: 22px;
            border-radius: 24px;
            border: 1px solid var(--line);
            background: rgba(248, 250, 247, 0.9);
        }
        .reference-box h2 {
            margin: 0 0 12px;
            font-size: 1.45rem;
        }
        .reference-list {
            display: grid;
            gap: 12px;
        }
        .reference-item {
            padding: 14px 16px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.85);
            border: 1px solid rgba(83, 78, 66, 0.1);
        }
        .reference-item strong {
            display: block;
            margin-bottom: 4px;
        }
        .muted { color: var(--muted); }
        @media (max-width: 720px) {
            .shell { padding: 22px; border-radius: 24px; }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="topbar">
            <div class="brand">
                <div class="logo-box">
                    @if (!empty($portalBranding['logo_url']))
                        <img src="{{ $portalBranding['logo_url'] }}" alt="{{ $portalBranding['logo_alt'] ?? $portalBranding['site_name'] }}">
                    @else
                        <span class="logo-fallback">{{ $portalBranding['mark_text'] ?? 'AN' }}</span>
                    @endif
                </div>
                <div>
                    <strong>{{ $portalBranding['site_name'] ?? config('app.name', 'Arena Nalar') }}</strong>
                    <div class="muted">{{ $portalBranding['tagline'] ?? 'Portal editorial olahraga, IT, dan hidroponik.' }}</div>
                </div>
            </div>

            <a href="{{ route('home') }}" class="back-link">Kembali ke Home</a>
        </div>

        <article class="shell">
            <span class="eyebrow">{{ $themeLabel }}</span>
            <h1>{{ $article->title }}</h1>
            <div class="meta">
                <span>{{ optional($article->published_at)->format('d M Y') }}</span>
                <span>{{ strtoupper($article->topic?->country_code ?? 'ID') }}</span>
            </div>
            <p class="deck">{{ $article->excerpt }}</p>
            <img class="cover" src="{{ route('articles.cover', $article) }}" alt="{{ $article->title }}" loading="eager">

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
                                    <div style="margin-top:6px;">
                                        <a href="{{ $reference['url'] }}" target="_blank" rel="nofollow noopener">{{ $reference['url'] }}</a>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if (!empty($affiliateSettings['disclosure']))
                <p class="muted" style="margin-top:22px;">{{ $affiliateSettings['disclosure'] }}</p>
            @endif
        </article>
    </div>
</body>
</html>
