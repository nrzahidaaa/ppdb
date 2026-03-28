<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'PPDB Online') }} - @yield('title')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('css/ppdb.css') }}">
</head>
<body style="margin:0;padding:0;background:#f0f4f8;min-height:100vh;font-family:'Figtree',sans-serif;">

    {{-- NAVBAR --}}
    <nav style="background:#33528A;padding:14px 32px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 2px 8px rgba(0,0,0,0.15);">
        <a href="{{ url('/') }}" style="color:#fff;text-decoration:none;font-weight:700;font-size:16px;display:flex;align-items:center;gap:8px;">
            <span style="background:#C4E81D;color:#33528A;border-radius:8px;padding:4px 8px;font-size:12px;font-weight:800;">PP</span>
            PPDB Online
        </a>
        <div style="display:flex;gap:12px;">
            <a href="{{ route('pengumuman') }}" style="color:#fff;text-decoration:none;font-size:13px;opacity:.85;">📢 Pengumuman</a>
            <a href="{{ route('login') }}" style="background:#C4E81D;color:#33528A;text-decoration:none;font-size:13px;font-weight:700;padding:6px 16px;border-radius:8px;">🔐 Login Admin</a>
        </div>
    </nav>

    {{-- CONTENT --}}
    <main style="padding:32px 16px;">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer style="text-align:center;padding:20px;font-size:11px;color:#999;border-top:1px solid #e5e7eb;background:#fff;margin-top:40px;">
        © {{ date('Y') }} PPDB Online. All rights reserved.
    </footer>

</body>
</html>