@extends('layouts.guest')

@section('title', 'Formulir Pendaftaran')

@section('content')
<div style="max-width:720px;margin:50px auto;padding:0 16px;">

    <div style="background:#fff;border-radius:16px;box-shadow:0 10px 25px rgba(0,0,0,0.08);overflow:hidden;">

        {{-- HEADER --}}
        <div style="background:linear-gradient(135deg,#33528A,#33A9A0);padding:24px 28px;color:#fff;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:44px;height:44px;background:rgba(255,255,255,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;">📝</div>
                <div>
                    <h2 style="margin:0;font-size:18px;font-weight:700;">Formulir Pendaftaran PPDB</h2>
                    <p style="margin:2px 0 0;font-size:12px;opacity:.85;">Silakan isi data dengan lengkap dan benar</p>
                </div>
            </div>
        </div>

        {{-- STEP INDICATOR --}}
        <div style="background:#f8f9fc;padding:12px 28px;border-bottom:1px solid #e5e7eb;display:flex;gap:8px;align-items:center;">
            <span style="background:#33528A;color:#fff;border-radius:99px;width:22px;height:22px;display:inline-flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;">1</span>
            <span style="font-size:12px;color:#33528A;font-weight:600;">Data Diri</span>
            <span style="color:#ddd;font-size:12px;">——</span>
            <span style="background:#e5e7eb;color:#999;border-radius:99px;width:22px;height:22px;display:inline-flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;">2</span>
            <span style="font-size:12px;color:#999;">Konfirmasi</span>
        </div>

        <div style="padding:28px;">

            {{-- ERROR --}}
            @if($errors->any())
            <div style="background:#fee2e2;color:#991b1b;padding:12px 16px;border-radius:8px;margin-bottom:20px;font-size:12px;border-left:4px solid #e05454;">
                <strong>⚠️ Terdapat kesalahan:</strong>
                <ul style="margin:6px 0 0;padding-left:18px;">
                    @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('pendaftaran.storePublik') }}">
                @csrf

                {{-- SECTION: Data Diri --}}
                <div style="font-size:11px;font-weight:700;color:#33528A;text-transform:uppercase;letter-spacing:1px;margin-bottom:12px;padding-bottom:6px;border-bottom:2px solid #C4E81D;">
                    👤 Data Diri Siswa
                </div>

                {{-- Nama --}}
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span style="color:#e05454">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" class="form-control" placeholder="Masukkan nama lengkap siswa">
                </div>

                {{-- TTL --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div class="form-group">
                        <label class="form-label">Tempat Lahir <span style="color:#e05454">*</span></label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="form-control" placeholder="Kota tempat lahir">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="form-control">
                    </div>
                </div>

                {{-- Ortu & Gender --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div class="form-group">
                        <label class="form-label">Nama Orang Tua <span style="color:#e05454">*</span></label>
                        <input type="text" name="nama_orang_tua" value="{{ old('nama_orang_tua') }}" class="form-control" placeholder="bin/binti ...">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin <span style="color:#e05454">*</span></label>
                        <select name="jenis_kelamin" class="form-control">
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('jenis_kelamin')=='L'?'selected':'' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin')=='P'?'selected':'' }}>Perempuan</option>
                        </select>
                    </div>
                </div>

                {{-- SECTION: Data Sekolah --}}
                <div style="font-size:11px;font-weight:700;color:#33528A;text-transform:uppercase;letter-spacing:1px;margin:20px 0 12px;padding-bottom:6px;border-bottom:2px solid #C4E81D;">
                    🏫 Data Sekolah & Alamat
                </div>

                {{-- Sekolah --}}
                <div class="form-group">
                    <label class="form-label">Asal Sekolah <span style="color:#e05454">*</span></label>
                    <input type="text" name="asal_sekolah" value="{{ old('asal_sekolah') }}" class="form-control" placeholder="Nama SD/MI asal">
                </div>

                {{-- Alamat --}}
                <div class="form-group">
                    <label class="form-label">Alamat Lengkap <span style="color:#e05454">*</span></label>
                    <textarea name="alamat" rows="3" class="form-control" placeholder="Jl. ... RT/RW ... Desa/Kel ... Kec ...">{{ old('alamat') }}</textarea>
                </div>

                {{-- Jalur & No Telp --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div class="form-group">
                        <label class="form-label">Jalur Pendaftaran <span style="color:#e05454">*</span></label>
                        <select name="jalur" class="form-control">
                            <option value="">-- Pilih Jalur --</option>
                            <option value="reguler"  {{ old('jalur')=='reguler'?'selected':'' }}>📋 Reguler</option>
                            <option value="prestasi" {{ old('jalur')=='prestasi'?'selected':'' }}>🏆 Prestasi</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="no_telp" value="{{ old('no_telp') }}" class="form-control" placeholder="08xxxxxxxxxx">
                    </div>
                </div>

                {{-- BUTTON --}}
                <div style="margin-top:24px;">
                    <button type="submit" class="btn btn-primary" style="width:100%;padding:14px;font-size:14px;font-weight:700;border-radius:10px;">
                        🚀 Kirim Pendaftaran
                    </button>
                    <p style="text-align:center;font-size:11px;color:var(--text-light);margin-top:12px;">
                        Sudah pernah daftar? 
                        <a href="{{ route('pengumuman') }}" style="color:var(--primary);font-weight:600;">Cek pengumuman →</a>
                    </p>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection