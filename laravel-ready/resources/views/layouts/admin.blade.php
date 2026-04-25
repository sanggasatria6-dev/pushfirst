<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Portal' }}</title>
    <style>
        :root {
            --bg: #efe3d4;
            --sidebar: #2a2f21;
            --sidebar-soft: #353d2b;
            --surface: rgba(250, 244, 236, 0.9);
            --surface-strong: #fff9f1;
            --line: rgba(101, 73, 48, 0.15);
            --line-strong: rgba(101, 73, 48, 0.26);
            --text: #24170e;
            --muted: #6d594a;
            --accent: #4d683b;
            --accent-strong: #36492c;
            --accent-soft: #e5ebd7;
            --warm: #aa6236;
            --danger: #8e4a31;
            --success: #40613b;
            --shadow: 0 26px 70px rgba(59, 34, 18, 0.1);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            color: var(--text);
            font-family: "Iowan Old Style", "Palatino Linotype", "Book Antiqua", Georgia, serif;
            background:
                radial-gradient(circle at top right, rgba(77, 104, 59, 0.1), transparent 22%),
                radial-gradient(circle at top left, rgba(170, 98, 54, 0.08), transparent 18%),
                linear-gradient(180deg, #faf1e5 0%, var(--bg) 100%);
        }
        a { color: inherit; text-decoration: none; }
        .shell {
            display: grid;
            grid-template-columns: 280px minmax(0, 1fr);
            min-height: 100vh;
        }
        .sidebar {
            padding: 26px;
            color: #f5efe8;
            background:
                linear-gradient(180deg, rgba(255,255,255,.04), rgba(255,255,255,0)),
                linear-gradient(180deg, var(--sidebar), #21261b);
            border-right: 1px solid rgba(255,255,255,.06);
        }
        .brand {
            padding: 20px;
            border-radius: 24px;
            background: rgba(255,255,255,.05);
            border: 1px solid rgba(255,255,255,.08);
        }
        .brand strong { display: block; font-size: 1.2rem; margin-bottom: 6px; }
        .brand span { color: rgba(245, 239, 232, 0.72); line-height: 1.6; }
        .sidebar nav {
            display: grid;
            gap: 10px;
            margin-top: 22px;
        }
        .sidebar nav a {
            padding: 14px 16px;
            border-radius: 16px;
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.06);
        }
        .sidebar nav a:hover { background: rgba(255,255,255,.08); }
        .content {
            padding: 28px;
        }
        .topbar {
            display: flex;
            align-items: start;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 24px;
        }
        .topbar h2 { margin: 0; font-size: clamp(1.8rem, 3vw, 2.5rem); }
        .panel, .stat {
            border: 1px solid var(--line);
            border-radius: 26px;
            background: var(--surface);
            box-shadow: var(--shadow);
            backdrop-filter: blur(10px);
        }
        .panel { padding: 22px; }
        .grid { display: grid; gap: 18px; }
        .grid-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .grid-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .stat {
            padding: 22px;
            background: linear-gradient(145deg, rgba(255, 250, 243, 0.9), rgba(239, 229, 214, 0.76));
        }
        .stat strong {
            display: block;
            margin-top: 8px;
            font-size: 2rem;
        }
        .muted { color: var(--muted); }
        .pill {
            display: inline-flex;
            width: fit-content;
            padding: 8px 12px;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--accent-soft), #efe3ce);
            color: var(--accent-strong);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }
        .flash {
            margin-bottom: 18px;
            padding: 14px 16px;
            border-radius: 18px;
            background: rgba(29, 107, 71, 0.12);
            border: 1px solid rgba(29, 107, 71, 0.16);
            color: var(--success);
        }
        input, textarea, select, button {
            width: 100%;
            padding: 12px 14px;
            border-radius: 14px;
            border: 1px solid var(--line);
            background: rgba(255, 250, 243, 0.82);
            color: inherit;
            font: inherit;
        }
        textarea { resize: vertical; }
        button {
            cursor: pointer;
            border: none;
            color: #fff;
            background: linear-gradient(135deg, var(--accent-strong), var(--accent), #647f4f);
        }
        button.alt {
            background: rgba(255,255,255,.9);
            color: var(--text);
            border: 1px solid var(--line-strong);
        }
        button.danger { background: linear-gradient(135deg, #7a3d2a, var(--danger)); }
        .stack { display: grid; gap: 14px; }
        .inline {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .inline > * { flex: 1 1 0; }
        .inline label { flex: initial; }
        .table-wrap {
            overflow-x: auto;
            border-radius: 20px;
            border: 1px solid rgba(97, 70, 50, 0.08);
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            min-width: 840px;
        }
        .table th, .table td {
            padding: 14px 14px;
            border-bottom: 1px solid rgba(97, 70, 50, 0.08);
            text-align: left;
            vertical-align: top;
        }
        .table thead th {
            font-size: .9rem;
            color: var(--muted);
            background: rgba(255,255,255,.55);
        }
        .card-note {
            padding: 16px;
            border-radius: 18px;
            background: rgba(255, 248, 239, 0.66);
            border: 1px dashed var(--line-strong);
        }
        @media (max-width: 1040px) {
            .shell { grid-template-columns: 1fr; }
            .grid-2, .grid-3 { grid-template-columns: 1fr; }
            .content, .sidebar { padding: 20px; }
            .topbar { flex-direction: column; }
        }
    </style>
</head>
<body>
<div class="shell">
    <aside class="sidebar">
        <div class="brand">
            <strong>Mega Portal Studio</strong>
            <span>Panel untuk produksi konten SEO, katalog Micro-SaaS, dan monetisasi affiliate.</span>
        </div>
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
                <span class="pill">Admin Portal</span>
                <h2>{{ $title ?? 'Admin Portal' }}</h2>
                <div class="muted">{{ $subtitle ?? 'Kelola portal, SEO factory, dan upload Micro-SaaS' }}</div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}" style="width:auto;">
                @csrf
                <button type="submit" style="width:auto;padding:12px 18px;">Logout</button>
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
