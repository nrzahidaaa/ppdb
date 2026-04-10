<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil — PPDB Online</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/ppdb.css') }}">
</head>
<body style="display:flex;align-items:center;justify-content:center;min-height:100vh;background:var(--bg);">
    <div style="background:white;border-radius:20px;padding:50px;text-align:center;max-width:480px;width:100%;box-shadow:var(--shadow-md);">
        <div style="font-size:60px;margin-bottom:16px;">🎉</div>
        <h1 style="font-size:22px;font-weight:800;color:var(--primary);margin-bottom:8px;">Pendaftaran Berhasil!</h1>
        <p style="color:var(--text-light);margin-bottom:24px;">Terima kasih telah mendaftar. Simpan NISN Anda untuk cek pengumuman.</p>

        <div style="background:var(--bg);border-radius:12px;padding:20px;margin-bottom:24px;">
        <div style="font-size:12px;color:var(--text-light);margin-bottom:6px;">NISN Anda</div>
        <div style="font-size:24px;font-weight:800;color:var(--primary);">{{ session('nisn') }}</div>
            <div style="font-size:14px;font-weight:600;margin-top:6px;">{{ session('nama') }}</div>
        </div>

        <div class="alert alert-warning" style="text-align:left;margin-bottom:24px;">
            ⚠️ Harap simpan NISN ini untuk keperluan verifikasi berkas.
        </div>

        <a href="{{ route('beranda') }}" class="btn btn-primary" style="width:100%;justify-content:center;">← Kembali ke Beranda</a>

        <a href="{{ route('pendaftaran.formEdit') }}" class="btn btn-outline" style="width:100%;justify-content:center;margin-top:8px;">
    ✏️ Edit Data Saya
        </a>
    </div>
</body>
</html>