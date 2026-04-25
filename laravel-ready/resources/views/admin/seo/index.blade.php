@extends('layouts.admin', ['title' => 'SEO Factory', 'subtitle' => 'Tema artikel, keyword otomatis, banner affiliate acak, dan workflow SEO yang lebih siap production.'])

@php
    $themeLabels = collect($themes)->mapWithKeys(fn ($theme, $key) => [$key => $theme['label']]);
@endphp

@section('content')
<section class="grid grid-3" style="margin-bottom:18px;">
    <div class="stat">
        <span class="pill">Tema Aktif</span>
        <strong>{{ $topics->count() }}</strong>
        <div class="muted">Setiap tema menyimpan keyword aktif terakhir yang akan diganti otomatis saat generate.</div>
    </div>
    <div class="stat">
        <span class="pill">Banner</span>
        <strong>{{ $banners->where('is_active', true)->count() }}</strong>
        <div class="muted">Placement yang sama akan memakai random pick, jadi `home_hero` tidak tampil dobel sekaligus.</div>
    </div>
    <div class="stat">
        <span class="pill">Artikel</span>
        <strong>{{ $articles->where('status', 'published')->count() }}</strong>
        <div class="muted">Artikel terbaru yang siap dipreview, dirapikan, atau dihapus dari panel ini.</div>
    </div>
</section>

<div class="grid grid-2">
    <section class="panel">
        <div class="inline" style="justify-content:space-between;align-items:start;">
            <div>
                <span class="pill">Tema SEO</span>
                <h3 style="margin:10px 0 6px;">Tambah Tema Artikel</h3>
                <p class="muted" style="margin:0;">Anda cukup pilih tema. Keyword utama dan intent akan dipilih otomatis dari pool tema saat artikel diproduksi.</p>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.seo.topics.store') }}" class="stack" style="margin-top:18px;">
            @csrf
            <select name="category" required>
                <option value="">Pilih tema konten</option>
                @foreach ($themes as $key => $theme)
                    <option value="{{ $key }}">{{ $theme['label'] }}</option>
                @endforeach
            </select>
            <div class="card-note">
                <strong>Keyword utama otomatis</strong>
                <div class="muted" style="margin-top:6px;">Sistem akan memilih keyword yang relevan dari tema, lalu memutar keyword berikutnya saat generator berjalan supaya tidak perlu Anda isi manual tiap kali.</div>
            </div>
            <label class="inline" style="justify-content:flex-start;">
                <input style="width:auto;" type="checkbox" name="is_active" value="1" checked>
                <span>Aktif untuk generator</span>
            </label>
            <button type="submit">Simpan Tema</button>
        </form>
    </section>

    <section class="panel">
        <span class="pill">Banner Affiliate</span>
        <h3 style="margin:10px 0 6px;">Tambah Banner</h3>
        <p class="muted" style="margin:0;">CTA itu teks tombol ajakan klik, misalnya `Cek Promo` atau `Lihat Penawaran`. Opsional, karena sistem akan pakai fallback jika dikosongkan.</p>
        <form method="POST" action="{{ route('admin.seo.banners.store') }}" class="stack" style="margin-top:18px;">
            @csrf
            <input type="text" name="name" placeholder="Nama banner" required>
            <select name="placement" required>
                <option value="home_hero">Home Hero</option>
                <option value="article_header">Article Header</option>
                <option value="article_inline">Article Inline</option>
                <option value="article_footer">Article Footer</option>
            </select>
            <input type="url" name="image_url" placeholder="URL gambar banner" required>
            <input type="url" name="target_url" placeholder="URL affiliate" required>
            <div class="inline">
                <input type="text" name="cta_text" placeholder="CTA opsional, contoh: Cek Promo">
                <input type="number" name="weight" value="10" min="1" max="100" placeholder="Bobot">
            </div>
            <label class="inline" style="justify-content:flex-start;">
                <input style="width:auto;" type="checkbox" name="is_active" value="1" checked>
                <span>Aktif</span>
            </label>
            <button type="submit">Simpan Banner</button>
        </form>
    </section>
</div>

