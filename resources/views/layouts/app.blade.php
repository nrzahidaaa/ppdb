 <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — PPDB Online</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/ppdb.css') }}">
    @stack('styles')


    <style>
/* Override pagination */
nav[role="navigation"] { display:none !important; }
nav svg { display:none !important; }
.pagination-wrapper nav ul,
.pagination-wrapper nav ol {
    display:flex !important;
    align-items:center !important;
    gap:4px !important;
    list-style:none !important;
    padding:0 !important;
    margin:0 !important;
}
.pagination-wrapper nav ul li a,
.pagination-wrapper nav ul li span,
.pagination-wrapper nav ol li a,
.pagination-wrapper nav ol li span {
    display:inline-flex !important;
    align-items:center !important;
    justify-content:center !important;
    min-width:32px !important;
    height:32px !important;
    padding:0 8px !important;
    border-radius:8px !important;
    border:1px solid #e5e7eb !important;
    font-size:12px !important;
    font-weight:600 !important;
    color:#33528A !important;
    text-decoration:none !important;
}
.pagination-wrapper nav ul li span[aria-current],
.pagination-wrapper nav ol li span[aria-current] {
    background:#33528A !important;
    color:white !important;
    border-color:#33528A !important;
}
</style>

</head>
<body>

<!-- ===== SIDEBAR ===== -->
<aside class="sidebar" style="display:flex;flex-direction:column;height:100vh;">
        <div class="sidebar-logo">
        <div class="sidebar-logo-icon">PP</div>
        <div>
            <div style="font-size:13px;font-weight:700;color:var(--primary);">PPDB Online</div>
            <div style="font-size:10px;color:var(--text-light);">T.A 2025/2026</div>
        </div>
    </div>

@if(isset($globalTahunAjarans) && $globalTahunAjarans->count())
    <div style="padding:16px 14px;border-top:1px solid #eef2f7;border-bottom:1px solid #eef2f7;background:#fff;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
            <div style="font-size:11px;font-weight:700;color:#64748b;letter-spacing:.5px;text-transform:uppercase;">
                Tahun Ajaran
            </div>

            @if($selectedTahunAjaran && $selectedTahunAjaran->is_active)
                <span style="font-size:10px;font-weight:700;background:#dcfce7;color:#166534;padding:3px 8px;border-radius:999px;">
                    Aktif
                </span>
            @endif
        </div>

        <form action="{{ route('tahun-ajaran.pilih') }}" method="POST" style="margin:0;">
            @csrf
            <select name="tahun_ajaran_id"
                    onchange="this.form.submit()"
                    style="
                        width:100%;
                        border:1px solid #dbe2ea;
                        border-radius:12px;
                        padding:12px 14px;
                        font-size:14px;
                        font-weight:600;
                        color:#1e293b;
                        background:#f8fafc;
                        outline:none;
                    ">
                @foreach($globalTahunAjarans as $ta)
                    <option value="{{ $ta->id }}"
                        {{ ($selectedTahunAjaran?->id == $ta->id) ? 'selected' : '' }}>
                        {{ $ta->nama_tahun_ajaran }}{{ $ta->is_active ? ' (Aktif)' : '' }}
                    </option>
                @endforeach
            </select>
        </form>

        <a href="{{ route('tahun-ajaran.index') }}"
           style="
                display:flex;
                align-items:center;
                gap:8px;
                margin-top:10px;
                padding:10px 12px;
                border-radius:10px;
                background:#f8fafc;
                color:#33528A;
                text-decoration:none;
                font-size:13px;
                font-weight:600;
           ">
            <span>⚙️</span>
            <span>Kelola tahun ajaran</span>
        </a>
    </div>
