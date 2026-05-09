@extends('layouts.admin', ['title' => 'Konten & Media', 'subtitle' => 'Branding, topik artikel, media gambar, dan placement affiliate untuk portal production.'])

@php
    $themeLabels = collect($themes)->mapWithKeys(fn ($theme, $key) => [$key => $theme['label']]);
    $branding = $settings['branding'] ?? [];
    $homepage = $settings['homepage'] ?? [];
    $affiliate = $settings['affiliate'] ?? [];
    $selectedArticleReferences = collect($selectedArticle?->source_references ?? [])->map(
        fn ($reference) => trim(implode(' | ', array_filter([
            $reference['title'] ?? '',
            $reference['publisher'] ?? '',
            $reference['url'] ?? '',
            $reference['year'] ?? '',
        ], fn ($value) => filled($value))))
    )->filter()->implode("\n");
@endphp

@section('content')
<style>
    .article-admin {
        display: grid;
        grid-template-columns: 360px minmax(0, 1fr);
        gap: 18px;
        align-items: start;
    }
    .article-search-form {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin: 18px 0 16px;
    }
    .article-search-form > * {
        flex: 1 1 0;
    }
    .article-list {
        display: grid;
        gap: 10px;
        max-height: 980px;
        overflow-y: auto;
        padding-right: 4px;
    }
    .article-list-item {
        display: block;
        padding: 14px 16px;
        border-radius: 18px;
        border: 1px solid rgba(101, 73, 48, 0.12);
        background: rgba(255, 251, 246, 0.82);
    }
    .article-list-item.active {
        border-color: rgba(54, 73, 44, 0.28);
        background: rgba(229, 235, 215, 0.72);
        box-shadow: inset 0 0 0 1px rgba(54, 73, 44, 0.08);
    }
    .article-list-meta {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 8px;
        font-size: .9rem;
        color: var(--muted);
    }
    .article-editor-header {
        display: flex;
        align-items: start;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 16px;
    }
    .article-editor-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    .article-editor-actions form,
    .article-editor-actions a {
        width: auto;
    }
    .article-editor-actions button,
    .article-editor-actions a {
        width: auto;
        padding: 11px 16px;
        border-radius: 14px;
    }
    .pager {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-top: 14px;
        color: var(--muted);
    }
    .pager a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 14px;
        border-radius: 14px;
        border: 1px solid rgba(101, 73, 48, 0.16);
        background: rgba(255, 250, 243, 0.82);
        color: var(--text);
    }
    .editor-empty {
        padding: 20px;
        border-radius: 20px;
        border: 1px dashed rgba(101, 73, 48, 0.24);
        background: rgba(255, 251, 246, 0.6);
    }
    @media (max-width: 1240px) {
        .article-admin {
            grid-template-columns: 1fr;
        }
        .article-list {
            max-height: 420px;
        }
    }
</style>
<section class="grid grid-3" style="margin-bottom:18px;">
    <div class="stat">
        <span class="pill">Brand</span>
        <strong>{{ $branding['site_name'] ?? config('app.name', 'Arena Nalar') }}</strong>
        <div class="muted">Nama portal aktif di frontend dan artikel.</div>
    </div>
    <div class="stat">
        <span class="pill">Artikel</span>
        <strong>{{ $articlesPublishedCount }}</strong>
        <div class="muted">Artikel publish yang siap tampil di home.</div>
    </div>
    <div class="stat">
        <span class="pill">Media</span>
        <strong>{{ collect($articleImagesByCategory)->flatten(1)->count() }}</strong>
        <div class="muted">Gambar kategori yang siap dipakai sebagai cover artikel.</div>
    </div>
</section>

