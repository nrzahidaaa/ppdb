@extends('layouts.app')

@section('title', 'Tambah Pendaftaran')
@section('page-title', 'Tambah Pendaftaran')

@section('content')

<div class="section-header">
    <div>
        <h2 style="font-size:16px;font-weight:700;">Tambah Pendaftar Baru</h2>
        <p style="font-size:12px;color:var(--text-light);">Isi formulir data pendaftaran siswa baru</p>
    </div>
    <a href="{{ route('pendaftaran.index') }}" class="btn btn-outline">← Kembali</a>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title">📝 Formulir Pendaftaran</span>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('pendaftaran.store') }}">
            @csrf

            {{-- Data Pribadi --}}
            <div style="margin-bottom:24px;">
                <div style="font-size:13px;font-weight:700;color:var(--primary);margin-bottom:16px;padding-bottom:8px;border-bottom:2px solid var(--highlight);">
                    👤 Data Pribadi
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap <span style="color:red;">*</span></label>
                        <input type="text" name="nama" class="form-control" placeholder="Nama lengkap siswa" value="{{ old('nama') }}" required>
                        @error('nama')<div style="color:#e05454;font-size:11px;margin-top:4px;">{{ $message }}</div>@enderror
                    </div>
                    <!-- <div class="form-group">
                        <label class="form-label">NISN <span style="color:red;">*</span></label>
                        <input type="text" name="nisn" class="form-control" placeholder="10 digit NISN" value="{{ old('nisn') }}" required>
                        @error('nisn')<div style="color:#e05454;font-size:11px;margin-top:4px;">{{ $message }}</div>@enderror
                    </div> -->
                    <div class="form-group">
                        <label class="form-label">Tempat Lahir <span style="color:red;">*</span></label>
                        <input type="text" name="tempat_lahir" class="form-control" placeholder="Kota tempat lahir" value="{{ old('tempat_lahir') }}" required>
                        @error('tempat_lahir')<div style="color:#e05454;font-size:11px;margin-top:4px;">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Lahir <span style="color:red;">*</span></label>
                        <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}" required>
                        @error('tanggal_lahir')<div style="color:#e05454;font-size:11px;margin-top:4px;">{{ $message }}</div>@enderror
                    </div>
                    <!-- <div class="form-group">
                        <label class="form-label">Jenis Kelamin <span style="color:red;">*</span></label>
                        <select name="jenis_kelamin" class="form-control form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('jenis_kelamin')=='L'?'selected':'' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin')=='P'?'selected':'' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')<div style="color:#e05454;font-size:11px;margin-top:4px;">{{ $message }}</div>@enderror
                    </div> -->
                    <div class="form-group">
                        <label class="form-label">Alamat <span style="color:red;">*</span></label>
                        <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat lengkap" required>{{ old('alamat') }}</textarea>
                        @error('alamat')<div style="color:#e05454;font-size:11px;margin-top:4px;">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- Data Sekolah --}}
            <div style="margin-bottom:24px;">
                <div style="font-size:13px;font-weight:700;color:var(--primary);margin-bottom:16px;padding-bottom:8px;border-bottom:2px solid var(--highlight);">
                    🏫 Data Asal Sekolah & Jurusan
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Asal Sekolah <span style="color:red;">*</span></label>
                        <input type="text" name="asal_sekolah" class="form-control" placeholder="Nama SMP asal" value="{{ old('asal_sekolah') }}" required>
                        @error('asal_sekolah')<div style="color:#e05454;font-size:11px;margin-top:4px;">{{ $message }}</div>@enderror
                    </div>
                    <!-- <div class="form-group">
                        <label class="form-label">Pilihan Jurusan <span style="color:red;">*</span></label>
                        <select name="pilihan_jurusan" class="form-control form-select" required>
                            <option value="">-- Pilih Jurusan --</option>
                            <option value="MIPA"   {{ old('pilihan_jurusan')=='MIPA'  ?'selected':'' }}>MIPA</option>
                            <option value="IPS"    {{ old('pilihan_jurusan')=='IPS'   ?'selected':'' }}>IPS</option>
                            <option value="Bahasa" {{ old('pilihan_jurusan')=='Bahasa'?'selected':'' }}>Bahasa</option>
                        </select>
                        @error('pilihan_jurusan')<div style="color:#e05454;font-size:11px;margin-top:4px;">{{ $message }}</div>@enderror
                    </div> -->
                    <div class="form-group">
                        <label class="form-label">Nilai Rata-rata Rapor <span style="color:red;">*</span></label>
                        <input type="number" name="nilai_rata_rata" class="form-control" placeholder="contoh: 85.5" step="0.01" min="0" max="100" value="{{ old('nilai_rata_rata') }}" required>
                        @error('nilai_rata_rata')<div style="color:#e05454;font-size:11px;margin-top:4px;">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- Data Orang Tua --}}
            <div style="margin-bottom:24px;">
                <div style="font-size:13px;font-weight:700;color:var(--primary);margin-bottom:16px;padding-bottom:8px;border-bottom:2px solid var(--highlight);">
                    👨‍👩‍👧 Data Orang Tua / Wali
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Nama Orang Tua / Wali <span style="color:red;">*</span></label>
                        <input type="text" name="nama_orang_tua" class="form-control" placeholder="Nama ayah/ibu/wali" value="{{ old('nama_orang_tua') }}" required>
                        @error('nama_orang_tua')<div style="color:#e05454;font-size:11px;margin-top:4px;">{{ $message }}</div>@enderror
                    </div>
                    <!-- <div class="form-group">
                        <label class="form-label">Nomor Telepon <span style="color:red;">*</span></label>
                        <input type="text" name="no_telp" class="form-control" placeholder="Nomor HP aktif" value="{{ old('no_telp') }}" required>
                        @error('no_telp')<div style="color:#e05454;font-size:11px;margin-top:4px;">{{ $message }}</div>@enderror
                    </div> -->
                </div>
            </div>

            {{-- Tombol --}}
            <div style="display:flex;gap:12px;justify-content:flex-end;padding-top:16px;border-top:1px solid var(--border);">
                <a href="{{ route('pendaftaran.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">💾 Simpan Pendaftaran</button>
            </div>

        </form>
    </div>
</div>

@endsection