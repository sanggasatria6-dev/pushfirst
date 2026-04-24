<aside style="margin:24px 0;padding:18px;border:1px solid #dcc8b2;border-radius:20px;background:#fff8ef;">
    <a href="{{ $banner->target_url }}" target="_blank" rel="nofollow sponsored">
        <img src="{{ $banner->image_url }}" alt="{{ $banner->name }}" style="width:100%;border-radius:14px;">
    </a>
    @if ($banner->cta_text)
        <div style="margin-top:12px;">
            <a href="{{ $banner->target_url }}" target="_blank" rel="nofollow sponsored" style="display:inline-block;background:#9f3f2f;color:#fff;padding:10px 14px;border-radius:12px;text-decoration:none;">
                {{ $banner->cta_text }}
            </a>
        </div>
    @endif
</aside>
