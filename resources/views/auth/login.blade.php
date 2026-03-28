<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — PPDB Online</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/ppdb.css') }}">
    <style>
        body {
            display: flex; align-items: center; justify-content: center;
            min-height: 100vh;
        }
        body::before {
            content: ''; position: fixed;
            top: -100px; right: -100px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(51,169,160,0.12) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        body::after {
            content: ''; position: fixed;
            bottom: -80px; left: -80px;
            width: 350px; height: 350px;
            background: radial-gradient(circle, rgba(51,82,138,0.10) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
    </style>
</head>
<body>

<div class="auth-card" style="position:relative;z-index:1;">

    {{-- Logo --}}
    <div style="text-align:center;margin-bottom:28px;">
        <div style="width:56px;height:56px;background:linear-gradient(135deg,var(--primary),var(--secondary));border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;color:white;font-weight:800;font-size:20px;">PP</div>
        <h2 style="font-size:20px;font-weight:700;margin-bottom:5px;">Selamat Datang 👋</h2>
        <p style="color:var(--text-light);font-size:13px;">Masuk ke Sistem PPDB Online</p>
    </div>

    {{-- Session Error --}}
    @if($errors->any())
        <div class="alert alert-danger">
            ❌ {{ $errors->first() }}
        </div>
    @endif

    {{-- Form --}}
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label class="form-label">Username / Email</label>
            <div style="position:relative;">
                <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--text-light);">👤</span>
                <input
                    type="email"
                    name="email"
                    class="form-control"
                    style="padding-left:40px;"
                    placeholder="Masukkan email Anda"
                    value="{{ old('email') }}"
                    required autofocus
                >
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Password</label>
            <div style="position:relative;">
                <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--text-light);">🔒</span>
                <input
                    type="password"
                    name="password"
                    class="form-control"
                    style="padding-left:40px;"
                    placeholder="Masukkan password Anda"
                    required
                >
            </div>
        </div>

        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
            <label style="display:flex;align-items:center;gap:7px;font-size:12px;cursor:pointer;">
                <input type="checkbox" name="remember"> Ingat saya
            </label>
            @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" style="font-size:12px;color:var(--secondary);font-weight:600;text-decoration:none;">Lupa password?</a>
            @endif
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:13px;font-size:14px;">
            Masuk ke Sistem →
        </button>
    </form>

    <div style="text-align:center;margin-top:18px;font-size:12px;color:var(--text-light);">
        Kembali ke <a href="{{ route('beranda') }}" style="color:var(--secondary);font-weight:600;text-decoration:none;">Halaman Utama</a>
    </div>

    <div style="display:flex;align-items:center;justify-content:center;gap:6px;margin-top:24px;padding-top:20px;border-top:1px solid var(--border);font-size:11px;color:var(--text-light);">
        🔐 Sistem dilindungi enkripsi SSL
    </div>
</div>

</body>
</html>
