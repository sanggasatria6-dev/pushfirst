<aside style="margin:24px 0;padding:16px;border:1px solid rgba(101,73,48,.15);border-radius:24px;background:linear-gradient(180deg, rgba(255,251,245,.96), rgba(244,235,223,.88));box-shadow:0 22px 52px rgba(58,35,18,.08);">
    <a href="{{ $banner->target_url }}" target="_blank" rel="nofollow sponsored" style="display:block;text-decoration:none;color:#241812;">
        <img src="{{ $banner->image_url }}" alt="{{ $banner->name }}" style="width:100%;display:block;border-radius:18px;object-fit:cover;">
    </a>
    <div style="margin-top:14px;display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;">
        <div>
            <div style="font-size:12px;letter-spacing:.08em;text-transform:uppercase;color:#8d542f;font-weight:700;">Affiliate</div>
            <strong style="display:block;margin-top:4px;">{{ $banner->name }}</strong>
        </div>
        <a href="{{ $banner->target_url }}" target="_blank" rel="nofollow sponsored" style="display:inline-flex;align-items:center;justify-content:center;padding:11px 16px;border-radius:14px;background:linear-gradient(135deg, #36492c, #4d683b, #688252);color:#fff;text-decoration:none;font-weight:700;box-shadow:0 14px 28px rgba(54,73,44,.22);">
            {{ $banner->cta_text ?: 'Lihat Penawaran' }}
        </a>
    </div>
</aside>
