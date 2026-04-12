<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Pengumuman — PPDB Online</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/ppdb.css') }}">
    <style>
        body {
            background: var(--bg);
            min-height: 100vh;
        }

        .publik-navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 40px;
            background: white;
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .pengumuman-wrapper {
            max-width: 600px;
            margin: 60px auto;
            padding: 0 20px;
        }

        .result-card {
            background: white;
            border-radius: 20px;
            padding: 36px;
            box-shadow: var(--shadow-md);
            text-align: center;
            margin-top: 24px;
        }

        .status-icon {
            font-size: 56px;
            margin-bottom: 16px;
        }

        .status-lulus {
            background: linear-gradient(135deg, var(--success), #6da024);
            color: white;
            border-radius: 20px;
        }

        .status-ditolak {
            background: linear-gradient(135deg, #e05454, #c43d3d);
            color: white;
            border-radius: 20px;
        }

        .status-pending {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            border-radius: 20px;
        }

        .status-verifikasi {
            background: linear-gradient(135deg, var(--secondary), #1a8a82);
            color: white;
            border-radius: 20px;
        }

        .status-waiting_proses {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-radius: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            font-size: 13px;
            gap: 16px;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: rgba(255,255,255,0.8);
        }

        .info-value {
            font-weight: 600;
            text-align: right;
        }

        .catatan-box {
            margin-top: 18px;
            background: rgba(255,255,255,0.18);
            border-radius: 12px;
            padding: 16px;
            text-align: left;
            font-size: 12px;
            line-height: 1.8;
        }

        .catatan-title {
            font-weight: 700;
            margin-bottom: 6px;
        }
    </style>
</head>
<body>

<nav class="publik-navbar">
    <div style="display:flex;align-items:center;gap:10px;">
        <div style="width:36px;height:36px;background:linear-gradient(135deg,var(--primary),var(--secondary));border-radius:9px;display:flex;align-items:center;justify-content:center;color:white;font-weight:800;font-size:13px;">PP</div>
        <div>
            <div style="font-size:13px;font-weight:700;color:var(--primary);">PPDB Online</div>
            <div style="font-size:10px;color:var(--text-light);">MTsN 3 TAPIN 2025/2026</div>
        </div>
    </div>
    <a href="{{ route('beranda') }}" class="btn btn-outline btn-sm">← Beranda</a>
</nav>

<div class="pengumuman-wrapper">

    {{-- Header --}}
    <div style="text-align:center;margin-bottom:32px;">
        <div style="font-size:40px;margin-bottom:12px;">📢</div>
        <h1 style="font-size:22px;font-weight:800;color:var(--primary);margin-bottom:8px;">Cek Status Pengumuman</h1>
        <p style="color:var(--text-light);font-size:13px;">Masukkan NISN untuk mengecek status seleksi Anda</p>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom:16px;">✅ {{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger" style="margin-bottom:16px;">❌ {{ session('error') }}</div>
    @endif

    {{-- Form Cek --}}
    <div style="background:white;border-radius:var(--radius);padding:28px;box-shadow:var(--shadow);margin-bottom:24px;">
        <form method="POST" action="{{ route('pengumuman.cek') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">NISN</label>
                <input
                    type="text"
                    name="nisn"
                    class="form-control"
                    placeholder="Masukkan NISN"
                    value="{{ old('nisn', request('nisn')) }}"
                    required
                >
                @error('nisn')
                    <div style="color:#e05454;font-size:11px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:13px;">
                🔍 Cek Status
            </button>
        </form>
    </div>

    {{-- Hasil --}}
    @isset($data)
        @if($data)

            <div class="result-card status-{{ $data->status }}">

                @if($data->status === 'lulus')
                    <div class="status-icon">🎉</div>
                    <h2 style="font-size:22px;font-weight:800;margin-bottom:6px;">Selamat! Anda Diterima</h2>
                    <p style="opacity:0.9;font-size:13px;margin-bottom:24px;">
                        Anda dinyatakan <strong>LULUS</strong> seleksi penerimaan peserta didik baru.
                    </p>

                @elseif($data->status === 'ditolak')
                    <div class="status-icon">😔</div>
                    <h2 style="font-size:22px;font-weight:800;margin-bottom:6px;">Mohon Maaf</h2>
                    <p style="opacity:0.9;font-size:13px;margin-bottom:24px;">
                        Anda dinyatakan <strong>TIDAK LULUS</strong> seleksi penerimaan peserta didik baru.
                    </p>

                @elseif($data->status === 'verifikasi')
                    <div class="status-icon">🔍</div>
                    <h2 style="font-size:22px;font-weight:800;margin-bottom:6px;">Sudah Diverifikasi</h2>
                    <p style="opacity:0.9;font-size:13px;margin-bottom:24px;">
                        Data Anda sudah diverifikasi oleh panitia dan sedang menunggu hasil akhir seleksi.
                    </p>

                @elseif($data->status === 'pending')
                    <div class="status-icon">⚠️</div>
                    <h2 style="font-size:22px;font-weight:800;margin-bottom:6px;">Perlu Perbaikan Data / Berkas</h2>
                    <p style="opacity:0.9;font-size:13px;margin-bottom:24px;">
                        Terdapat catatan dari admin. Silakan periksa informasi di bawah ini.
                    </p>

                @else
                    <div class="status-icon">⏳</div>
                    <h2 style="font-size:22px;font-weight:800;margin-bottom:6px;">Waiting Proses</h2>
                    <p style="opacity:0.9;font-size:13px;margin-bottom:24px;">
                        Data Anda sedang diperiksa oleh admin. Silakan tunggu proses verifikasi.
                    </p>
                @endif

                {{-- Info Siswa --}}
                <div style="background:rgba(255,255,255,0.15);border-radius:12px;padding:20px;text-align:left;">
                    <div class="info-row">
                        <span class="info-label">Nomor Pendaftaran</span>
                        <span class="info-value">{{ $data->nomor_pendaftaran }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Nama Lengkap</span>
                        <span class="info-value">{{ $data->nama }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Asal Sekolah</span>
                        <span class="info-value">{{ $data->asal_sekolah }}</span>
                    </div>

                    @if($data->status === 'lulus' && $data->kelas)
                        <div class="info-row">
                            <span class="info-label">Kelas</span>
                            <span class="info-value">{{ $data->kelas->nama_kelas }}</span>
                        </div>
                    @endif

                    <div class="info-row" style="border-bottom:none;">
                        <span class="info-label">Status</span>
                        <span class="info-value">
                            @if($data->status === 'lulus')
                                ✅ Lulus
                            @elseif($data->status === 'ditolak')
                                ❌ Tidak Lulus
                            @elseif($data->status === 'verifikasi')
                                🔵 Verifikasi
                            @elseif($data->status === 'pending')
                                ⚠️ Pending
                            @else
                                ⏳ Waiting Proses
                            @endif
                        </span>
                    </div>
                </div>

                {{-- Catatan admin untuk pending --}}
                @if($data->status === 'pending' && !empty($data->catatan))
                    <div class="catatan-box">
                        <div class="catatan-title">📋 Catatan Admin</div>
                        <div>{{ $data->catatan }}</div>
                    </div>
                @endif

                @if(in_array($data->status, ['waiting_proses', 'pending']))
                    <div style="margin-top:16px;">
                        <a href="{{ route('pendaftaran.editPublik', $data->nisn) }}"
                        class="btn"
                        style="width:100%;justify-content:center;padding:13px;background:rgba(255,255,255,0.2);color:white;border:1px solid rgba(255,255,255,0.25);">
                            ✏️ Edit Data / Perbaiki Berkas
                        </a>
                    </div>
                @endif

                {{-- Info waiting proses --}}
                @if($data->status === 'waiting_proses')
                    <div class="catatan-box">
                        <div class="catatan-title">ℹ️ Informasi</div>
                        <div>Data Anda sedang dalam proses pemeriksaan oleh admin. Silakan tunggu hasil verifikasi selanjutnya.</div>
                    </div>
                @endif

                {{-- Predikat untuk yang lulus --}}
                @if($data->status === 'lulus' && $data->predikat)
                    <div style="margin-top:16px;background:rgba(255,255,255,0.15);border-radius:10px;padding:14px;text-align:left;">
                        <div style="font-size:12px;opacity:0.9;">Predikat Hasil Seleksi</div>
                        <div style="font-size:18px;font-weight:800;">
                            {{ $data->predikat }}
                        </div>
                    </div>
                @endif

                {{-- Info daftar ulang --}}
                @if($data->status === 'lulus')
                    <div style="margin-top:20px;background:rgba(255,255,255,0.15);border-radius:10px;padding:14px;font-size:12px;text-align:left;">
                        ⚠️ <strong>Segera lakukan daftar ulang</strong> pada tanggal 11–15 Juli 2025 dengan membawa berkas asli ke sekolah.
                    </div>
                @endif

            </div>

        @else
            <div style="background:white;border-radius:20px;padding:40px;text-align:center;box-shadow:var(--shadow-md);">
                <div style="font-size:48px;margin-bottom:16px;">🔎</div>
                <h2 style="font-size:18px;font-weight:700;color:var(--text);margin-bottom:8px;">Data Tidak Ditemukan</h2>
                <p style="color:var(--text-light);font-size:13px;margin-bottom:20px;">
                    NISN <strong>{{ request('nisn') }}</strong> tidak ditemukan dalam sistem.
                    Pastikan NISN yang Anda masukkan sudah benar.
                </p>
                <div class="alert alert-warning" style="text-align:left;">
                    Nomor pendaftaran diberikan saat Anda berhasil mendaftar, contoh: <strong>PPDB-2025-0001</strong>
                </div>
            </div>
        @endif
    @endisset

    {{-- Keterangan Status --}}
    @if(!isset($data))
        <div style="background:white;border-radius:var(--radius);padding:20px;box-shadow:var(--shadow);">
            <div style="font-size:12px;font-weight:700;color:var(--text);margin-bottom:12px;">ℹ️ Keterangan Status</div>

            <div style="display:flex;flex-direction:column;gap:8px;">
                <div style="display:flex;align-items:center;gap:10px;font-size:12px;">
                    <span class="badge badge-secondary" style="min-width:110px;justify-content:center;">⏳ Waiting Proses</span>
                    <span style="color:var(--text-light);">Data diri dan berkas sedang dicek oleh admin</span>
                </div>

                <div style="display:flex;align-items:center;gap:10px;font-size:12px;">
                    <span class="badge badge-warning" style="min-width:110px;justify-content:center;">⚠️ Pending</span>
                    <span style="color:var(--text-light);">Ada kekurangan data / berkas, cek catatan admin</span>
                </div>

                <div style="display:flex;align-items:center;gap:10px;font-size:12px;">
                    <span class="badge badge-secondary" style="min-width:110px;justify-content:center;">🔵 Verifikasi</span>
                    <span style="color:var(--text-light);">Data sudah diverifikasi oleh panitia</span>
                </div>

                <div style="display:flex;align-items:center;gap:10px;font-size:12px;">
                    <span class="badge badge-success" style="min-width:110px;justify-content:center;">✅ Lulus</span>
                    <span style="color:var(--text-light);">Dinyatakan lulus seleksi, segera daftar ulang</span>
                </div>

                <div style="display:flex;align-items:center;gap:10px;font-size:12px;">
                    <span class="badge badge-danger" style="min-width:110px;justify-content:center;">❌ Tidak Lulus</span>
                    <span style="color:var(--text-light);">Tidak lulus seleksi penerimaan</span>
                </div>
            </div>
        </div>
    @endif

    <div style="text-align:center;margin-top:24px;font-size:12px;color:var(--text-light);">
        Ada pertanyaan? Hubungi kami di 📞 0812-3456-7890 atau ✉️ ppdb@mtsn3tapin.sch.id
    </div>

</div>

</body>
</html>