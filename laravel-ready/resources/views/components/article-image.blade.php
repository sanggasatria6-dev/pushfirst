<figure style="margin:22px 0 26px;">
    <img src="{{ $image['url'] }}" alt="{{ $image['alt'] ?? '' }}" loading="lazy" style="width:100%;display:block;border-radius:18px;border:1px solid rgba(107, 79, 56, 0.18);box-shadow:0 18px 44px rgba(58,35,18,.08);">
    @if (!empty($image['caption']))
        <figcaption style="margin-top:10px;color:#6d594a;font-size:.95rem;line-height:1.6;">{{ $image['caption'] }}</figcaption>
    @endif
</figure>
