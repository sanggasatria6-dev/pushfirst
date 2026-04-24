<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Portal' }}</title>
    <style>
        :root {
            --bg: #f4efe8;
            --surface: #fffdf9;
            --card: #ffffff;
            --line: #dbcdb7;
            --text: #211c17;
            --muted: #6c6259;
            --accent: #a43d2d;
            --accent-soft: #f8e5dc;
            --ok: #256f4b;
        }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Georgia, "Times New Roman", serif; background: linear-gradient(180deg, #f0e5d8, #faf7f2); color: var(--text); }
        a { color: inherit; text-decoration: none; }
        .shell { display: grid; grid-template-columns: 260px 1fr; min-height: 100vh; }
        .sidebar { padding: 28px; background: #201815; color: #f9f4ee; }
        .sidebar h1 { font-size: 24px; margin: 0 0 24px; }
        .sidebar nav { display: grid; gap: 10px; }
        .sidebar nav a { padding: 12px 14px; border-radius: 12px; background: rgba(255,255,255,.06); }
        .content { padding: 28px; }
        .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .panel { background: var(--surface); border: 1px solid var(--line); border-radius: 18px; padding: 20px; box-shadow: 0 10px 30px rgba(57, 33, 17, .06); }
        .grid { display: grid; gap: 20px; }
        .grid-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .grid-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .stat { padding: 18px; border-radius: 18px; background: var(--card); border: 1px solid var(--line); }
        .stat strong { display: block; font-size: 32px; margin-top: 8px; }
        input, textarea, select, button { width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid var(--line); font: inherit; }
        button { cursor: pointer; background: var(--accent); color: white; border: none; }
        .muted { color: var(--muted); }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 12px 10px; border-bottom: 1px solid var(--line); vertical-align: top; text-align: left; }
        .flash { padding: 14px 16px; margin-bottom: 18px; background: #e5f5ec; color: var(--ok); border-radius: 14px; }
        .stack { display: grid; gap: 14px; }
        .inline { display: flex; gap: 12px; align-items: center; }
        .tag { display: inline-block; padding: 6px 10px; border-radius: 999px; background: var(--accent-soft); color: var(--accent); font-size: 12px; }
        @media (max-width: 960px) {
            .shell { grid-template-columns: 1fr; }
            .grid-2, .grid-4 { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="shell">
    <aside class="sidebar">
        <h1>Mega Portal</h1>
        <nav>
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('admin.microsaas.index') }}">Micro-SaaS</a>
            <a href="{{ route('admin.seo.index') }}">SEO Factory</a>
            <a href="{{ route('home') }}" target="_blank">Lihat Home</a>
        </nav>
    </aside>
    <main class="content">
        <div class="topbar">
            <div>
                <h2 style="margin:0;">{{ $title ?? 'Admin Portal' }}</h2>
                <div class="muted">{{ $subtitle ?? 'Kelola portal, SEO factory, dan upload Micro-SaaS' }}</div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>

        @if (session('status'))
            <div class="flash">{{ session('status') }}</div>
        @endif

        @include('admin.partials.errors')

        @yield('content')
    </main>
</div>
</body>
</html>
