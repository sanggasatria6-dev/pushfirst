<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mega Portal</title>
    <style>
        body { margin: 0; font-family: Georgia, serif; background: linear-gradient(180deg, #f7efe2, #fffaf2); color: #261d16; }
        .wrap { width: min(1180px, calc(100vw - 32px)); margin: 0 auto; padding: 28px 0 64px; }
        .hero { display: grid; grid-template-columns: 1.2fr .8fr; gap: 22px; align-items: stretch; }
        .hero-main, .hero-side, .card, .article { background: rgba(255,255,255,.82); border: 1px solid #dfd0ba; border-radius: 24px; padding: 22px; box-shadow: 0 16px 48px rgba(71, 38, 17, .07); }
        .hero-main { background: linear-gradient(135deg, #fff4e6, #fbfbf8); }
        .cards { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 18px; margin-top: 22px; }
        .articles { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 18px; margin-top: 18px; }
        .muted { color: #6b6056; }
        .pill { display: inline-block; background: #9f3f2f; color: white; padding: 8px 12px; border-radius: 999px; font-size: 13px; }
        .btn { display: inline-block; background: #201815; color: #fff; padding: 12px 16px; border-radius: 14px; margin-top: 10px; text-decoration: none; }
        img { max-width: 100%; border-radius: 16px; display: block; }
        @media (max-width: 920px) {
            .hero, .cards, .articles { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="wrap">
        <section class="hero">
            <div class="hero-main">
                <span class="pill">Micro-SaaS Hub + Affiliate Portal</span>
                <h1>Temukan tools, top-up game, dan penawaran digital dalam satu portal.</h1>
                <p class="muted">Homepage ini otomatis bertambah saat admin upload frontend Micro-SaaS baru, lalu katalog langsung tampil tanpa edit manual.</p>
                @if ($heroBanners->first())
                    <a href="{{ $heroBanners->first()->target_url }}" target="_blank" class="btn">{{ $heroBanners->first()->cta_text ?: 'Lihat Penawaran Utama' }}</a>
                @endif
            </div>
            <div class="hero-side">
                @foreach ($heroBanners as $banner)
                    <a href="{{ $banner->target_url }}" target="_blank" style="display:block;margin-bottom:14px;">
                        <img src="{{ $banner->image_url }}" alt="{{ $banner->name }}">
                    </a>
                @endforeach
            </div>
        </section>

        <section style="margin-top:28px;">
            <h2>Micro-SaaS Aktif</h2>
            <div class="cards">
                @forelse ($featuredMicrosaas as $item)
                    <article class="card">
                        <span class="pill">{{ $item->price_label ?: 'Micro-SaaS' }}</span>
                        <h3>{{ $item->name }}</h3>
                        <p class="muted">{{ $item->tagline ?: $item->description }}</p>
                        <a class="btn" href="{{ $item->frontend_entry_url }}" target="_blank">Buka App</a>
                    </article>
                @empty
                    <div class="card">Belum ada Micro-SaaS aktif.</div>
                @endforelse
            </div>
        </section>

        <section style="margin-top:28px;">
            <h2>Artikel SEO Terbaru</h2>
            <div class="articles">
                @forelse ($latestArticles as $article)
                    <article class="article">
                        <div class="muted">{{ optional($article->published_at)->format('d M Y') }}</div>
                        <h3>{{ $article->title }}</h3>
                        <p class="muted">{{ $article->excerpt }}</p>
                        <a class="btn" href="{{ route('articles.show', $article) }}">Baca Artikel</a>
                    </article>
                @empty
                    <div class="article">Belum ada artikel publish.</div>
                @endforelse
            </div>
        </section>
    </div>
</body>
</html>