<div class="grid grid-2">
    <section class="panel">
        <span class="pill">Branding</span>
        <h3 style="margin:10px 0 6px;">Identitas Portal</h3>
        <p class="muted" style="margin:0;">Ubah nama web, tagline, copy hero, dan catatan affiliate tanpa edit file manual.</p>

        <form method="POST" action="{{ route('admin.seo.branding.update') }}" class="stack" style="margin-top:18px;">
            @csrf
            @method('PUT')
            <input type="text" name="site_name" value="{{ $branding['site_name'] ?? config('app.name', 'Arena Nalar') }}" placeholder="Nama website" required>
            <input type="text" name="tagline" value="{{ $branding['tagline'] ?? '' }}" placeholder="Tagline singkat">
            <input type="text" name="hero_title" value="{{ $homepage['hero_title'] ?? '' }}" placeholder="Judul hero" required>
            <textarea name="hero_description" rows="3" placeholder="Deskripsi hero" required>{{ $homepage['hero_description'] ?? '' }}</textarea>
            <input type="text" name="footer_note" value="{{ $homepage['footer_note'] ?? '' }}" placeholder="Catatan footer">
            <input type="text" name="affiliate_disclosure" value="{{ $affiliate['disclosure'] ?? '' }}" placeholder="Disclosure affiliate">
            <button type="submit">Simpan Branding</button>
        </form>
    </section>

    <section class="panel">
        <span class="pill">Logo</span>
        <h3 style="margin:10px 0 6px;">Upload Logo Website</h3>
        <p class="muted" style="margin:0;">Upload logo Anda di sini. File akan dipakai di home dan halaman artikel.</p>

        @if (!empty($branding['logo_url']))
            <div class="card-note" style="margin-top:18px;">
                <img src="{{ $branding['logo_url'] }}" alt="{{ $branding['logo_alt'] ?? ($branding['site_name'] ?? 'Logo') }}" style="width:88px;height:88px;object-fit:contain;border-radius:18px;background:#fff;border:1px solid rgba(101,73,48,.12);padding:10px;">
                <div class="muted" style="margin-top:10px;">Logo aktif saat ini.</div>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.seo.branding.logo.store') }}" enctype="multipart/form-data" class="stack" style="margin-top:18px;">
            @csrf
            <input type="file" name="logo" accept=".jpg,.jpeg,.png,.webp,.svg" required>
            <input type="text" name="logo_alt" value="{{ $branding['logo_alt'] ?? ($branding['site_name'] ?? '') }}" placeholder="Alt text logo">
            <button type="submit">Upload Logo</button>
        </form>

        @if (!empty($branding['logo_url']))
            <form method="POST" action="{{ route('admin.seo.branding.logo.destroy') }}" style="margin-top:12px;">
                @csrf
                @method('DELETE')
                <button type="submit" class="danger">Hapus Logo</button>
            </form>
        @endif
    </section>
</div>

