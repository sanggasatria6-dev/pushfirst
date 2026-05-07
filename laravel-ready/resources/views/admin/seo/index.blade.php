@extends('layouts.admin', ['title' => 'Konten & Media', 'subtitle' => 'Branding, topik artikel, media gambar, dan placement affiliate untuk portal production.'])

@php
    $themeLabels = collect($themes)->mapWithKeys(fn ($theme, $key) => [$key => $theme['label']]);
    $branding = $settings['branding'] ?? [];
    $homepage = $settings['homepage'] ?? [];
    $affiliate = $settings['affiliate'] ?? [];
@endphp

@section('content')
<section class="grid grid-3" style="margin-bottom:18px;">
    <div class="stat">
        <span class="pill">Brand</span>
        <strong>{{ $branding['site_name'] ?? config('app.name', 'Arena Nalar') }}</strong>
        <div class="muted">Nama portal aktif di frontend dan artikel.</div>
    </div>
    <div class="stat">
        <span class="pill">Artikel</span>
        <strong>{{ $articles->where('status', 'published')->count() }}</strong>
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
        <p class="muted" style="margin:0;">Kalau akun affiliate Anda belum siap, bagian ini bisa diisi belakangan. Tempel `target_url` saat link sudah jadi. Gambar banner opsional.</p>

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
                        <th>Keyword Aktif</th>
                        <th>Intent</th>
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
                                </div>
                                <label class="inline" style="justify-content:flex-start;">
                                    <input style="width:auto;" type="checkbox" name="is_active" value="1" @checked($topic->is_active)>
                                    <span>Aktif</span>
                                </label>
                                <button type="submit">Update Tema</button>
                            </form>
                        </td>
                        <td>{{ $topic->keyword ?: '-' }}</td>
                        <td>{{ ucfirst($topic->search_intent) }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.seo.topics.destroy', $topic) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="muted">Belum ada tema aktif.</td></tr>
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
    <h3 style="margin:10px 0 16px;">Editorial Terbaru</h3>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Artikel</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>Preview</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($articles as $article)
                <tr>
                    <td style="min-width:420px;">
                        <form method="POST" action="{{ route('admin.seo.articles.update', $article) }}" class="stack">
                            @csrf
                            @method('PUT')
                            <input type="text" name="title" value="{{ $article->title }}" required>
                            <input type="text" name="meta_description" value="{{ $article->meta_description }}" required>
                            <textarea name="excerpt" rows="2">{{ $article->excerpt }}</textarea>
                            <textarea name="content_html" rows="10" required>{{ $article->content_html }}</textarea>
                            <textarea name="source_references_text" rows="4" placeholder="Judul | Penerbit | URL | Tahun">@foreach ($article->source_references ?? [] as $reference){{ ($reference['title'] ?? '') }} | {{ ($reference['publisher'] ?? '') }} | {{ ($reference['url'] ?? '') }} | {{ ($reference['year'] ?? '') }}
@endforeach</textarea>
                            <select name="status" required>
                                <option value="draft" @selected($article->status === 'draft')>draft</option>
                                <option value="published" @selected($article->status === 'published')>published</option>
                            </select>
                            <button type="submit">Update Artikel</button>
                        </form>
                    </td>
                    <td>{{ $article->slug }}</td>
                    <td>{{ $article->status }}</td>
                    <td><a href="{{ route('articles.show', $article) }}" target="_blank">Buka</a></td>
                    <td>
                        <form method="POST" action="{{ route('admin.seo.articles.destroy', $article) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="muted">Belum ada artikel.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
