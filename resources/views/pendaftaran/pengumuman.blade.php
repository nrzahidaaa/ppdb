<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Pengumuman — PPDB Online</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/ppdb.css') }}">
    <style>
        body { background: var(--bg); min-height: 100vh; }

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
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-radius: 20px;
        }

        .status-verifikasi {
            background: linear-gradient(135deg, var(--secondary), #1a8a82);
            color: white;
            border-radius: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid var(--border);
            font-size: 13px;
        }

        .info-row:last-child { border-bottom: none; }
        .info-label { color: var(--text-light); }
        .info-value { font-weight: 600; }
    </style>
</head>
<body>

{{-- Navbar --}}
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
        <p style="color:var(--text-light);font-size:13px;">Masukkan nomor pendaftaran untuk mengecek status seleksi Anda</p>
    </div>

    {{-- Form Cek --}}
    <div style="background:white;border-radius:var(--radius);padding:28px;box-shadow:var(--shadow);margin-bottom:24px;">
        <form method="POST" action="{{ route('pengumuman.cek') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Nomor Pendaftaran</label>
                <input
                    type="text"
                    name="nomor_pendaftaran"
                    class="form-control"
                    placeholder="contoh: PPDB-2025-0001"
                    value="{{ old('nomor_pendaftaran', request('nomor_pendaftaran')) }}"
                    required
                    style="text-transform:uppercase;"
                >
                @error('nomor_pendaftaran')
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
            {{-- Data ditemukan --}}
            <div class="result-card {{ 'status-' . $data->status }}">

                @if($data->status === 'lulus')
                    <div class="status-icon">🎉</div>
                    <h2 style="font-size:22px;font-weight:800;margin-bottom:6px;">Selamat! Anda Diterima</h2>
                    <p style="opacity:0.9;font-size:13px;margin-bottom:24px;">Anda dinyatakan <strong>LULUS</strong> seleksi penerimaan peserta didik baru.</p>
                @elseif($data->status === 'ditolak')
                    <div class="status-icon">😔</div>
                    <h2 style="font-size:22px;font-weight:800;margin-bottom:6px;">Mohon Maaf</h2>
                    <p style="opacity:0.9;font-size:13px;margin-bottom:24px;">Anda dinyatakan <strong>TIDAK LULUS</strong> seleksi penerimaan peserta didik baru.</p>
                @elseif($data->status === 'verifikasi')
                    <div class="status-icon">🔍</div>
                    <h2 style="font-size:22px;font-weight:800;margin-bottom:6px;">Sedang Diverifikasi</h2>
                    <p style="opacity:0.9;font-size:13px;margin-bottom:24px;">Berkas Anda sedang dalam proses verifikasi oleh panitia. Harap menunggu.</p>
                @else
                    <div class="status-icon">⏳</div>
                    <h2 style="font-size:22px;font-weight:800;margin-bottom:6px;">Menunggu Proses</h2>
                    <p style="opacity:0.9;font-size:13px;margin-bottom:24px;">Pendaftaran Anda sudah diterima dan sedang menunggu proses seleksi.</p>
                @endif

                {{-- Info Siswa --}}
                <div style="background:rgba(255,255,255,0.15);border-radius:12px;padding:20px;text-align:left;">
                    <div class="info-row" style="border-color:rgba(255,255,255,0.2);">
                        <span class="info-label" style="color:rgba(255,255,255,0.8);">Nomor Pendaftaran</span>
                        <span class="info-value">{{ $data->nomor_pendaftaran }}</span>
                    </div>
                    <div class="info-row" style="border-color:rgba(255,255,255,0.2);">
                        <span class="info-label" style="color:rgba(255,255,255,0.8);">Nama Lengkap</span>
                        <span class="info-value">{{ $data->nama }}</span>
                    </div>
                    <div class="info-row" style="border-color:rgba(255,255,255,0.2);">
                        <span class="info-label" style="color:rgba(255,255,255,0.8);">Asal Sekolah</span>
                        <span class="info-value">{{ $data->asal_sekolah }}</span>
                    </div>
                    <div class="info-row" style="border-color:rgba(255,255,255,0.2);">
                        <span class="info-label" style="color:rgba(255,255,255,0.8);">Pilihan Jurusan</span>
                        <span class="info-value">{{ $data->pilihan_jurusan }}</span>
                    </div>
                    @if($data->status === 'lulus' && $data->kelas)
                    <div class="info-row" style="border-color:rgba(255,255,255,0.2);">
                        <span class="info-label" style="color:rgba(255,255,255,0.8);">Kelas</span>
                        <span class="info-value">{{ $data->kelas->nama_kelas }}</span>
                    </div>
                    @endif
                    <div class="info-row" style="border-bottom:none;">
                        <span class="info-label" style="color:rgba(255,255,255,0.8);">Status</span>
                        <span class="info-value" style="text-transform:capitalize;">
                            @if($data->status === 'lulus')     ✅ Lulus
                            @elseif($data->status === 'ditolak') ❌ Tidak Lulus
                            @elseif($data->status === 'verifikasi') 🔵 Verifikasi
                            @else ⏳ Pending
                            @endif
                        </span>
                    </div>
                </div>

                @if($data->status === 'lulus')
                <div style="margin-top:20px;background:rgba(255,255,255,0.15);border-radius:10px;padding:14px;font-size:12px;text-align:left;">
                    ⚠️ <strong>Segera lakukan daftar ulang</strong> pada tanggal 11–15 Juli 2025 dengan membawa berkas asli ke sekolah.
                </div>
                @endif

            </div>

        @else
            {{-- Data tidak ditemukan --}}
            <div style="background:white;border-radius:20px;padding:40px;text-align:center;box-shadow:var(--shadow-md);">
                <div style="font-size:48px;margin-bottom:16px;">🔎</div>
                <h2 style="font-size:18px;font-weight:700;color:var(--text);margin-bottom:8px;">Data Tidak Ditemukan</h2>
                <p style="color:var(--text-light);font-size:13px;margin-bottom:20px;">
                    Nomor pendaftaran <strong>{{ request('nomor_pendaftaran') }}</strong> tidak ditemukan dalam sistem.
                    Pastikan nomor yang Anda masukkan sudah benar.
                </p>
                <div class="alert alert-warning" style="text-align:left;">
                    💡 Nomor pendaftaran diberikan saat Anda berhasil mendaftar, contoh: <strong>PPDB-2025-0001</strong>
                </div>
            </div>
        @endif
    @endisset

    {{-- Info --}}
    @if(!isset($data))
    <div style="background:white;border-radius:var(--radius);padding:20px;box-shadow:var(--shadow);">
        <div style="font-size:12px;font-weight:700;color:var(--text);margin-bottom:12px;">ℹ️ Keterangan Status</div>
        <div style="display:flex;flex-direction:column;gap:8px;">
            <div style="display:flex;align-items:center;gap:10px;font-size:12px;">
                <span class="badge badge-warning" style="min-width:80px;justify-content:center;">⏳ Pending</span>
                <span style="color:var(--text-light);">Pendaftaran diterima, menunggu proses seleksi</span>
            </div>
            <div style="display:flex;align-items:center;gap:10px;font-size:12px;">
                <span class="badge badge-secondary" style="min-width:80px;justify-content:center;">🔵 Verifikasi</span>
                <span style="color:var(--text-light);">Berkas sedang diverifikasi oleh panitia</span>
            </div>
            <div style="display:flex;align-items:center;gap:10px;font-size:12px;">
                <span class="badge badge-success" style="min-width:80px;justify-content:center;">✅ Lulus</span>
                <span style="color:var(--text-light);">Dinyatakan lulus seleksi, segera daftar ulang</span>
            </div>
            <div style="display:flex;align-items:center;gap:10px;font-size:12px;">
                <span class="badge badge-danger" style="min-width:80px;justify-content:center;">❌ Ditolak</span>
                <span style="color:var(--text-light);">Tidak lulus seleksi penerimaan</span>
            </div>
        </div>
    </div>
    @endif

    <div style="text-align:center;margin-top:24px;font-size:12px;color:var(--text-light);">
        Ada pertanyaan? Hubungi kami di 📞 0812-3456-7890 atau ✉️ ppdb@smtsn3tapin.sch.id
    </div>

</div>

</body>
</html>
