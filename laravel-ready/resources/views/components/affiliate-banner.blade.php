@if ($banner->image_url)
    <aside class="affiliate-banner affiliate-banner-image-only">
        <a href="{{ $banner->target_url }}" target="_blank" rel="nofollow sponsored" class="affiliate-banner-link">
            <img src="{{ $banner->image_url }}" alt="{{ $banner->name }}" class="affiliate-banner-image">
        </a>
    </aside>
@else
    <aside class="affiliate-banner affiliate-banner-card">
        <div class="affiliate-banner-card-inner">
            <div>
                <div class="affiliate-banner-eyebrow">Rekomendasi Partner</div>
                <strong class="affiliate-banner-title">{{ $banner->name }}</strong>
            </div>
            <a href="{{ $banner->target_url }}" target="_blank" rel="nofollow sponsored" class="affiliate-banner-button">
                {{ $banner->cta_text ?: 'Lihat Rekomendasi' }}
            </a>
        </div>
    </aside>
@endif
