@extends('layouts.app')

@section('title', 'Edit Pendaftaran')
@section('page-title', 'Edit Pendaftaran')

@section('content')
<div class="section-header" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
    <div>
        <h2 style="font-size:16px;font-weight:700;margin:0;">Edit Data Pendaftaran</h2>
        <p style="font-size:12px;color:var(--text-light);margin-top:4px;">
            Perbarui data pendaftar
        </p>
    </div>
    <a href="{{ route('pendaftaran.index') }}" class="btn btn-outline">← Kembali</a>
</div>

@if ($errors->any())
    <div style="background:#fee2e2;color:#991b1b;padding:12px 16px;border-radius:8px;margin-bottom:20px;font-size:12px;border-left:4px solid #e05454;">
        <strong>⚠️ Terdapat kesalahan:</strong>
        <ul style="margin:6px 0 0;padding-left:18px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <span class="card-title">✏️ Form Edit Pendaftaran</span>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('pendaftaran.update', $pendaftaran->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- INFORMASI PRIBADI --}}
            <div style="font-size:11px;font-weight:700;color:#33528A;text-transform:uppercase;letter-spacing:1px;margin-bottom:12px;padding-bottom:6px;border-bottom:2px solid #C4E81D;">
                👤 Informasi Pribadi Siswa
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div class="form-group">
                    <label class="form-label">NISN</label>
                    <input type="text" name="nisn" class="form-control"
                        value="{{ old('nisn', $pendaftaran->nisn) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">NIK</label>
                    <input type="text" name="nik" class="form-control"
                        value="{{ old('nik', $pendaftaran->nik) }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Nama Siswa <span style="color:red">*</span></label>
                <input type="text" name="nama" class="form-control"
                    value="{{ old('nama', $pendaftaran->nama) }}" required>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div class="form-group">
                    <label class="form-label">Tempat Lahir <span style="color:red">*</span></label>
                    <input type="text" name="tempat_lahir" class="form-control"
                        value="{{ old('tempat_lahir', $pendaftaran->tempat_lahir) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control"
                        value="{{ old('tanggal_lahir', $pendaftaran->tanggal_lahir) }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Jenis Kelamin <span style="color:red">*</span></label>
                <div style="display:flex;gap:20px;margin-top:6px;">
                    <label>
                        <input type="radio" name="jenis_kelamin" value="L"
                            {{ old('jenis_kelamin', $pendaftaran->jenis_kelamin) == 'L' ? 'checked' : '' }}>
                        Laki-laki
                    </label>
                    <label>
                        <input type="radio" name="jenis_kelamin" value="P"
                            {{ old('jenis_kelamin', $pendaftaran->jenis_kelamin) == 'P' ? 'checked' : '' }}>
                        Perempuan
                    </label>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div class="form-group">
                    <label class="form-label">Hobi</label>
                    <input type="text" name="hobi" class="form-control"
                        value="{{ old('hobi', $pendaftaran->hobi) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Cita-cita</label>
                    <input type="text" name="cita_cita" class="form-control"
                        value="{{ old('cita_cita', $pendaftaran->cita_cita) }}">
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;">
                <div class="form-group">
                    <label class="form-label">Anak Ke-</label>
                    <input type="number" name="anak_ke" class="form-control"
                        value="{{ old('anak_ke', $pendaftaran->anak_ke) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Jumlah Saudara</label>
                    <input type="number" name="jumlah_saudara" class="form-control"
                        value="{{ old('jumlah_saudara', $pendaftaran->jumlah_saudara) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="no_telp" class="form-control"
                        value="{{ old('no_telp', $pendaftaran->no_telp) }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Status Tinggal</label>
                <input type="text" name="status_tinggal" class="form-control"
                    value="{{ old('status_tinggal', $pendaftaran->status_tinggal) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Alamat <span style="color:red">*</span></label>
                <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat', $pendaftaran->alamat) }}</textarea>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:12px;">
                <div class="form-group">
                    <label class="form-label">Desa/Kelurahan</label>
                    <input type="text" name="desa_kelurahan" class="form-control"
                        value="{{ old('desa_kelurahan', $pendaftaran->desa_kelurahan) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Kecamatan</label>
                    <input type="text" name="kecamatan" class="form-control"
                        value="{{ old('kecamatan', $pendaftaran->kecamatan) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Kabupaten/Kota</label>
                    <input type="text" name="kabupaten_kota" class="form-control"
                        value="{{ old('kabupaten_kota', $pendaftaran->kabupaten_kota) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Kode Pos</label>
                    <input type="text" name="kode_pos" class="form-control"
                        value="{{ old('kode_pos', $pendaftaran->kode_pos) }}">
                </div>
            </div>

            {{-- ASAL SEKOLAH --}}
            <div style="font-size:11px;font-weight:700;color:#33528A;text-transform:uppercase;letter-spacing:1px;margin:20px 0 12px;padding-bottom:6px;border-bottom:2px solid #C4E81D;">
                🏫 Asal Sekolah
            </div>

            <div class="form-group">
                <label class="form-label">Asal Sekolah <span style="color:red">*</span></label>
                <input type="text" name="asal_sekolah" class="form-control"
                    value="{{ old('asal_sekolah', $pendaftaran->asal_sekolah) }}" required>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;">
                <div class="form-group">
                    <label class="form-label">Jenis Sekolah</label>
                    <input type="text" name="jenis_sekolah" class="form-control"
                        value="{{ old('jenis_sekolah', $pendaftaran->jenis_sekolah) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Status Sekolah</label>
                    <input type="text" name="status_sekolah" class="form-control"
                        value="{{ old('status_sekolah', $pendaftaran->status_sekolah) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">NPSN Sekolah</label>
                    <input type="text" name="npsn_sekolah" class="form-control"
                        value="{{ old('npsn_sekolah', $pendaftaran->npsn_sekolah) }}">
                </div>
            </div>

            {{-- ORANG TUA --}}
            <div style="font-size:11px;font-weight:700;color:#33528A;text-transform:uppercase;letter-spacing:1px;margin:20px 0 12px;padding-bottom:6px;border-bottom:2px solid #C4E81D;">
                👨‍👩‍👧 Informasi Orang Tua
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;">
                <div class="form-group">
                    <label class="form-label">No. KK</label>
                    <input type="text" name="no_kk" class="form-control"
                        value="{{ old('no_kk', $pendaftaran->no_kk) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Kepala Keluarga</label>
                    <input type="text" name="nama_kepala_keluarga" class="form-control"
                        value="{{ old('nama_kepala_keluarga', $pendaftaran->nama_kepala_keluarga) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Status Kepemilikan Rumah</label>
                    <input type="text" name="status_kepemilikan_rumah" class="form-control"
                        value="{{ old('status_kepemilikan_rumah', $pendaftaran->status_kepemilikan_rumah) }}">
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div class="form-group">
                    <label class="form-label">Nama Ayah</label>
                    <input type="text" name="nama_ayah" class="form-control"
                        value="{{ old('nama_ayah', $pendaftaran->nama_ayah) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">NIK Ayah</label>
                    <input type="text" name="nik_ayah" class="form-control"
                        value="{{ old('nik_ayah', $pendaftaran->nik_ayah) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Status Ayah</label>
                    <input type="text" name="status_ayah" class="form-control"
                        value="{{ old('status_ayah', $pendaftaran->status_ayah) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Pendidikan Ayah</label>
                    <input type="text" name="pendidikan_ayah" class="form-control"
                        value="{{ old('pendidikan_ayah', $pendaftaran->pendidikan_ayah) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Pekerjaan Ayah</label>
                    <input type="text" name="pekerjaan_ayah" class="form-control"
                        value="{{ old('pekerjaan_ayah', $pendaftaran->pekerjaan_ayah) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Penghasilan Ayah</label>
                    <input type="text" name="penghasilan_ayah" class="form-control"
                        value="{{ old('penghasilan_ayah', $pendaftaran->penghasilan_ayah) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">No. HP Ayah</label>
                    <input type="text" name="no_hp_ayah" class="form-control"
                        value="{{ old('no_hp_ayah', $pendaftaran->no_hp_ayah) }}">
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:10px;">
                <div class="form-group">
                    <label class="form-label">Nama Ibu</label>
                    <input type="text" name="nama_ibu" class="form-control"
                        value="{{ old('nama_ibu', $pendaftaran->nama_ibu) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">NIK Ibu</label>
                    <input type="text" name="nik_ibu" class="form-control"
                        value="{{ old('nik_ibu', $pendaftaran->nik_ibu) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Status Ibu</label>
                    <input type="text" name="status_ibu" class="form-control"
                        value="{{ old('status_ibu', $pendaftaran->status_ibu) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Pendidikan Ibu</label>
                    <input type="text" name="pendidikan_ibu" class="form-control"
                        value="{{ old('pendidikan_ibu', $pendaftaran->pendidikan_ibu) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Pekerjaan Ibu</label>
                    <input type="text" name="pekerjaan_ibu" class="form-control"
                        value="{{ old('pekerjaan_ibu', $pendaftaran->pekerjaan_ibu) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Penghasilan Ibu</label>
                    <input type="text" name="penghasilan_ibu" class="form-control"
                        value="{{ old('penghasilan_ibu', $pendaftaran->penghasilan_ibu) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">No. HP Ibu</label>
                    <input type="text" name="no_hp_ibu" class="form-control"
                        value="{{ old('no_hp_ibu', $pendaftaran->no_hp_ibu) }}">
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:10px;">
                <div class="form-group">
                    <label class="form-label">Nama Wali</label>
                    <input type="text" name="nama_wali" class="form-control"
                        value="{{ old('nama_wali', $pendaftaran->nama_wali) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">NIK Wali</label>
                    <input type="text" name="nik_wali" class="form-control"
                        value="{{ old('nik_wali', $pendaftaran->nik_wali) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Status Wali</label>
                    <input type="text" name="status_wali" class="form-control"
                        value="{{ old('status_wali', $pendaftaran->status_wali) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Pendidikan Wali</label>
                    <input type="text" name="pendidikan_wali" class="form-control"
                        value="{{ old('pendidikan_wali', $pendaftaran->pendidikan_wali) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Pekerjaan Wali</label>
                    <input type="text" name="pekerjaan_wali" class="form-control"
                        value="{{ old('pekerjaan_wali', $pendaftaran->pekerjaan_wali) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Penghasilan Wali</label>
                    <input type="text" name="penghasilan_wali" class="form-control"
                        value="{{ old('penghasilan_wali', $pendaftaran->penghasilan_wali) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">No. HP Wali</label>
                    <input type="text" name="no_hp_wali" class="form-control"
                        value="{{ old('no_hp_wali', $pendaftaran->no_hp_wali) }}">
                </div>
            </div>

            {{-- JALUR --}}
            <div style="font-size:11px;font-weight:700;color:#33528A;text-transform:uppercase;letter-spacing:1px;margin:20px 0 12px;padding-bottom:6px;border-bottom:2px solid #C4E81D;">
                📋 Jalur Pendaftaran
            </div>

            <div class="form-group">
                <label class="form-label">Jalur <span style="color:red">*</span></label>
                <div style="display:flex;gap:20px;margin-top:6px;">
                    <label>
                        <input type="radio" name="jalur" value="reguler"
                            {{ old('jalur', $pendaftaran->jalur) == 'reguler' ? 'checked' : '' }}>
                        Reguler
                    </label>
                    <label>
                        <input type="radio" name="jalur" value="prestasi"
                            {{ old('jalur', $pendaftaran->jalur) == 'prestasi' ? 'checked' : '' }}>
                        Prestasi
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Nama Orang Tua / Bin-Binti <span style="color:red">*</span></label>
                <input type="text" name="nama_orang_tua" class="form-control"
                    value="{{ old('nama_orang_tua', $pendaftaran->nama_orang_tua) }}" required>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;">
                <div class="form-group">
                    <label class="form-label">No. KKS</label>
                    <input type="text" name="no_kks" class="form-control"
                        value="{{ old('no_kks', $pendaftaran->no_kks) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">No. PKH</label>
                    <input type="text" name="no_pkh" class="form-control"
                        value="{{ old('no_pkh', $pendaftaran->no_pkh) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">No. KIP</label>
                    <input type="text" name="no_kip" class="form-control"
                        value="{{ old('no_kip', $pendaftaran->no_kip) }}">
                </div>
            </div>

            {{-- UPLOAD BERKAS --}}
            <div style="font-size:11px;font-weight:700;color:#33528A;text-transform:uppercase;letter-spacing:1px;margin:20px 0 12px;padding-bottom:6px;border-bottom:2px solid #C4E81D;">
                📁 Upload Berkas
            </div>

            <div style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:12px;color:#0369a1;">
                Kalau tidak upload file baru, maka file lama tetap dipakai.
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div class="form-group">
                    <label class="form-label">File NISN</label>
                    <input type="file" name="nisn_file" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                    @if($pendaftaran->nisn_file)
                        <small style="display:block;margin-top:6px;">
                            File saat ini:
                            <a href="{{ asset('storage/' . $pendaftaran->nisn_file) }}" target="_blank">Lihat file</a>
                        </small>
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label">Kartu Keluarga</label>
                    <input type="file" name="kartu_keluarga" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                    @if($pendaftaran->kartu_keluarga)
                        <small style="display:block;margin-top:6px;">
                            File saat ini:
                            <a href="{{ asset('storage/' . $pendaftaran->kartu_keluarga) }}" target="_blank">Lihat file</a>
                        </small>
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label">Akta Kelahiran</label>
                    <input type="file" name="akta_kelahiran" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                    @if($pendaftaran->akta_kelahiran)
                        <small style="display:block;margin-top:6px;">
                            File saat ini:
                            <a href="{{ asset('storage/' . $pendaftaran->akta_kelahiran) }}" target="_blank">Lihat file</a>
                        </small>
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label">Foto</label>
                    <input type="file" name="foto" class="form-control" accept=".jpg,.jpeg,.png">
                    @if($pendaftaran->foto)
                        <small style="display:block;margin-top:6px;">
                            File saat ini:
                            <a href="{{ asset('storage/' . $pendaftaran->foto) }}" target="_blank">Lihat file</a>
                        </small>
                    @endif
                </div>
            </div>

            <div class="form-group" style="margin-top:12px;">
                <label class="form-label">Ijazah / SKL</label>
                <input type="file" name="ijazah" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                @if($pendaftaran->ijazah)
                    <small style="display:block;margin-top:6px;">
                        File saat ini:
                        <a href="{{ asset('storage/' . $pendaftaran->ijazah) }}" target="_blank">Lihat file</a>
                    </small>
                @endif
            </div>

            <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:24px;">
                <a href="{{ route('pendaftaran.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">💾 Update Pendaftaran</button>
            </div>
        </form>
    </div>
</div>
@endsection