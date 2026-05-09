<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $portalBranding['site_name'] ?? config('app.name', 'Arena Nalar') }}</title>
    <meta name="description" content="{{ $homepageSettings['hero_description'] ?? 'Portal editorial olahraga, IT, dan hidroponik.' }}">
    <meta name="robots" content="index,follow">
    <link rel="canonical" href="{{ url()->current() }}">
    <style>
        :root {
            --bg: #f8f6f1;
            --surface: #ffffff;
            --surface-soft: #f2efe8;
            --line: #d8d1c3;
            --text: #1f1f1b;
            --muted: #6a665d;
            --accent: #1f5c4d;
            --accent-strong: #163f36;
            --accent-soft: #deeee8;
            --warm: #ae6c32;
            --warm-soft: #f8ead8;
            --shadow: 0 18px 44px rgba(18, 28, 24, 0.08);
        }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            color: var(--text);
            font-family: "Iowan Old Style", "Palatino Linotype", Georgia, serif;
            background:
                radial-gradient(circle at top right, rgba(31, 92, 77, 0.08), transparent 24%),
                radial-gradient(circle at top left, rgba(174, 108, 50, 0.09), transparent 22%),
                linear-gradient(180deg, #fbfaf6 0%, var(--bg) 100%);
        }
        a { color: inherit; text-decoration: none; }
        .page { width: min(1180px, calc(100vw - 32px)); margin: 0 auto; padding: 24px 0 72px; }
        .topbar, .brand, .nav, .hero-actions, .stat-row, .footer {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }
        .topbar {
            justify-content: space-between;
            gap: 18px;
            padding-bottom: 26px;
        }
        .brand { gap: 14px; }
        .logo-box {
            width: 52px;
            height: 52px;
            display: grid;
            place-items: center;
            border-radius: 16px;
            border: 1px solid var(--line);
            background: var(--surface);
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        .logo-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 6px;
        }
        .logo-fallback {
            font-size: 1rem;
            font-weight: 700;
            color: var(--accent-strong);
        }
        .brand-copy strong {
            display: block;
            font-size: 1.05rem;
        }
        .brand-copy span {
            color: var(--muted);
            font-size: .95rem;
        }
        .nav {
            gap: 10px;
            justify-content: flex-end;
        }
        .nav a {
            padding: 10px 14px;
            border-radius: 999px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.78);
        }
        .hero {
            display: grid;
            grid-template-columns: minmax(0, 1.15fr) minmax(320px, .85fr);
            gap: 22px;
        }
        .hero-main, .hero-side, .section-card, .article-card, .topic-card, .tool-card {
            border: 1px solid var(--line);
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.88);
            box-shadow: var(--shadow);
            backdrop-filter: blur(10px);
        }
        .hero-main {
            padding: 34px;
            background:
                linear-gradient(135deg, rgba(255, 255, 255, 0.98), rgba(242, 239, 232, 0.94));
        }
        .eyebrow {
            display: inline-flex;
            padding: 8px 12px;
            border-radius: 999px;
            background: var(--accent-soft);
            color: var(--accent-strong);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
        }
        h1 {
            margin: 16px 0 14px;
            max-width: 11ch;
            font-size: clamp(2.5rem, 5vw, 4.8rem);
            line-height: .96;
        }
        .lead {
            max-width: 60ch;
            color: var(--muted);
            font-size: 1.08rem;
            line-height: 1.8;
        }
        .hero-actions {
            gap: 12px;
            margin-top: 24px;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 13px 18px;
            border-radius: 16px;
            font-weight: 700;
        }
        .btn-primary {
            color: #fff;
            background: linear-gradient(135deg, var(--accent-strong), var(--accent));
        }
        .btn-secondary {
            border: 1px solid var(--line);
            background: var(--surface);
        }
        .stat-row {
            gap: 12px;
            margin-top: 28px;
        }
        .stat {
            min-width: 150px;
            padding: 16px 18px;
            border-radius: 20px;
            border: 1px solid var(--line);
            background: var(--surface-soft);
        }
        .stat strong {
            display: block;
            margin-bottom: 6px;
            font-size: 1.5rem;
        }
        .hero-side {
            padding: 18px;
            display: grid;
            gap: 14px;
            align-content: start;
            background:
                linear-gradient(180deg, rgba(248, 250, 247, 0.95), rgba(245, 240, 232, 0.92));
        }
        .focus-card {
            padding: 18px;
            border-radius: 22px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.9);
        }
        .focus-card strong {
            display: block;
            margin-bottom: 6px;
            font-size: 1.02rem;
        }
        .focus-card p, .section-head p, .article-card p, .tool-card p, .topic-card p, .footer, .muted {
            color: var(--muted);
        }
        .section {
            margin-top: 34px;
        }
        .section-card {
            padding: 26px;
        }
        .section-head {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 18px;
        }
        .section-head h2 {
            margin: 10px 0 0;
            font-size: clamp(1.9rem, 3vw, 2.8rem);
        }
        .article-grid {
            display: grid;
            gap: 18px;
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
        .article-card {
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .article-card img {
            width: 100%;
            aspect-ratio: 4 / 3;
            object-fit: cover;
            display: block;
            border-bottom: 1px solid var(--line);
        }
        .article-copy {
            padding: 18px;
            display: flex;
            flex: 1;
            flex-direction: column;
            gap: 10px;
        }
        .article-copy h3 {
            margin: 0;
            font-size: 1.28rem;
            line-height: 1.28;
        }
        .article-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
            font-size: .92rem;
            color: var(--muted);
        }
        .tag {
            display: inline-flex;
            width: fit-content;
            padding: 7px 11px;
            border-radius: 999px;
            background: var(--warm-soft);
            color: var(--warm);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
        }
        .topic-grid, .tool-grid {
            display: grid;
            gap: 18px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
        .topic-card, .tool-card {
            padding: 22px;
        }
        .topic-card h3, .tool-card h3 {
            margin: 0 0 10px;
            font-size: 1.22rem;
        }
        .footer {
            justify-content: space-between;
            gap: 16px;
            margin-top: 36px;
            padding: 18px 0 0;
        }
        @media (max-width: 1080px) {
            .article-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .topic-grid, .tool-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 920px) {
            .hero { grid-template-columns: 1fr; }
            h1 { max-width: none; }
        }
        @media (max-width: 720px) {
            .page { width: min(100vw - 20px, 1180px); }
            .article-grid { grid-template-columns: 1fr; }
            .section-head, .topbar { align-items: flex-start; flex-direction: column; }
        }
    </style>
</head>
<body>
    <div class="page">
        <header class="topbar">
            <div class="brand">
                <div class="logo-box">
                    @if (!empty($portalBranding['logo_url']))
                        <img src="{{ $portalBranding['logo_url'] }}" alt="{{ $portalBranding['logo_alt'] ?? $portalBranding['site_name'] }}">
                    @else
                        <span class="logo-fallback">{{ $portalBranding['mark_text'] ?? 'AN' }}</span>
                    @endif
                </div>
                <div class="brand-copy">
                    <strong>{{ $portalBranding['site_name'] ?? config('app.name', 'Arena Nalar') }}</strong>
                    <span>{{ $portalBranding['tagline'] ?? 'Portal editorial olahraga, IT, dan hidroponik.' }}</span>
                </div>
            </div>

            <nav class="nav">
                <a href="#artikel">Artikel</a>
                <a href="#fokus">Fokus Topik</a>
                <a href="#tools">Tools</a>
                <a href="{{ route('home') }}">Home</a>
            </nav>
        </header>

        <section class="hero">
            <div class="hero-main">
                <span class="eyebrow">Portal Editorial</span>
                <h1>{{ $homepageSettings['hero_title'] ?? 'Olahraga, IT, dan hidroponik dalam satu arus konten.' }}</h1>
                <p class="lead">{{ $homepageSettings['hero_description'] ?? 'Produksi artikel ber-volume tinggi tetap harus punya arah, gambar yang konsisten, dan ruang affiliate yang tidak merusak pengalaman baca.' }}</p>

                <div class="hero-actions">
                    <a href="#artikel" class="btn btn-primary">Baca Artikel Terbaru</a>
                    <a href="{{ route('admin.seo.index') }}" class="btn btn-secondary">Kelola Konten</a>
                </div>

                <div class="stat-row">
                    <div class="stat">
                        <strong>{{ $articleCount }}</strong>
                        <span class="muted">Artikel publish</span>
                    </div>
                    <div class="stat">
                        <strong>{{ count($themeLabels) }}</strong>
                        <span class="muted">Pilar konten</span>
                    </div>
                    <div class="stat">
                        <strong>{{ $featuredMicrosaas->count() }}</strong>
                        <span class="muted">Tools aktif</span>
                    </div>
                </div>
            </div>

            <aside class="hero-side" id="fokus">
                @foreach ($themeLabels as $key => $label)
                    <div class="focus-card">
                        <strong>{{ $label }}</strong>
                        <p>{{ $themeDescriptions[$key] ?? 'Tema aktif untuk mesin produksi artikel.' }}</p>
                    </div>
                @endforeach
            </aside>
        </section>

        <section id="artikel" class="section">
            <div class="section-card">
                <div class="section-head">
                    <div>
                        <span class="eyebrow">Artikel Hari Ini</span>
                        <h2>Konten terbaru yang siap dibaca</h2>
                    </div>
                    <p>Tiap artikel tampil dengan satu cover image dan arah editorial yang lebih spesifik.</p>
                </div>

                <div class="article-grid">
                    @forelse ($latestArticles as $article)
                        <article class="article-card">
                            @if (!empty($articleCoverUrls[$article->id]))
                                <img src="{{ $articleCoverUrls[$article->id] }}" alt="{{ $article->title }}" loading="lazy">
                            @endif
                            <div class="article-copy">
                                <span class="tag">{{ $themeLabels[$article->topic?->category ?? ''] ?? 'Editorial' }}</span>
                                <h3>{{ $article->title }}</h3>
                                <p>{{ $article->excerpt }}</p>
                                <div class="article-meta">
                                    <span>{{ optional($article->published_at)->format('d M Y') }}</span>
                                    <a href="{{ route('articles.show', $article) }}">Baca selengkapnya</a>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="topic-card">
                            <h3>Belum ada artikel publish.</h3>
                            <p>Masuk ke panel admin lalu jalankan generator atau input artikel manual.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="section">
            <div class="section-card">
                <div class="section-head">
                    <div>
                        <span class="eyebrow">Peta Konten</span>
                        <h2>Arah portal yang baru</h2>
                    </div>
                    <p>Olahraga jadi arus utama, IT tetap hidup, dan hidroponik tidak dibuang.</p>
                </div>

                <div class="topic-grid">
                    @foreach ($themeLabels as $key => $label)
                        <article class="topic-card">
                            <span class="tag">{{ $label }}</span>
                            <h3>{{ $label }}</h3>
                            <p>{{ $themeDescriptions[$key] ?? 'Tema ini aktif untuk produksi konten.' }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="tools" class="section">
            <div class="section-card">
                <div class="section-head">
                    <div>
                        <span class="eyebrow">Tools & App</span>
                        <h2>Ruang untuk aplikasi dan eksperimen</h2>
                    </div>
                    <p>Masih tersedia jika Anda ingin menaruh tool, mini app, atau eksperimen digital lain.</p>
                </div>

                <div class="tool-grid">
                    @forelse ($featuredMicrosaas as $item)
                        <article class="tool-card">
                            <span class="tag">{{ $item->price_label ?: 'Tool' }}</span>
                            <h3>{{ $item->name }}</h3>
                            <p>{{ $item->tagline ?: $item->description }}</p>
                            <a class="btn btn-secondary" href="{{ $item->frontend_entry_url }}" target="_blank">Buka Tool</a>
                        </article>
                    @empty
                        <article class="tool-card">
                            <h3>Belum ada tools aktif.</h3>
                            <p>Bagian ini aman dibiarkan kosong atau diisi kemudian saat Anda menambah app sendiri.</p>
                        </article>
                    @endforelse
                </div>
            </div>
        </section>

        <footer class="footer">
            <div>
                <strong>{{ $portalBranding['site_name'] ?? config('app.name', 'Arena Nalar') }}</strong>
                <div class="muted">{{ $homepageSettings['footer_note'] ?? 'Portal editorial untuk olahraga, IT, dan hidroponik.' }}</div>
            </div>
            <div class="muted">Copyright &copy; {{ now()->year }}</div>
        </footer>
    </div>
</body>
</html>
