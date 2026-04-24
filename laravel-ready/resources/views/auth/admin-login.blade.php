<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; background: radial-gradient(circle at top, #efd9bf, #f6f2ea 58%, #ddd3c0); font-family: Georgia, serif; }
        .card { width: min(460px, calc(100vw - 32px)); padding: 28px; border-radius: 24px; background: rgba(255,255,255,.9); border: 1px solid rgba(76,44,23,.15); box-shadow: 0 20px 60px rgba(71,40,18,.12); }
        input, button { width: 100%; padding: 14px 16px; margin-top: 12px; border-radius: 14px; border: 1px solid #cbb89f; font: inherit; }
        button { background: #9f3f2f; color: #fff; border: none; cursor: pointer; }
        .hint { color: #6a5848; font-size: 14px; }
        .error { color: #a33a2d; margin-top: 10px; }
    </style>
</head>
<body>
    <form class="card" method="POST" action="{{ route('admin.login.store') }}">
        @csrf
        <h1 style="margin:0 0 6px;">Admin Login</h1>
        <p class="hint" style="margin:0 0 16px;">Masuk ke backend Mega Portal melalui path tersembunyi: /{{ $adminPath }}/login</p>

        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus>

        <label>Password</label>
        <input type="password" name="password" required>

        <label style="display:flex;align-items:center;gap:8px;margin-top:12px;">
            <input type="checkbox" name="remember" value="1" style="width:auto;margin:0;">
            <span class="hint">Ingat sesi login</span>
        </label>

        @error('email')
            <div class="error">{{ $message }}</div>
        @enderror

        <button type="submit">Masuk</button>
    </form>
</body>
</html>
