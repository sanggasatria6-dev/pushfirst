<aside style="margin:24px 0;padding:16px;border:1px solid rgba(97,70,50,.14);border-radius:24px;background:rgba(255,252,248,.9);box-shadow:0 20px 48px rgba(50,31,18,.06);">
    <a href="{{ $banner->target_url }}" target="_blank" rel="nofollow sponsored" style="display:block;text-decoration:none;color:#241812;">
        <img src="{{ $banner->image_url }}" alt="{{ $banner->name }}" style="width:100%;display:block;border-radius:18px;object-fit:cover;">
    </a>
    <div style="margin-top:14px;display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;">
        <div>
            <div style="font-size:12px;letter-spacing:.08em;text-transform:uppercase;color:#9a6438;font-weight:700;">Affiliate</div>
            <strong style="display:block;margin-top:4px;">{{ $banner->name }}</strong>
        </div>
        <a href="{{ $banner->target_url }}" target="_blank" rel="nofollow sponsored" style="display:inline-flex;align-items:center;justify-content:center;padding:11px 16px;border-radius:14px;background:#25573f;color:#fff;text-decoration:none;font-weight:700;">
            {{ $banner->cta_text ?: 'Lihat Penawaran' }}
        </a>
    </div>
</aside>
