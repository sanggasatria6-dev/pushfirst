@extends('layouts.admin', ['title' => 'SEO Factory', 'subtitle' => 'Kelola topik, banner affiliate, dan produksi artikel otomatis dengan Vertex API'])

@section('content')
<div class="grid grid-2">
    <section class="panel">
        <h3>Tambah Topik SEO</h3>
        <form method="POST" action="{{ route('admin.seo.topics.store') }}" class="stack">
            @csrf
            <input type="text" name="keyword" placeholder="Keyword utama" required>
            <select name="category" required>
                <option value="buyer_guides">Buyer Guides</option>
                <option value="iot">IoT</option>
                <option value="informatics_learning">Informatics Learning</option>
            </select>
            <select name="search_intent" required>
                <option value="informational">Informational</option>
                <option value="commercial">Commercial</option>
                <option value="transactional">Transactional</option>
                <option value="navigational">Navigational</option>
            </select>
            <div class="inline">
                <input type="text" name="language" value="id" placeholder="Bahasa">
                <input type="text" name="country_code" value="ID" placeholder="Negara">
            </div>
            <label class="inline"><input style="width:auto;" type="checkbox" name="is_active" value="1" checked> Aktif untuk generator</label>
            <button type="submit">Simpan Topik</button>
        </form>
    </section>

    <section class="panel">
        <h3>Tambah Banner Affiliate</h3>
        <form method="POST" action="{{ route('admin.seo.banners.store') }}" class="stack">
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
            <input type="text" name="cta_text" placeholder="CTA, contoh: Top Up Sekarang">
            <input type="number" name="weight" value="10" min="1" max="100">
            <label class="inline"><input style="width:auto;" type="checkbox" name="is_active" value="1" checked> Aktif</label>
            <button type="submit">Simpan Banner</button>
        </form>
    </section>
</div>

<section class="panel" style="margin-top:20px;">
    <div class="inline" style="justify-content:space-between;">
        <div>
            <h3 style="margin:0;">Generate Artikel</h3>
            <p class="muted">Memakai topik aktif dengan rotasi berdasarkan `last_generated_at`.</p>
        </div>
        <form method="POST" action="{{ route('admin.seo.generate') }}">
            @csrf
            <button type="submit">Generate Sekarang</button>
        </form>
    </div>
</section>

<div class="grid grid-2" style="margin-top:20px;">
    <section class="panel">
        <h3>Topik Aktif</h3>
        <table class="table">
            <thead><tr><th>Keyword</th><th>Intent</th><th>Terakhir Generate</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse ($topics as $topic)
                <tr>
                    <td>
                        <form method="POST" action="{{ route('admin.seo.topics.update', $topic) }}" class="stack">
                            @csrf
                            @method('PUT')
                            <input type="text" name="keyword" value="{{ $topic->keyword }}" required>
                            <div class="inline">
                                <select name="category" required>
                                    @foreach (['buyer_guides', 'iot', 'informatics_learning'] as $category)
                                        <option value="{{ $category }}" @selected($topic->category === $category)>{{ $category }}</option>
                                    @endforeach
                                </select>
                                <select name="search_intent" required>
                                    @foreach (['informational', 'commercial', 'transactional', 'navigational'] as $intent)
                                        <option value="{{ $intent }}" @selected($topic->search_intent === $intent)>{{ ucfirst($intent) }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="language" value="{{ $topic->language }}" required>
                                <input type="text" name="country_code" value="{{ $topic->country_code }}" required>
                            </div>
                            <label class="inline"><input style="width:auto;" type="checkbox" name="is_active" value="1" @checked($topic->is_active)> Aktif</label>
                            <button type="submit">Update</button>
                        </form>
                    </td>
                    <td>{{ $topic->category }} / {{ $topic->search_intent }}</td>
                    <td>{{ optional($topic->last_generated_at)->format('d M Y H:i') ?: '-' }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.seo.topics.destroy', $topic) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:#4d2f28;">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="muted">Belum ada topik.</td></tr>
            @endforelse
            </tbody>
        </table>
    </section>

    <section class="panel">
        <h3>Banner Affiliate</h3>
        <table class="table">
            <thead><tr><th>Nama</th><th>Placement</th><th>Link</th><th>Aksi</th></tr></thead>
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
                            <input type="text" name="cta_text" value="{{ $banner->cta_text }}">
                            <div class="inline">
                                <input type="number" name="weight" value="{{ $banner->weight }}" min="1" max="100">
                                <label class="inline"><input style="width:auto;" type="checkbox" name="is_active" value="1" @checked($banner->is_active)> Aktif</label>
                            </div>
                            <button type="submit">Update</button>
                        </form>
                    </td>
                    <td>{{ $banner->placement }}</td>
                    <td><a href="{{ $banner->target_url }}" target="_blank">Buka</a></td>
                    <td>
                        <form method="POST" action="{{ route('admin.seo.banners.destroy', $banner) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:#4d2f28;">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="muted">Belum ada banner.</td></tr>
            @endforelse
            </tbody>
        </table>
    </section>
</div>

<section class="panel" style="margin-top:20px;">
    <h3>Artikel Terbaru</h3>
    <table class="table">
        <thead><tr><th>Artikel</th><th>Slug</th><th>Status</th><th>Preview</th><th>Aksi</th></tr></thead>
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
                        <textarea name="content_html" rows="12" required>{{ $article->content_html }}</textarea>
                        <select name="status" required>
                            <option value="draft" @selected($article->status === 'draft')>draft</option>
                            <option value="published" @selected($article->status === 'published')>published</option>
                        </select>
                        <button type="submit">Update</button>
                    </form>
                </td>
                <td>{{ $article->slug }}</td>
                <td>{{ $article->status }}</td>
                <td><a href="{{ route('articles.show', $article) }}" target="_blank">Buka</a></td>
                <td>
                    <form method="POST" action="{{ route('admin.seo.articles.destroy', $article) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background:#4d2f28;">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="muted">Belum ada artikel.</td></tr>
        @endforelse
        </tbody>
    </table>
</section>
@endsection
