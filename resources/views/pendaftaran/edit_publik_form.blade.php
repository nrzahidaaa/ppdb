@extends('layouts.guest')

@section('title', 'Edit Data Diri')

@section('content')
<div style="max-width:720px;margin:50px auto;padding:0 16px;">

    <div style="background:#fff;border-radius:16px;box-shadow:0 10px 25px rgba(0,0,0,0.08);overflow:hidden;">

        {{-- HEADER --}}
        <div style="background:linear-gradient(135deg,#33528A,#33A9A0);padding:24px 28px;color:#fff;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:44px;height:44px;background:rgba(255,255,255,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;">✏️</div>
                <div>
                    <h2 style="margin:0;font-size:18px;font-weight:700;">Edit Data Diri</h2>
                    <p style="margin:2px 0 0;font-size:12px;opacity:.85;">NISN: {{ $data->nisn }}</p>
                </div>
            </div>
        </div>

        <div style="padding:28px;">

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

            <form method="POST" action="{{ route('pendaftaran.updatePublik', $data->nisn) }}">
                @csrf
                @method('PUT')

                {{-- SECTION: Data Diri --}}
                <div style="font-size:11px;font-weight:700;color:#33528A;text-transform:uppercase;letter-spacing:1px;margin-bottom:12px;padding-bottom:6px;border-bottom:2px solid #C4E81D;">
                    👤 Data Diri Siswa
                </div>

                {{-- Nama --}}
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span style="color:#e05454">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $data->nama) }}" class="form-control" placeholder="Masukkan nama lengkap siswa" required>
                </div>

                {{-- TTL --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div class="form-group">
                        <label class="form-label">Tempat Lahir <span style="color:#e05454">*</span></label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $data->tempat_lahir) }}" class="form-control" placeholder="Kota tempat lahir">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $data->tanggal_lahir?->format('Y-m-d')) }}" class="form-control">
                    </div>
                </div>

                {{-- Ortu & Gender --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div class="form-group">
                        <label class="form-label">Nama Orang Tua <span style="color:#e05454">*</span></label>
                        <input type="text" name="nama_orang_tua" value="{{ old('nama_orang_tua', $data->nama_orang_tua) }}" class="form-control" placeholder="bin/binti ...">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin <span style="color:#e05454">*</span></label>
                        <select name="jenis_kelamin" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('jenis_kelamin', $data->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $data->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
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
                    <input type="text" name="asal_sekolah" value="{{ old('asal_sekolah', $data->asal_sekolah) }}" class="form-control" placeholder="Nama SD/MI asal" required>
                </div>

                {{-- Alamat --}}
                <div class="form-group">
                    <label class="form-label">Alamat Lengkap</label>
                    <textarea name="alamat" rows="3" class="form-control" placeholder="Jl. ... RT/RW ... Desa/Kel ... Kec ...">{{ old('alamat', $data->alamat) }}</textarea>
                </div>

                {{-- Jalur & No Telp --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div class="form-group">
                        <label class="form-label">Jalur Pendaftaran <span style="color:#e05454">*</span></label>
                        <select name="jalur" class="form-control" required>
                            <option value="">-- Pilih Jalur --</option>
                            <option value="reguler"  {{ old('jalur', $data->jalur) == 'reguler'  ? 'selected' : '' }}>📋 Reguler</option>
                            <option value="prestasi" {{ old('jalur', $data->jalur) == 'prestasi' ? 'selected' : '' }}>🏆 Prestasi</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="no_telp" value="{{ old('no_telp', $data->no_telp) }}" class="form-control" placeholder="08xxxxxxxxxx">
                    </div>
                </div>

                {{-- BUTTON --}}
                <div style="display:flex;gap:12px;margin-top:24px;">
                    <a href="{{ route('pendaftaran.formEdit') }}" class="btn btn-outline" style="flex:1;text-align:center;padding:13px;">
                        ← Batal
                    </a>
                    <button type="submit" class="btn btn-primary" style="flex:2;padding:13px;font-size:14px;font-weight:700;">
                        💾 Simpan Perubahan
                    </button>
                </div>

                <p style="text-align:center;font-size:11px;color:var(--text-light);margin-top:12px;">
                    ⚠️ Data hanya bisa diedit selama status masih <strong>Pending</strong>
                </p>

            </form>
        </div>
    </div>
</div>
@endsection