<div class="grid grid-2" style="margin-top:18px;">
    <section class="panel">
        <span class="pill">Media Artikel</span>
        <h3 style="margin:10px 0 6px;">Upload Gambar per Kategori</h3>
        <p class="muted" style="margin:0;">Inilah tempat upload gambar artikel. Setiap artikel akan mengambil satu gambar cover secara otomatis dari kategori/topik yang cocok.</p>

        <form method="POST" action="{{ route('admin.seo.media.article-images.store') }}" enctype="multipart/form-data" class="stack" style="margin-top:18px;">
            @csrf
            <select name="category" required>
                <option value="">Pilih kategori gambar</option>
                @foreach ($themes as $key => $theme)
                    <option value="{{ $key }}">{{ $theme['label'] }}</option>
                @endforeach
            </select>
            <input type="file" name="images[]" accept=".jpg,.jpeg,.png,.webp" multiple required>
            <div class="card-note">
                <strong>Alur upload</strong>
                <div class="muted" style="margin-top:6px;">Upload banyak gambar sekaligus. Sistem akan menyimpan ke library kategori dan memakainya sebagai cover artikel.</div>
            </div>
            <button type="submit">Upload Gambar</button>
        </form>
    </section>

    <section class="panel">
        <span class="pill">Affiliate</span>
        <h3 style="margin:10px 0 6px;">Placement Link Involve Asia</h3>
        <p class="muted" style="margin:0;">Kalau akun Involve Asia Anda sudah aktif, ambil link affiliate dari dashboard Involve Asia lalu tempel ke `target_url`. Gambar banner opsional. Kalau tidak ada banner, placement tetap bisa tampil sebagai kartu rekomendasi teks.</p>
        <div class="card-note" style="margin-top:18px;">
            <strong>Alur paling mudah</strong>
            <div class="muted" style="margin-top:6px;">1. Login ke Involve Asia. 2. Pilih merchant atau produk. 3. Generate tracking link. 4. Paste link itu ke field `target_url` di bawah. 5. Isi nama placement dan CTA sesuai isi promosi.</div>
        </div>

        <form method="POST" action="{{ route('admin.seo.banners.store') }}" class="stack" style="margin-top:18px;">
            @csrf
            <input type="text" name="name" placeholder="Nama placement, contoh: Raket Badminton Partner" required>
            <select name="placement" required>
                <option value="article_inline">Article Inline</option>
                <option value="article_header">Article Header</option>
                <option value="article_footer">Article Footer</option>
                <option value="home_hero">Home Hero</option>
            </select>
            <input type="url" name="image_url" placeholder="URL gambar opsional">
            <input type="url" name="target_url" placeholder="URL affiliate / Involve Asia" required>
            <div class="inline">
                <input type="text" name="cta_text" placeholder="CTA opsional, contoh: Cek Raket">
                <input type="number" name="weight" value="10" min="1" max="100">
            </div>
            <label class="inline" style="justify-content:flex-start;">
                <input style="width:auto;" type="checkbox" name="is_active" value="1" checked>
                <span>Aktif</span>
            </label>
            <button type="submit">Simpan Placement</button>
        </form>
    </section>
</div>

<section class="panel" style="margin-top:18px;">
    <div class="inline" style="justify-content:space-between;align-items:start;">
        <div>
            <span class="pill">Generator</span>
            <h3 style="margin:10px 0 6px;">Generate Batch Artikel</h3>
            <p class="muted" style="margin:0;">Tema portal sekarang diarahkan ke olahraga, perlengkapan olahraga, IT, dan hidroponik. Prompt juga dikunci supaya tidak menyebut Gemini atau AI di artikel.</p>
        </div>
        <form method="POST" action="{{ route('admin.seo.generate') }}" style="width:auto;">
            @csrf
            <button type="submit" style="width:auto;padding:12px 18px;">Generate Sekarang</button>
        </form>
    </div>
</section>

