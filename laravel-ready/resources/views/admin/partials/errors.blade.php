@if ($errors->any())
    <div style="padding:14px 16px;margin-bottom:18px;background:#fde8e4;color:#8e2f21;border-radius:14px;">
        <strong>Ada input yang perlu diperbaiki.</strong>
        <ul style="margin:8px 0 0 18px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