@endif

    <nav class="sidebar-nav">
        <div class="nav-label">Menu Utama</div>

        <a href="{{ route('dashboard') }}"
           class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span>🏠</span> Dashboard
        </a>

        <a href="{{ route('pendaftaran.index') }}"
           class="nav-item {{ request()->routeIs('pendaftaran.*') ? 'active' : '' }}">
            <span>📝</span> Pendaftaran
            @if($pendaftaranBaru ?? 0)
                <span class="nav-badge">{{ $pendaftaranBaru }}</span>
            @endif
        </a>

        <div class="nav-label">Data & Master</div>

        <a href="{{ route('master.index') }}"
           class="nav-item {{ request()->routeIs('master.*') ? 'active' : '' }}">
            <span>🗂️</span> Data Master
        </a>

        <a href="{{ route('siswa.index') }}"
           class="nav-item {{ request()->routeIs('siswa.*') ? 'active' : '' }}">
            <span>👨‍🎓</span> Data Siswa
        </a>

        <a href="{{ route('kelas.index') }}"
           class="nav-item {{ request()->routeIs('kelas.*') ? 'active' : '' }}">
            <span>🏫</span> Data Kelas
        </a>

        <a href="{{ route('nilai-tes.index') }}"
           class="nav-item {{ request()->routeIs('nilai-tes.*') ? 'active' : '' }}">
            <span>📝</span> Nilai Tes
        </a>

        <div class="nav-label">Proses Seleksi</div>

        <a href="{{ route('klasifikasi.index') }}"
           class="nav-item {{ request()->routeIs('klasifikasi.*') ? 'active' : '' }}">
            <span>🤖</span> Proses Klasifikasi
        </a>

        <a href="{{ route('klasifikasi.pembagian') }}"
           class="nav-item {{ request()->is('klasifikasi/pembagian') ? 'active' : '' }}">
            <span>📋</span> Pembagian Kelas
        </a>

        <div class="nav-label">Output</div>

        <a href="{{ route('laporan.index') }}"
           class="nav-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
            <span>📊</span> Laporan
        </a>

    </nav>

    <div class="sidebar-bottom">
        <div class="user-card">
            <div class="avatar">{{ substr(auth()->user()->name ?? 'A', 0, 2) }}</div>
            <div>
                <div style="font-size:12px;font-weight:600;line-height:1.2;">
                    {{ auth()->user()->name ?? 'Admin' }}
                </div>
                <div style="font-size:10px;color:var(--text-light);">Administrator</div>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-item" style="width:100%;border:none;background:none;cursor:pointer;color:#e05454;">
                <span>🚪</span> Logout
            </button>
        </form>
    </div>
</aside>

<!-- ===== MAIN CONTENT ===== -->
<div class="main-wrapper">

    <!-- Topbar -->
    <header class="topbar">
        <div>
            <h1 style="font-size:16px;font-weight:700;">@yield('page-title', 'Dashboard')</h1>
            <p style="font-size:11px;color:var(--text-light);">
                {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
                &nbsp;·&nbsp; Selamat datang, {{ auth()->user()->name ?? 'Admin' }}
            </p>
        </div>

        <div style="display:flex;align-items:center;gap:10px;">
            {{-- Search --}}
            <div style="display:flex;align-items:center;gap:8px;background:var(--bg);border:1px solid var(--border);border-radius:8px;padding:8px 14px;font-size:13px;color:var(--text-light);">
                🔍 Cari...
            </div>

            {{-- Notifications --}}
            <div style="position:relative;">
                <button style="width:36px;height:36px;border-radius:8px;border:1px solid var(--border);background:white;cursor:pointer;font-size:15px;">
                    🔔
                </button>
                <span style="position:absolute;top:6px;right:6px;width:7px;height:7px;background:#e05454;border-radius:50%;border:1px solid white;"></span>
            </div>

            {{-- Avatar --}}
            <div class="avatar" style="border-radius:9px;cursor:pointer;">
                {{ substr(auth()->user()->name ?? 'A', 0, 2) }}
            </div>
        </div>
    </header>

    <!-- Page Body -->
    <main class="page-content">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">❌ {{ session('error') }}</div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning">⚠️ {{ session('warning') }}</div>
        @endif

        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>
