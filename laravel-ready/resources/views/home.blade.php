<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'SerbaInfo') }}</title>
    <style>
        :root {
            --bg: #f4efe8;
            --surface: rgba(255, 251, 247, 0.86);
            --surface-strong: #fffdf9;
            --line: rgba(100, 71, 50, 0.14);
            --line-strong: rgba(100, 71, 50, 0.24);
            --text: #231711;
            --muted: #6d5b50;
            --accent: #2b6a4d;
            --accent-strong: #1f4d39;
            --accent-soft: #e5f0e8;
            --warm: #b86a32;
            --warm-soft: #faefe1;
            --shadow: 0 28px 80px rgba(51, 31, 18, 0.09);
        }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            color: var(--text);
            font-family: Georgia, "Times New Roman", serif;
            background:
                radial-gradient(circle at top left, rgba(184, 106, 50, 0.14), transparent 28%),
                radial-gradient(circle at top right, rgba(43, 106, 77, 0.14), transparent 22%),
                linear-gradient(180deg, #fbf6f0 0%, var(--bg) 100%);
        }
        a { color: inherit; text-decoration: none; }
        .page { width: min(1200px, calc(100vw - 32px)); margin: 0 auto; padding: 24px 0 72px; }
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 18px 0 28px;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .brand-mark {
            width: 48px;
            height: 48px;
            display: grid;
            place-items: center;
            border-radius: 16px;
            color: #fff;
            font-weight: 700;
            background: linear-gradient(145deg, var(--accent), var(--warm));
            box-shadow: var(--shadow);
        }
        .brand-copy strong { display: block; font-size: 1.05rem; }
        .brand-copy span { color: var(--muted); font-size: .95rem; }
        .nav {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .nav a {
            padding: 10px 14px;
            border-radius: 999px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.55);
        }
        .hero {
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(320px, .8fr);
            gap: 22px;
            align-items: stretch;
        }
        .hero-main, .hero-side, .section-card, .article-card, .micro-card {
            border: 1px solid var(--line);
            border-radius: 30px;
            background: var(--surface);
            backdrop-filter: blur(12px);
            box-shadow: var(--shadow);
        }
        .hero-main {
            position: relative;
            overflow: hidden;
            padding: 34px;
            background:
                linear-gradient(145deg, rgba(255, 254, 252, 0.97), rgba(247, 238, 226, 0.9));
        }
        .hero-main::after {
            content: "";
            position: absolute;
            inset: auto -60px -80px auto;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(43, 106, 77, 0.18), transparent 68%);
        }
        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: var(--warm-soft);
            color: var(--warm);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
        }
        h1 {
            margin: 18px 0 14px;
            max-width: 10.5ch;
            font-size: clamp(2.4rem, 5vw, 4.8rem);
            line-height: .96;
        }
        .lead {
            max-width: 58ch;
            color: var(--muted);
            font-size: 1.08rem;
            line-height: 1.75;
        }
        .hero-meta {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin: 28px 0;
        }
        .metric {
            padding: 16px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.66);
            border: 1px solid rgba(100, 71, 50, 0.1);
        }
        .metric strong {
            display: block;
            margin-bottom: 6px;
            font-size: 1.55rem;
        }
        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px 18px;
            border-radius: 16px;
            font-weight: 700;
            transition: transform .16s ease, box-shadow .16s ease, background .16s ease;
        }
        .btn:hover { transform: translateY(-2px); }
        .btn-primary {
            color: #fff;
            background: linear-gradient(135deg, var(--accent-strong), var(--accent));
            box-shadow: 0 16px 36px rgba(31, 77, 57, 0.24);
        }
        .btn-secondary {
            border: 1px solid var(--line-strong);
            background: rgba(255, 255, 255, 0.68);
            color: var(--text);
        }
        .hero-side {
            padding: 18px;
            display: grid;
            gap: 14px;
            align-content: start;
            background:
                linear-gradient(180deg, rgba(255, 252, 248, 0.92), rgba(246, 239, 231, 0.86));
        }
        .banner-card {
            display: grid;
            gap: 14px;
            padding: 16px;
            border-radius: 22px;
            background: var(--surface-strong);
            border: 1px solid var(--line);
        }
        .banner-card img {
            width: 100%;
            display: block;
            border-radius: 18px;
            object-fit: cover;
        }
        .banner-card p, .section-head p, .micro-card p, .article-card p, .muted { color: var(--muted); }
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
            gap: 20px;
            margin-bottom: 18px;
        }
        .section-head h2 {
            margin: 8px 0 0;
            font-size: clamp(1.8rem, 3vw, 2.6rem);
        }
        .micro-grid, .article-grid {
            display: grid;
            gap: 18px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
        .micro-card, .article-card {
            padding: 22px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            background: rgba(255, 255, 255, 0.7);
        }
        .tag {
            display: inline-flex;
            width: fit-content;
            padding: 8px 12px;
            border-radius: 999px;
            background: var(--accent-soft);
            color: var(--accent-strong);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
        }
        .micro-card h3, .article-card h3 {
            margin: 0;
            font-size: 1.35rem;
            line-height: 1.2;
        }
        .article-meta {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            font-size: .94rem;
            color: var(--muted);
        }
        .empty {
            min-height: 220px;
            display: grid;
            place-items: center;
            text-align: center;
            border: 1px dashed var(--line-strong);
            border-radius: 24px;
            color: var(--muted);
            background: rgba(255, 255, 255, 0.42);
        }
        @media (max-width: 980px) {
            .hero, .micro-grid, .article-grid, .hero-meta { grid-template-columns: 1fr; }
            .section-head, .topbar { align-items: start; flex-direction: column; }
            h1 { max-width: none; }
        }
    </style>
</head>
<body>
    <div class="page">
        <header class="topbar">
            <div class="brand">
                <div class="brand-mark">SP</div>
                <div class="brand-copy">
                    <strong>{{ config('app.name', 'SerbaInfo Portal') }}</strong>
                    <span>Portal artikel, tools, dan rekomendasi digital yang tertata rapi.</span>
                </div>
            </div>
            <nav class="nav">
                <a href="#microsaas">Micro-SaaS</a>
                <a href="#articles">Artikel</a>
                <a href="{{ route('home') }}">Home</a>
            </nav>
        </header>

        <section class="hero">
            <div class="hero-main">
                <div class="eyebrow">Portal Siap Production</div>
                <h1>Konten SEO, katalog app, dan affiliate tampil lebih meyakinkan.</h1>
                <p class="lead">Semua elemen inti portal dirancang untuk terasa bersih, terkurasi, dan profesional. Micro-SaaS aktif, artikel terbaru, dan penawaran affiliate sekarang tampil dalam alur yang lebih jelas untuk pengunjung.</p>

                <div class="hero-meta">
                    <div class="metric">
                        <strong>{{ $featuredMicrosaas->count() }}</strong>
                        <span class="muted">Micro-SaaS aktif</span>
                    </div>
                    <div class="metric">
                        <strong>{{ $latestArticles->count() }}</strong>
                        <span class="muted">Artikel terbaru</span>
                    </div>
                    <div class="metric">
                        <strong>{{ $heroBanner ? '1' : '0' }}</strong>
                        <span class="muted">Promo hero aktif</span>
                    </div>
                </div>

                <div class="hero-actions">
                    @if ($heroBanner)
                        <a href="{{ $heroBanner->target_url }}" target="_blank" rel="nofollow sponsored" class="btn btn-primary">
                            {{ $heroBanner->cta_text ?: 'Lihat Penawaran Pilihan' }}
                        </a>
                    @endif
                    <a href="#microsaas" class="btn btn-secondary">Jelajahi katalog</a>
                </div>
            </div>

            <aside class="hero-side">
                @if ($heroBanner)
                    <a href="{{ $heroBanner->target_url }}" target="_blank" rel="nofollow sponsored" class="banner-card">
                        <img src="{{ $heroBanner->image_url }}" alt="{{ $heroBanner->name }}">
                        <div>
                            <strong>{{ $heroBanner->name }}</strong>
                            <p>{{ $heroBanner->cta_text ?: 'Banner ini ditampilkan otomatis dari placement home hero.' }}</p>
                        </div>
                    </a>
                @else
                    <div class="banner-card">
                        <strong>Belum ada banner hero aktif</strong>
                        <p>Tambahkan banner affiliate pada placement `home_hero` untuk menampilkan promo utama di area ini.</p>
                    </div>
                @endif
                <div class="banner-card">
                    <strong>Alur konten lebih terarah</strong>
                    <p>Artikel terbaru, katalog, dan promo kini memiliki hierarki visual yang lebih jelas sehingga portal terasa lebih matang saat dipakai live.</p>
                </div>
            </aside>
        </section>

        <section id="microsaas" class="section">
            <div class="section-card">
                <div class="section-head">
                    <div>
                        <div class="eyebrow">Katalog</div>
                        <h2>Micro-SaaS Aktif</h2>
                    </div>
                    <p>Aplikasi yang sudah siap dibuka langsung dari portal utama.</p>
                </div>
                <div class="micro-grid">
                    @forelse ($featuredMicrosaas as $item)
                        <article class="micro-card">
                            <span class="tag">{{ $item->price_label ?: 'Micro-SaaS' }}</span>
                            <h3>{{ $item->name }}</h3>
                            <p>{{ $item->tagline ?: $item->description }}</p>
                            <a class="btn btn-primary" href="{{ $item->frontend_entry_url }}" target="_blank">Buka App</a>
                        </article>
                    @empty
                        <div class="empty">Belum ada Micro-SaaS aktif.</div>
                    @endforelse
                </div>
            </div>
        </section>

        <section id="articles" class="section">
            <div class="section-card">
                <div class="section-head">
                    <div>
                        <div class="eyebrow">Editorial</div>
                        <h2>Artikel SEO Terbaru</h2>
                    </div>
                    <p>Konten terbit terbaru dari engine SEO dan editor portal.</p>
                </div>
                <div class="article-grid">
                    @forelse ($latestArticles as $article)
                        <article class="article-card">
                            <div class="article-meta">
                                <span>{{ optional($article->published_at)->format('d M Y') }}</span>
                                <span>{{ $article->generation_model ?: 'Editorial' }}</span>
                            </div>
                            <h3>{{ $article->title }}</h3>
                            <p>{{ $article->excerpt }}</p>
                            <a class="btn btn-secondary" href="{{ route('articles.show', $article) }}">Baca Artikel</a>
                        </article>
                    @empty
                        <div class="empty">Belum ada artikel publish.</div>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
</body>
</html>
