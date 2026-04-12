<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Sekolah - PPDB Online</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/ppdb.css') }}">
    <style>
        body { background: white; }

        .nav-menu { display: flex; align-items: center; gap: 4px; }
        .nav-menu a {
            padding: 8px 14px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s;
        }
        .nav-menu a:hover,
        .nav-menu a.active {
            background: var(--bg);
            color: var(--primary);
        }

        .section {
            padding: 60px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .section-sub {
            text-align: center;
            color: var(--text-light);
            margin-bottom: 40px;
            font-size: 13px;
        }

        footer {
            background: var(--text);
            color: rgba(255,255,255,0.7);
            padding: 28px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<nav class="landing-nav">
    <div style="display:flex;align-items:center;gap:12px;">
        <div style="width:40px;height:40px;background:linear-gradient(135deg,var(--primary),var(--secondary));border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;font-weight:800;font-size:15px;">PP</div>
        <div>
            <div style="font-weight:700;font-size:14px;color:var(--primary);">PPDB Online</div>
            <div style="font-size:11px;color:var(--text-light);">MTsN 3 Tapin</div>
        </div>
    </div>

    <div class="nav-menu">
        <a href="{{ route('profil.sekolah') }}" class="active">🏫 Profil Sekolah</a>
        <a href="{{ url('/') }}#informasi">ℹ️ Informasi</a>
        <a href="{{ url('/') }}#alur">📋 Alur Daftar</a>
        <a href="{{ route('pengumuman') }}">📢 Pengumuman</a>
    </div>

    <a href="{{ route('login') }}" class="btn btn-primary">🔒 Login</a>
</nav>

<section class="section">
    <h2 class="section-title">Profil Sekolah</h2>
    <p class="section-sub">Mengenal lebih dekat MTsN 3 Tapin</p>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:32px;align-items:start;">
        <div>
            <div style="background:linear-gradient(135deg,var(--primary),var(--secondary));border-radius:16px;padding:32px;color:white;margin-bottom:20px;">
                <div style="font-size:48px;margin-bottom:12px;">🏫</div>
                <h3 style="font-size:20px;font-weight:800;margin-bottom:8px;">MTsN 3 Tapin</h3>
                <p style="font-size:13px;opacity:0.85;line-height:1.8;">
                    Madrasah Tsanawiyah Negeri 3 Tapin adalah lembaga pendidikan Islam menengah pertama
                    yang berkomitmen mencetak generasi berakhlak mulia dan berprestasi akademik tinggi.
                </p>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div style="background:white;border:1px solid var(--border);border-radius:12px;padding:16px;text-align:center;">
                    <div style="font-size:24px;font-weight:800;color:var(--primary);">A</div>
                    <div style="font-size:11px;color:var(--text-light);">Akreditasi</div>
                </div>
                <div style="background:white;border:1px solid var(--border);border-radius:12px;padding:16px;text-align:center;">
                    <div style="font-size:24px;font-weight:800;color:var(--secondary);">360</div>
                    <div style="font-size:11px;color:var(--text-light);">Kuota Siswa</div>
                </div>
                <div style="background:white;border:1px solid var(--border);border-radius:12px;padding:16px;text-align:center;">
                    <div style="font-size:24px;font-weight:800;color:#597001;">9</div>
                    <div style="font-size:11px;color:var(--text-light);">Rombel</div>
                </div>
                <div style="background:white;border:1px solid var(--border);border-radius:12px;padding:16px;text-align:center;">
                    <div style="font-size:24px;font-weight:800;color:var(--primary);">1985</div>
                    <div style="font-size:11px;color:var(--text-light);">Tahun Berdiri</div>
                </div>
            </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:16px;">
            <div style="background:white;border:1px solid var(--border);border-radius:12px;padding:20px;">
                <div style="font-size:13px;font-weight:700;margin-bottom:12px;color:var(--primary);">📍 Identitas Sekolah</div>
                <table style="width:100%;font-size:12px;border-collapse:collapse;">
                    <tr><td style="padding:6px 0;color:var(--text-light);width:40%;">Nama</td><td style="font-weight:600;">MTsN 3 Tapin</td></tr>
                    <tr><td style="padding:6px 0;color:var(--text-light);">NPSN</td><td style="font-weight:600;">30315678</td></tr>
                    <tr><td style="padding:6px 0;color:var(--text-light);">Alamat</td><td style="font-weight:600;">Jl. Pendidikan No. 1, Tapin</td></tr>
                    <tr><td style="padding:6px 0;color:var(--text-light);">Provinsi</td><td style="font-weight:600;">Kalimantan Selatan</td></tr>
                    <tr><td style="padding:6px 0;color:var(--text-light);">Status</td><td style="font-weight:600;">Negeri</td></tr>
                    <tr><td style="padding:6px 0;color:var(--text-light);">Akreditasi</td><td style="font-weight:600;"><span class="badge badge-success">A — Unggul</span></td></tr>
                </table>
            </div>

            <div style="background:white;border:1px solid var(--border);border-radius:12px;padding:20px;">
                <div style="font-size:13px;font-weight:700;margin-bottom:12px;color:var(--primary);">🎯 Visi & Misi</div>
                <div style="font-size:12px;color:var(--text-light);line-height:1.9;">
                    <strong style="color:var(--text);">Visi:</strong><br>
                    Terwujudnya madrasah yang unggul, islami, dan berdaya saing tinggi.<br><br>

                    <strong style="color:var(--text);">Misi:</strong><br>
                    ✓ Menyelenggarakan pendidikan berkualitas<br>
                    ✓ Membentuk karakter islami dan berakhlak mulia<br>
                    ✓ Mengembangkan potensi siswa secara optimal<br>
                    ✓ Menjalin kerjasama dengan masyarakat dan stakeholder
                </div>
            </div>
        </div>
    </div>
</section>

<footer>
    <div>
        <div style="color:white;font-weight:700;">PPDB Online — MTsN 3 Tapin</div>
        <div style="margin-top:4px;">© {{ date('Y') }} Sistem Informasi PPDB. All rights reserved.</div>
    </div>
    <div style="text-align:right;">
        <div>📧 ppdb@mtsn3tapin.sch.id</div>
        <div>📞 0812-3456-7890</div>
    </div>
</footer>

</body>
</html>