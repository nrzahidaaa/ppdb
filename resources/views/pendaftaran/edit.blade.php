@extends('layouts.app')

@section('title', 'Edit Pendaftaran')
@section('page-title', 'Edit Pendaftaran')

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
        <h2 style="font-size:16px;font-weight:700;">Edit Data Pendaftar</h2>
        <p style="font-size:12px;color:var(--text-light);">Ubah data pendaftaran siswa</p>
    </div>
    <a href="{{ route('pendaftaran.index') }}" class="btn btn-outline">← Kembali</a>
</div>

@if($errors->any())
<div class="alert alert-danger">
    ❌ Terdapat kesalahan. Periksa kembali isian Anda.
    <ul style="margin-top:6px;padding-left:18px;">
        @foreach($errors->all() as $error)
            <li style="font-size:12px;">{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('pendaftaran.update', $pendaftaran->id) }}">
@csrf
@method('PUT')

{{-- Data Pribadi --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-header">
        <span class="card-title">👤 Data Pribadi</span>
        <span class="badge badge-primary">{{ $pendaftaran->nomor_pendaftaran }}</span>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

            <div class="form-group">
                <label class="form-label">Nama Lengkap <span style="color:red;">*</span></label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama', $pendaftaran->nama) }}" required>
                @error('nama')<div style="color:#e05454;font-size:11px;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <!-- <div class="form-group">
                <label class="form-label">NISN <span style="color:red;">*</span></label>
                <input type="text" name="nisn" class="form-control" value="{{ old('nisn', $pendaftaran->nisn) }}" required>
                @error('nisn')<div style="color:#e05454;font-size:11px;margin-top:4px;">{{ $message }}</div>@enderror
            </div> -->

            <div class="form-group">
                <label class="form-label">Tempat Lahir <span style="color:red;">*</span></label>
                <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $pendaftaran->tempat_lahir) }}" required>
                @error('tempat_lahir')<div style="color:#e05454;font-size:11px;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Tanggal Lahir <span style="color:red;">*</span></label>
                <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $pendaftaran->tanggal_lahir?->format('Y-m-d')) }}" required>
                @error('tanggal_lahir')<div style="color:#e05454;font-size:11px;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Jenis Kelamin <span style="color:red;">*</span></label>
                <select name="jenis_kelamin" class="form-control form-select" required>
                    <option value="L" {{ old('jenis_kelamin', $pendaftaran->jenis_kelamin)=='L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin', $pendaftaran->jenis_kelamin)=='P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jenis_kelamin')<div style="color:#e05454;font-size:11px;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Alamat <span style="color:red;">*</span></label>
                <textarea name="alamat" class="form-control" rows="2" required>{{ old('alamat', $pendaftaran->alamat) }}</textarea>
                @error('alamat')<div style="color:#e05454;font-size:11px;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

        </div>
    </div>
</div>

{{-- Data Sekolah --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-header">
        <span class="card-title">🏫 Data Asal Sekolah dan Jurusan</span>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

            <div class="form-group">
                <label class="form-label">Asal Sekolah <span style="color:red;">*</span></label>
                <input type="text" name="asal_sekolah" class="form-control" value="{{ old('asal_sekolah', $pendaftaran->asal_sekolah) }}" required>
                @error('asal_sekolah')<div style="color:#e05454;font-size:11px;margin-top:4px;">{{ $message }}</div>@enderror
            </div>
<!-- 
            <div class="form-group">
                <label class="form-label">Pilihan Jurusan <span style="color:red;">*</span></label>
                <select name="pilihan_jurusan" class="form-control form-select" required>
                    <option value="MIPA"   {{ old('pilihan_jurusan', $pendaftaran->pilihan_jurusan)=='MIPA'   ? 'selected' : '' }}>MIPA</option>
                    <option value="IPS"    {{ old('pilihan_jurusan', $pendaftaran->pilihan_jurusan)=='IPS'    ? 'selected' : '' }}>IPS</option>
                    <option value="Bahasa" {{ old('pilihan_jurusan', $pendaftaran->pilihan_jurusan)=='Bahasa' ? 'selected' : '' }}>Bahasa</option>
                </select>
                @error('pilihan_jurusan')<div style="color:#e05454;font-size:11px;margin-top:4px;">{{ $message }}</div>@enderror
            </div> -->

            <!-- <div class="form-group">
                <label class="form-label">Nilai Rata-rata Rapor <span style="color:red;">*</span></label>
                <input type="number" name="nilai_rata_rata" class="form-control" step="0.01" min="0" max="100" value="{{ old('nilai_rata_rata', $pendaftaran->nilai_rata_rata) }}" required>
                @error('nilai_rata_rata')<div style="color:#e05454;font-size:11px;margin-top:4px;">{{ $message }}</div>@enderror
            </div> -->

            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control form-select">
                    <option value="pending"    {{ old('status', $pendaftaran->status)=='pending'    ? 'selected' : '' }}>⏳ Pending</option>
                    <option value="verifikasi" {{ old('status', $pendaftaran->status)=='verifikasi' ? 'selected' : '' }}>🔵 Verifikasi</option>
                    <option value="lulus"      {{ old('status', $pendaftaran->status)=='lulus'      ? 'selected' : '' }}>✅ Lulus</option>
                    <option value="ditolak"    {{ old('status', $pendaftaran->status)=='ditolak'    ? 'selected' : '' }}>❌ Ditolak</option>
                </select>
            </div>
            <select name="jalur" class="form-control" required>
            <option value="">-- Pilih --</option>
            <option value="reguler" {{ old('jalur', $pendaftaran->jalur) == 'reguler' ? 'selected' : '' }}>Reguler</option>
            <option value="prestasi" {{ old('jalur', $pendaftaran->jalur) == 'prestasi' ? 'selected' : '' }}>Prestasi</option>
            </select>

        </div>
    </div>
</div>

{{-- Data Orang Tua --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-header">
        <span class="card-title">👨‍👩‍👧 Data Orang Tua / Wali</span>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

            <div class="form-group">
                <label class="form-label">Nama Orang Tua / Wali <span style="color:red;">*</span></label>
                <input type="text" name="nama_orang_tua" class="form-control" value="{{ old('nama_orang_tua', $pendaftaran->nama_orang_tua) }}" required>
                @error('nama_orang_tua')<div style="color:#e05454;font-size:11px;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <!-- <div class="form-group">
                <label class="form-label">Nomor Telepon <span style="color:red;">*</span></label>
                <input type="text" name="no_telp" class="form-control" value="{{ old('no_telp', $pendaftaran->no_telp) }}" required>
                @error('no_telp')<div style="color:#e05454;font-size:11px;margin-top:4px;">{{ $message }}</div>@enderror
            </div> -->

            <div class="form-group" style="grid-column:span 2;">
                <label class="form-label">Catatan</label>
                <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan tambahan (opsional)">{{ old('catatan', $pendaftaran->catatan) }}</textarea>
            </div>

        </div>
    </div>
</div>

{{-- Tombol --}}
<div style="display:flex;gap:12px;justify-content:flex-end;">
    <a href="{{ route('pendaftaran.index') }}" class="btn btn-outline">Batal</a>
    <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
</div>

</form>

@endsection
