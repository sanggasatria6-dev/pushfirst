@extends('layouts.admin', ['title' => 'Micro-SaaS Deployment', 'subtitle' => 'Upload build frontend ZIP, simpan backend URL, lalu tampilkan otomatis di katalog'])

@section('content')
<div class="grid grid-2">
    <section class="panel">
        <h3>Upload Build Baru</h3>
        <form method="POST" action="{{ route('admin.microsaas.store') }}" enctype="multipart/form-data" class="stack">
            @csrf
            <input type="text" name="name" placeholder="Nama SaaS" required>
            <input type="text" name="slug" placeholder="Slug, contoh: seo-writer" required>
            <input type="text" name="tagline" placeholder="Tagline singkat">
            <textarea name="description" rows="4" placeholder="Deskripsi singkat untuk katalog"></textarea>
            <input type="url" name="backend_base_url" placeholder="https://api.saas-anda.com" required>
            <input type="text" name="price_label" placeholder="Mulai Rp49.000 / bulan">
            <label class="inline"><input style="width:auto;" type="checkbox" name="is_featured" value="1"> Tampilkan sebagai featured</label>
            <input type="file" name="frontend_build" accept=".zip" required>
            <button type="submit">Upload dan Deploy</button>
        </form>
    </section>

    <section class="panel">
        <h3>Alur Deploy</h3>
        <div class="stack muted">
            <div>1. Upload file ZIP hasil build frontend.</div>
            <div>2. Sistem extract ke `storage/app/microsaas/{slug}/releases/{timestamp}`.</div>
            <div>3. Build aktif di-copy ke `public/microsaas/{slug}`.</div>
            <div>4. URL frontend otomatis menjadi `/microsaas/{slug}/`.</div>
            <div>5. Card produk langsung muncul di homepage.</div>
        </div>
    </section>
</div>

<section class="panel" style="margin-top:20px;">
    <h3>Daftar Micro-SaaS</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Backend</th>
                <th>Frontend</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($microsaasList as $item)
            <tr>
                <td>
                    <strong>{{ $item->name }}</strong><br>
                    <span class="muted">{{ $item->slug }}</span><br>
                    <span class="muted">{{ $item->tagline }}</span>
                </td>
                <td><a href="{{ $item->backend_base_url }}" target="_blank">{{ $item->backend_base_url }}</a></td>
                <td><a href="{{ $item->frontend_entry_url }}" target="_blank">{{ $item->frontend_entry_url }}</a></td>
                <td><span class="tag">{{ $item->status }}</span></td>
                <td>
                    <form method="POST" action="{{ route('admin.microsaas.activate', $item) }}" style="margin-bottom:8px;">
                        @csrf
                        <button type="submit">Aktifkan Ulang</button>
                    </form>
                    <form method="POST" action="{{ route('admin.microsaas.destroy', $item) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background:#4d2f28;">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="muted">Belum ada Micro-SaaS diupload.</td></tr>
        @endforelse
        </tbody>
    </table>
</section>
@endsection