<section class="panel" style="margin-top:18px;">
    <div class="inline" style="justify-content:space-between;align-items:start;">
        <div>
            <span class="pill">Generator</span>
            <h3 style="margin:10px 0 6px;">Generate Artikel Sekarang</h3>
            <p class="muted" style="margin:0;">Generator akan memilih tema aktif berdasarkan rotasi `last_generated_at`, lalu mengganti keyword utama secara otomatis sebelum artikel dibuat.</p>
        </div>
        <form method="POST" action="{{ route('admin.seo.generate') }}" style="width:auto;">
            @csrf
            <button type="submit" style="width:auto;padding:12px 18px;">Generate Sekarang</button>
        </form>
    </div>
</section>

<div class="grid grid-2" style="margin-top:18px;">
    <section class="panel">
        <span class="pill">Tema Tersimpan</span>
        <h3 style="margin:10px 0 16px;">Topik Aktif</h3>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tema</th>
                        <th>Keyword Aktif</th>
                        <th>Intent</th>
                        <th>Terakhir Generate</th>
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
                                    <div class="muted" style="margin-top:6px;">{{ $themes[$topic->category]['description'] ?? 'Tema lama. Silakan sesuaikan ke tema baru.' }}</div>
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
                        <td>{{ optional($topic->last_generated_at)->format('d M Y H:i') ?: '-' }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.seo.topics.destroy', $topic) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="muted">Belum ada tema SEO.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="panel">
        <span class="pill">Affiliate</span>
        <h3 style="margin:10px 0 16px;">Banner Tersimpan</h3>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Banner</th>
                        <th>Placement</th>
                        <th>CTA</th>
                        <th>Bobot</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($banners as $banner)
                    <tr>
                        <td>
                            <form method="POST" action="{{ route('admin.seo.banners.update', $banner) }}" class="stack">
                                @csrf
                                @method('PUT')
                                <input type="text" name="name" value="{{ $banner->name }}" required>
                                <select name="placement" required>
                                    @foreach (['home_hero', 'article_header', 'article_inline', 'article_footer'] as $placement)
                                        <option value="{{ $placement }}" @selected($banner->placement === $placement)>{{ $placement }}</option>
                                    @endforeach
                                </select>
                                <input type="url" name="image_url" value="{{ $banner->image_url }}" required>
                                <input type="url" name="target_url" value="{{ $banner->target_url }}" required>
                                <label class="inline" style="justify-content:flex-start;">
                                    <input style="width:auto;" type="checkbox" name="is_active" value="1" @checked($banner->is_active)>
                                    <span>Aktif</span>
                                </label>
                                <button type="submit">Update Banner</button>
                            </form>
                        </td>
                        <td>{{ $banner->placement }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.seo.banners.update', $banner) }}" class="stack">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="name" value="{{ $banner->name }}">
                                <input type="hidden" name="placement" value="{{ $banner->placement }}">
                                <input type="hidden" name="image_url" value="{{ $banner->image_url }}">
                                <input type="hidden" name="target_url" value="{{ $banner->target_url }}">
                                <input type="text" name="cta_text" value="{{ $banner->cta_text }}" placeholder="CTA opsional">
                                <input type="number" name="weight" value="{{ $banner->weight }}" min="1" max="100">
                                <input type="hidden" name="is_active" value="{{ $banner->is_active ? 1 : 0 }}">
                                <button type="submit" class="alt">Simpan CTA/Bobot</button>
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
                    <tr><td colspan="5" class="muted">Belum ada banner.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

<section class="panel" style="margin-top:18px;">
    <span class="pill">Editorial</span>
    <h3 style="margin:10px 0 16px;">Artikel Terbaru</h3>
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
                    <td>
                        <form method="POST" action="{{ route('admin.seo.articles.update', $article) }}" class="stack">
                            @csrf
                            @method('PUT')
                            <input type="text" name="title" value="{{ $article->title }}" required>
                            <input type="text" name="meta_description" value="{{ $article->meta_description }}" required>
                            <textarea name="excerpt" rows="2">{{ $article->excerpt }}</textarea>
                            <textarea name="content_html" rows="10" required>{{ $article->content_html }}</textarea>
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
