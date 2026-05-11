@if ($banner->image_url)
    <aside style="margin:26px 0;">
        <a href="{{ $banner->target_url }}" target="_blank" rel="nofollow sponsored" style="display:block;text-decoration:none;">
            <img src="{{ $banner->image_url }}" alt="{{ $banner->name }}" style="width:100%;display:block;border-radius:22px;border:1px solid rgba(83,78,66,.14);box-shadow:0 18px 42px rgba(18,28,24,.08);object-fit:cover;">
        </a>
    </aside>
@else
    <aside style="margin:26px 0;padding:18px;border:1px solid rgba(83,78,66,.14);border-radius:24px;background:linear-gradient(180deg, rgba(247,250,247,.96), rgba(244,240,232,.9));box-shadow:0 18px 42px rgba(18,28,24,.08);overflow:hidden;">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;">
            <div>
                <div style="font-size:12px;letter-spacing:.08em;text-transform:uppercase;color:#8a572d;font-weight:700;">Rekomendasi Partner</div>
                <strong style="display:block;margin-top:4px;">{{ $banner->name }}</strong>
            </div>
            <a href="{{ $banner->target_url }}" target="_blank" rel="nofollow sponsored" style="display:inline-flex;align-items:center;justify-content:center;max-width:100%;padding:11px 16px;border-radius:14px;background:linear-gradient(135deg, #173f36, #1f5c4d);color:#fff;text-decoration:none;font-weight:700;box-shadow:0 12px 26px rgba(23,63,54,.22);text-align:center;">
                {{ $banner->cta_text ?: 'Lihat Rekomendasi' }}
            </a>
        </div>
    </aside>
@endif