<div class="grid grid-2" style="margin-top:18px;">
    <section class="panel">
        <span class="pill">Topik SEO</span>
        <h3 style="margin:10px 0 16px;">Tema Aktif</h3>
        <p class="muted" style="margin:0 0 16px;">Alurnya sederhana: Anda cukup aktifkan tema. Sistem akan memilih dan memutar keyword sendiri setiap kali generator jalan. Jadi keyword yang terlihat di sini hanya keyword putaran saat ini, bukan keyword tetap.</p>

        <form method="POST" action="{{ route('admin.seo.topics.store') }}" class="stack" style="margin-bottom:18px;">
            @csrf
            <select name="category" required>
                <option value="">Tambah tema baru</option>
                @foreach ($themes as $key => $theme)
                    <option value="{{ $key }}">{{ $theme['label'] }}</option>
                @endforeach
            </select>
            <label class="inline" style="justify-content:flex-start;">
                <input style="width:auto;" type="checkbox" name="is_active" value="1" checked>
                <span>Aktif untuk generator</span>
            </label>
            <button type="submit">Simpan Tema</button>
        </form>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tema</th>
                        <th>Keyword Putaran Saat Ini</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($topics as $topic)
                    <tr>
                        <td>
                            <form method="POST" action="{{ route('admin.seo.topics.update', $topic) }}" class="stack">
                                @csrf
                                @method('PUT')
                                <select name="category" required>
                                    @foreach ($themes as $key => $theme)
                                        <option value="{{ $key }}" @selected($topic->category === $key)>{{ $theme['label'] }}</option>
                                    @endforeach
                                </select>
                                <div class="card-note">
                                    <strong>{{ $themeLabels[$topic->category] ?? $topic->category }}</strong>
                                    <div class="muted" style="margin-top:6px;">{{ $themes[$topic->category]['description'] ?? 'Tema tersimpan.' }}</div>
                                    <div class="muted" style="margin-top:8px;">Keyword akan berganti otomatis saat batch generator berikutnya berjalan.</div>
                                </div>
                                <label class="inline" style="justify-content:flex-start;">
                                    <input style="width:auto;" type="checkbox" name="is_active" value="1" @checked($topic->is_active)>
                                    <span>Aktif</span>
                                </label>
                                <button type="submit">Update Tema</button>
                            </form>
                        </td>
                        <td>{{ $topic->keyword ?: '-' }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.seo.topics.destroy', $topic) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="muted">Belum ada tema aktif.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="panel">
        <span class="pill">Library Gambar</span>
        <h3 style="margin:10px 0 16px;">Gambar per Kategori</h3>

        @foreach ($themes as $key => $theme)
            <div class="card-note" style="margin-bottom:14px;">
                <strong>{{ $theme['label'] }}</strong>
                <div class="muted" style="margin:6px 0 12px;">{{ $theme['description'] }}</div>

                <div class="grid grid-3">
                    @forelse ($articleImagesByCategory[$key] ?? [] as $image)
                        <div style="padding:12px;border-radius:18px;background:rgba(255,255,255,.72);border:1px solid rgba(101,73,48,.12);">
                            <img src="{{ $image['url'] }}" alt="{{ $image['name'] }}" style="width:100%;aspect-ratio:4/3;object-fit:cover;border-radius:14px;border:1px solid rgba(101,73,48,.12);">
                            <div class="muted" style="margin-top:10px;font-size:.88rem;word-break:break-word;">{{ $image['name'] }}</div>
                            <form method="POST" action="{{ route('admin.seo.media.article-images.destroy') }}" style="margin-top:10px;">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="path" value="{{ $image['path'] }}">
                                <button type="submit" class="danger">Hapus Gambar</button>
                            </form>
                        </div>
                    @empty
                        <div class="muted">Belum ada gambar untuk kategori ini.</div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </section>
</div>

<section class="panel" style="margin-top:18px;">
    <span class="pill">Placement Affiliate</span>
    <h3 style="margin:10px 0 16px;">Link dan CTA Tersimpan</h3>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Placement</th>
                    <th>Target</th>
                    <th>Bobot</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($banners as $banner)
                <tr>
                    <td>{{ $banner->name }}</td>
                    <td>{{ $banner->placement }}</td>
                    <td style="min-width:320px;">
                        <form method="POST" action="{{ route('admin.seo.banners.update', $banner) }}" class="stack">
                            @csrf
                            @method('PUT')
                            <input type="text" name="name" value="{{ $banner->name }}" required>
                            <input type="hidden" name="placement" value="{{ $banner->placement }}">
                            <input type="url" name="image_url" value="{{ $banner->image_url }}" placeholder="URL gambar opsional">
                            <input type="url" name="target_url" value="{{ $banner->target_url }}" required>
                            <div class="inline">
                                <input type="text" name="cta_text" value="{{ $banner->cta_text }}" placeholder="CTA opsional">
                                <input type="number" name="weight" value="{{ $banner->weight }}" min="1" max="100">
                            </div>
                            <label class="inline" style="justify-content:flex-start;">
                                <input style="width:auto;" type="checkbox" name="is_active" value="1" @checked($banner->is_active)>
                                <span>Aktif</span>
                            </label>
                            <button type="submit">Update Placement</button>
                        </form>
                    </td>
                    <td>{{ $banner->weight }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.seo.banners.destroy', $banner) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="muted">Belum ada placement affiliate.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</section>

<section class="panel" style="margin-top:18px;">
    <span class="pill">Artikel</span>
    <h3 style="margin:10px 0 6px;">Daftar Artikel & Editor</h3>
    <p class="muted" style="margin:0;">Pilih satu artikel dari daftar kiri, lalu edit detailnya di panel kanan. Search dipakai untuk menangani data yang besar.</p>

    <form method="GET" action="{{ route('admin.seo.index') }}" class="article-search-form">
        <input type="text" name="article_search" value="{{ $articleSearch }}" placeholder="Cari judul, slug, excerpt, atau meta description">
        <button type="submit" style="width:auto;">Cari Artikel</button>
    </form>

    <div class="article-admin">
        <div>
            <div class="article-list">
                @forelse ($articles as $article)
                    <a
                        href="{{ route('admin.seo.index', array_filter(['article_search' => $articleSearch, 'page' => $articles->currentPage(), 'article' => $article->id])) }}"
                        class="article-list-item @if ($selectedArticle && $selectedArticle->id === $article->id) active @endif"
                    >
                        <strong>{{ $article->title }}</strong>
                        <div class="muted" style="margin-top:6px;">{{ \Illuminate\Support\Str::limit($article->slug, 48) }}</div>
                        <div class="article-list-meta">
                            <span>{{ $themeLabels[$article->topic?->category ?? ''] ?? 'Editorial' }}</span>
                            <span>{{ $article->status }}</span>
                            <span>{{ optional($article->published_at)->format('d M Y') ?: '-' }}</span>
                        </div>
                    </a>
                @empty
                    <div class="editor-empty">
                        <strong>Belum ada artikel.</strong>
                        <div class="muted" style="margin-top:6px;">Coba generate artikel atau ubah kata kunci pencarian.</div>
                    </div>
                @endforelse
            </div>

            @if ($articles->hasPages())
                <div class="pager">
                    <span>Halaman {{ $articles->currentPage() }} dari {{ $articles->lastPage() }}</span>
                    <div style="display:flex;gap:10px;flex-wrap:wrap;">
                        @if ($articles->onFirstPage())
                            <span></span>
                        @else
                            <a href="{{ $articles->previousPageUrl() }}">Sebelumnya</a>
                        @endif
                        @if ($articles->hasMorePages())
                            <a href="{{ $articles->nextPageUrl() }}">Berikutnya</a>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <div>
            @if ($selectedArticle)
                <div class="article-editor-header">
                    <div>
                        <span class="pill">Editor</span>
                        <h3 style="margin:10px 0 6px;">{{ $selectedArticle->title }}</h3>
                        <div class="muted">{{ $selectedArticle->slug }}</div>
                    </div>
                    <div class="article-editor-actions">
                        <a class="alt" href="{{ route('articles.show', $selectedArticle) }}" target="_blank" style="display:inline-flex;align-items:center;justify-content:center;border:1px solid rgba(101,73,48,.2);background:rgba(255,255,255,.86);">Buka</a>
                        <form method="POST" action="{{ route('admin.seo.articles.destroy', $selectedArticle) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="danger">Hapus</button>
                        </form>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.seo.articles.update', $selectedArticle) }}" class="stack">
                    @csrf
                    @method('PUT')
                    <input type="text" name="title" value="{{ $selectedArticle->title }}" required>
                    <input type="text" name="meta_description" value="{{ $selectedArticle->meta_description }}" required>
                    <textarea name="excerpt" rows="3">{{ $selectedArticle->excerpt }}</textarea>
                    <textarea name="content_html" rows="18" required>{{ $selectedArticle->content_html }}</textarea>
                    <textarea name="source_references_text" rows="5" placeholder="Judul | Penerbit | URL | Tahun">{{ $selectedArticleReferences }}</textarea>
                    <select name="status" required>
                        <option value="draft" @selected($selectedArticle->status === 'draft')>draft</option>
                        <option value="published" @selected($selectedArticle->status === 'published')>published</option>
                    </select>
                    <button type="submit">Update Artikel</button>
                </form>
            @else
                <div class="editor-empty">
                    <strong>Tidak ada artikel yang dipilih.</strong>
                    <div class="muted" style="margin-top:6px;">Pilih artikel dari daftar kiri untuk membuka editor.</div>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
