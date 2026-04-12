<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPDB Online</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/ppdb.css') }}">
<style>
    html, body {
        height: 100%;
        margin: 0;
    }

    body {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        background: white;
    }

    .page-content {
        flex: 1;
    }

    .info-cards { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
    .info-card {
        background: white; border: 1px solid var(--border);
        border-radius: var(--radius); padding: 24px;
        transition: all 0.2s;
    }
    .info-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
    .info-card-icon {
        width: 44px; height: 44px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; margin-bottom: 14px;
    }

    .alur-steps {
        display: grid; grid-template-columns: repeat(5, 1fr);
        gap: 0; position: relative; margin-bottom: 40px;
    }
    .alur-steps::before {
        content: ''; position: absolute;
        top: 28px; left: 10%; width: 80%; height: 2px;
        background: var(--border); z-index: 0;
    }
    .alur-step {
        display: flex; flex-direction: column; align-items: center;
        text-align: center; padding: 0 8px; position: relative; z-index: 1;
    }
    .step-num {
        width: 56px; height: 56px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 16px; margin-bottom: 12px;
        border: 3px solid white; box-shadow: var(--shadow);
        color: white;
    }

    .jadwal-table { width: 100%; border-collapse: collapse; border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow); }
    .jadwal-table th { background: var(--primary); color: white; padding: 13px 20px; text-align: left; font-size: 12px; }
    .jadwal-table td { padding: 12px 20px; border-bottom: 1px solid var(--border); font-size: 13px; }
    .jadwal-table tr:last-child td { border-bottom: none; }
    .jadwal-table tr:nth-child(even) td { background: #f9fbff; }

    .cta-section {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        margin: 0 60px 60px; border-radius: 20px;
        padding: 50px; text-align: center; color: white;
    }

    .section { padding: 60px; max-width: 1200px; margin: 0 auto; }
    .section-title { text-align: center; font-size: 26px; font-weight: 700; margin-bottom: 8px; }
    .section-sub { text-align: center; color: var(--text-light); margin-bottom: 40px; font-size: 13px; }

    .stats-grid-hero { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; position: relative; z-index: 1; }
    .stat-hero {
        background: rgba(255,255,255,0.12); backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: var(--radius-sm); padding: 18px;
    }

    .footer-full {
        width: 100%;
        background: var(--text);
        color: rgba(255,255,255,0.7);
        padding: 28px 60px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        box-sizing: border-box;
        margin-top: auto;
    }

    .nav-menu { display: flex; align-items: center; gap: 4px; }
    .nav-menu a {
        padding: 8px 14px; font-size: 13px; font-weight: 500;
        color: var(--text); text-decoration: none;
        border-radius: 8px; transition: all 0.2s;
    }
    .nav-menu a:hover,
    .nav-menu a.active {
        background: var(--bg);
        color: var(--primary);
        font-weight: 600;
    }

    .landing-nav {
        position: sticky;
        top: 0;
        z-index: 999;
        background: white;
        border-bottom: 1px solid var(--border);
    }
</style>
</head>

<body>
<div class="page-content">
{{-- ===== NAVBAR ===== --}}
<nav class="landing-nav">
    <div style="display:flex;align-items:center;gap:12px;">
        <div style="width:40px;height:40px;background:linear-gradient(135deg,var(--primary),var(--secondary));border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;font-weight:800;font-size:15px;">PP</div>
        <div>
            <div style="font-weight:700;font-size:14px;color:var(--primary);">PPDB Online</div>
            <div style="font-size:11px;color:var(--text-light);">MTsN 3 Tapin</div>
        </div>
    </div>

    <div class="nav-menu">
        <a href="{{ route('profil.sekolah') }}">🏫 Profil Sekolah</a>
        <a href="{{ url('/') }}#informasi">ℹ️ Informasi</a>
<a href="{{ url('/') }}#alur">📋 Alur Daftar</a>
        <a href="{{ route('pengumuman') }}">📢 Pengumuman</a>
    </div>

    <a href="{{ route('login') }}" class="btn btn-primary">🔒 Login</a>
</nav>

{{-- ===== HERO ===== --}}
<div class="hero-section">
    <div>
        <div class="hero-badge">📋 Tahun Ajaran 2025/2026</div>
        <h1 class="hero-title">
            Sistem Informasi<br>
            <span>Penerimaan Peserta<br>Didik Baru</span>
        </h1>
        <p style="color:var(--text-light);font-size:14px;line-height:1.8;margin-bottom:28px;">
            Platform digital terpadu untuk proses seleksi dan penerimaan siswa baru.
            Mendukung klasifikasi otomatis berbasis analisis data untuk pembagian kelas
            yang objektif, transparan, dan efisien.
        </p>
        <div style="display:flex;gap:12px;">
            <a href="{{ route('login') }}" class="btn btn-highlight" style="font-size:14px;padding:13px 26px;">⚡ Masuk ke Sistem</a>
            <a href="#alur" class="btn btn-outline">📖 Panduan</a>
        </div>
    </div>

    <div class="hero-visual">
        <div style="font-size:11px;opacity:0.7;margin-bottom:16px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">
            Statistik PPDB 2025/2026
        </div>
        <div class="stats-grid-hero">
            <div class="stat-hero">
                <div style="font-size:28px;font-weight:800;">1.284</div>
                <div style="font-size:11px;opacity:0.8;margin-top:3px;">Total Pendaftar</div>
                <div style="display:inline-block;background:var(--highlight);color:var(--dark);font-size:9px;font-weight:700;padding:2px 7px;border-radius:10px;margin-top:6px;">+12% vs tahun lalu</div>
            </div>
            <div class="stat-hero">
                <div style="font-size:28px;font-weight:800;">360</div>
                <div style="font-size:11px;opacity:0.8;margin-top:3px;">Kuota Tersedia</div>
                <div style="display:inline-block;background:var(--highlight);color:var(--dark);font-size:9px;font-weight:700;padding:2px 7px;border-radius:10px;margin-top:6px;">9 Rombongan Belajar</div>
            </div>
            <div class="stat-hero">
                <div style="font-size:28px;font-weight:800;">847</div>
                <div style="font-size:11px;opacity:0.8;margin-top:3px;">Terverifikasi</div>
                <div style="display:inline-block;background:var(--highlight);color:var(--dark);font-size:9px;font-weight:700;padding:2px 7px;border-radius:10px;margin-top:6px;">66% selesai</div>
            </div>
            <div class="stat-hero">
                <div style="font-size:28px;font-weight:800;">437</div>
                <div style="font-size:11px;opacity:0.8;margin-top:3px;">Belum Lengkap</div>
                <div style="display:inline-block;background:var(--highlight);color:var(--dark);font-size:9px;font-weight:700;padding:2px 7px;border-radius:10px;margin-top:6px;">Perlu tindak lanjut</div>
            </div>
        </div>
    </div>
</div>

{{-- ===== INFORMASI PPDB ===== --}}
<section id="informasi" class="section" style="padding-bottom:0;">
    <h2 class="section-title">Informasi PPDB</h2>
    <p class="section-sub">Semua yang perlu kamu ketahui sebelum mendaftar</p>

    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;align-items:stretch;">
        
        <div style="background:white;border:1px solid var(--border);border-radius:12px;padding:24px;">
            <div style="font-size:28px;margin-bottom:12px;">📝</div>
            <div style="font-size:14px;font-weight:700;margin-bottom:10px;">Syarat Pendaftaran</div>
            <div style="font-size:12px;color:var(--text-light);line-height:2.1;">
                ✓ Mengisi Formulir Pendaftaran<br>
                ✓ Pas Foto 3x4 (3 lembar)<br>
                ✓ Fotokopi NISN<br>
                ✓ Fotokopi Akta Kelahiran dan KK<br>
                ✓ Fotokopi KIP atau PKH (jika ada)<br>
                ✓ Fotokopi piagam peringkat kelas (khusus jalur prestasi akademik)<br>
                ✓ Fotokopi piagam minimal tingkat kecamatan (khusus jalur prestasi non-akademik)
            </div>
        </div>

        <div style="background:white;border:1px solid var(--border);border-radius:12px;padding:24px;">
            <div style="font-size:28px;margin-bottom:12px;">🏆</div>
            <div style="font-size:14px;font-weight:700;margin-bottom:10px;">Jalur Prestasi</div>
            <div style="font-size:12px;color:var(--text-light);line-height:2.1;">
                ✓ Tidak melalui tes tertulis<br>
                ✓ Seleksi berdasarkan nilai rapor<br>
                ✓ Wajib melampirkan dokumen nilai rapor<br>
                ✓ Diperuntukkan bagi siswa berprestasi akademik maupun non-akademik
            </div>
        </div>

        <div style="background:white;border:1px solid var(--border);border-radius:12px;padding:24px;">
            <div style="font-size:28px;margin-bottom:12px;">📋</div>
            <div style="font-size:14px;font-weight:700;margin-bottom:10px;">Jalur Reguler</div>
            <div style="font-size:12px;color:var(--text-light);line-height:2.1;">
                ✓ Mengikuti tes tertulis<br>
                ✓ Mengikuti tes BTA (Baca Tulis Al-Qur'an)<br>
                ✓ Penilaian berdasarkan hasil tes<br>
                ✓ Terbuka untuk seluruh calon siswa
            </div>
        </div>
    </div>
</section>

{{-- ===== ALUR PENDAFTARAN ===== --}}
<section id="alur" class="section">
    <h2 class="section-title">Alur Pendaftaran</h2>
    <p class="section-sub">Ikuti 5 langkah mudah berikut untuk menyelesaikan proses pendaftaran</p>
    <div class="alur-steps">
        <div class="alur-step">
            <div class="step-num step-num-1">1</div>
            <div style="font-size:12px;font-weight:700;margin-bottom:4px;">Daftar Akun</div>
            <div style="font-size:11px;color:var(--text-light);">Buat akun dengan email aktif</div>
        </div>
        <div class="alur-step">
            <div class="step-num step-num-2">2</div>
            <div style="font-size:12px;font-weight:700;margin-bottom:4px;">Isi Formulir</div>
            <div style="font-size:11px;color:var(--text-light);">Lengkapi data diri &amp; prestasi</div>
        </div>
        <div class="alur-step">
            <div class="step-num step-num-3">3</div>
            <div style="font-size:12px;font-weight:700;margin-bottom:4px;">Upload Dokumen</div>
            <div style="font-size:11px;color:var(--text-light);">Unggah berkas yang diperlukan</div>
        </div>
        <div class="alur-step">
            <div class="step-num step-num-4">4</div>
            <div style="font-size:12px;font-weight:700;margin-bottom:4px;">Verifikasi</div>
            <div style="font-size:11px;color:var(--text-light);">Tunggu konfirmasi panitia</div>
        </div>
        <div class="alur-step">
            <div class="step-num step-num-5">5</div>
            <div style="font-size:12px;font-weight:700;margin-bottom:4px;">Pengumuman</div>
            <div style="font-size:11px;color:var(--text-light);">Hasil seleksi diumumkan</div>
        </div>
    </div>
</section>

{{-- ===== JADWAL TABLE ===== --}}
<div class="section" style="padding-top:0;">
    <h2 class="section-title">Jadwal Lengkap PPDB</h2>
    <p class="section-sub">Timeline resmi penerimaan peserta didik baru tahun ajaran 2025/2026</p>
    <table class="jadwal-table">
        <thead>
            <tr>
                <th>No</th><th>Kegiatan</th><th>Tanggal</th><th>Keterangan</th><th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jadwal ?? [] as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><strong>{{ $item->kegiatan }}</strong></td>
                <td>{{ $item->tanggal_formatted }}</td>
                <td>{{ $item->keterangan }}</td>
                <td>
                    @if($item->status === 'selesai')
                        <span class="badge badge-success">✓ Selesai</span>
                    @elseif($item->status === 'berlangsung')
                        <span class="badge badge-secondary">🔵 Berlangsung</span>
                    @else
                        <span class="badge badge-warning">⏳ Menunggu</span>
                    @endif
                </td>
            </tr>
            @endforeach

            @if(empty($jadwal) || count($jadwal) === 0)
            <tr><td>1</td><td><strong>Sosialisasi PPDB</strong></td><td>15–31 Mei 2025</td><td>Via website dan media sosial</td><td><span class="badge badge-success">✓ Selesai</span></td></tr>
            <tr><td>2</td><td><strong>Pendaftaran Online</strong></td><td>1–30 Juni 2025</td><td>Pengisian formulir & upload dokumen</td><td><span class="badge badge-secondary">🔵 Berlangsung</span></td></tr>
            <tr><td>3</td><td><strong>Verifikasi Berkas</strong></td><td>1–5 Juli 2025</td><td>Pemeriksaan kelengkapan dokumen</td><td><span class="badge badge-warning">⏳ Menunggu</span></td></tr>
            <tr><td>4</td><td><strong>Tes Seleksi</strong></td><td>7–8 Juli 2025</td><td>Tes akademik & wawancara</td><td><span class="badge badge-warning">⏳ Menunggu</span></td></tr>
            <tr><td>5</td><td><strong>Pengumuman Hasil</strong></td><td>10 Juli 2025</td><td>Via website & pengumuman langsung</td><td><span class="badge badge-warning">⏳ Menunggu</span></td></tr>
            <tr><td>6</td><td><strong>Daftar Ulang</strong></td><td>11–15 Juli 2025</td><td>Bagi siswa yang dinyatakan lulus</td><td><span class="badge badge-warning">⏳ Menunggu</span></td></tr>
            @endif
        </tbody>
    </table>
</div>

{{-- ===== CTA ===== --}}
<div class="cta-section">
    <h2 style="font-size:28px;font-weight:800;margin-bottom:10px;">Siap Mendaftar?</h2>
    <p style="opacity:0.85;margin-bottom:26px;">Daftarkan diri sekarang dan raih kesempatan belajar di sekolah terbaik kami.</p>
    <div style="display:flex;gap:12px;justify-content:center;">
        <a href="{{ route('pendaftaran.publik') }}" class="btn btn-highlight" style="font-size:14px;padding:14px 32px;">📝 Daftar Sekarang</a>
        <a href="{{ route('pengumuman') }}" class="btn" style="background:rgba(255,255,255,0.2);color:white;font-size:14px;padding:14px 32px;">📢 Cek Pengumuman</a>
    </div>
</div>
</div>
{{-- ===== FOOTER ===== --}}
<footer class="footer-full">
    <div>
        <div style="color:white;font-weight:700;">PPDB Online — MTsN 3 Tapin</div>
        <div style="margin-top:4px;">© {{ date('Y') }} Sistem Informasi PPDB. All rights reserved.</div>
    </div>
    <div style="text-align:right;">
        <div>📧 ppdb@mtsn3tapin.sch.id</div>
        <div>📞 0812-3456-7890</div>
    </div>
</footer>

<style>
@media (max-width: 992px) {
    #informasi > div[style*="grid-template-columns:repeat(4,1fr)"] {
        grid-template-columns: repeat(2,1fr) !important;
    }
}

@media (max-width: 768px) {
    #informasi > div[style*="grid-template-columns:repeat(4,1fr)"],
    #informasi > div[style*="grid-template-columns:repeat(2,1fr)"] {
        grid-template-columns: 1fr !important;
    }
}

html {
    scroll-behavior: smooth;
}

.section {
    scroll-margin-top: 90px;
}
</style>

<script>
const sections = document.querySelectorAll("section");
const navLinks = document.querySelectorAll(".nav-menu a");

window.addEventListener("scroll", () => {
    let current = "";

    sections.forEach(section => {
        const sectionTop = section.offsetTop - 120;
        if (scrollY >= sectionTop) {
            current = section.getAttribute("id");
        }
    });

    navLinks.forEach(link => {
        link.classList.remove("active");
        if (link.getAttribute("href") === "#" + current) {
            link.classList.add("active");
        }
    });
});
</script>

</body>
</html>