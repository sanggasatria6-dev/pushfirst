@extends('layouts.admin', ['title' => 'Dashboard'])

@section('content')
<section class="grid grid-4">
    <div class="stat"><span class="muted">Total Micro-SaaS</span><strong>{{ $stats['microsaas_total'] }}</strong></div>
    <div class="stat"><span class="muted">Micro-SaaS Aktif</span><strong>{{ $stats['microsaas_active'] }}</strong></div>
    <div class="stat"><span class="muted">Artikel Publish</span><strong>{{ $stats['articles_total'] }}</strong></div>
    <div class="stat"><span class="muted">Topik Aktif</span><strong>{{ $stats['topics_total'] }}</strong></div>
</section>

<section class="grid grid-2" style="margin-top:20px;">
    <div class="panel">
        <h3>Upload Micro-SaaS</h3>
        <p class="muted">Deploy frontend ZIP dan simpan URL backend agar listing langsung muncul di home.</p>
        <a class="tag" href="{{ route('admin.microsaas.index') }}">Buka Modul Micro-SaaS</a>
    </div>
    <div class="panel">
        <h3>Jalankan SEO Factory</h3>
        <p class="muted">Generate batch artikel dari topik aktif dengan satu klik atau lewat scheduler.</p>
        <a class="tag" href="{{ route('admin.seo.index') }}">Buka SEO Factory</a>
    </div>
</section>

<section class="grid grid-2" style="margin-top:20px;">
    <div class="panel">
        <h3>Micro-SaaS Terbaru</h3>
        <table class="table">
            <thead><tr><th>Nama</th><th>Status</th><th>Frontend</th></tr></thead>
            <tbody>
            @forelse ($latestMicrosaas as $item)
                <tr>
                    <td>{{ $item->name }}<br><span class="muted">{{ $item->slug }}</span></td>
                    <td><span class="tag">{{ $item->status }}</span></td>
                    <td><a href="{{ $item->frontend_entry_url }}" target="_blank">{{ $item->frontend_entry_url }}</a></td>
                </tr>
            @empty
                <tr><td colspan="3" class="muted">Belum ada Micro-SaaS.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="panel">
        <h3>Artikel Terbaru</h3>
        <table class="table">
            <thead><tr><th>Judul</th><th>Status</th><th>Tayang</th></tr></thead>
            <tbody>
            @forelse ($latestArticles as $article)
                <tr>
                    <td>{{ $article->title }}</td>
                    <td><span class="tag">{{ $article->status }}</span></td>
                    <td>{{ optional($article->published_at)->format('d M Y H:i') ?: '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="muted">Belum ada artikel.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
