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
    ❌ Terdapat kesalahan.
    <ul style="margin-top:6px;padding-left:18px;">
        @foreach($errors->all() as $error)
            <li style="font-size:12px;">{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('pendaftaran.update', $pendaftaran->id) }}" enctype="multipart/form-data">
@csrf
@method('PUT')

{{-- ===== DATA PRIBADI ===== --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-header">
        <span class="card-title">👤 Data Pribadi Siswa</span>
        <span class="badge badge-primary">{{ $pendaftaran->nomor_pendaftaran }}</span>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div class="form-group">
                <label class="form-label">NISN</label>
                <input type="text" name="nisn" class="form-control" value="{{ old('nisn', $pendaftaran->nisn) }}">
            </div>
            <div class="form-group">
                <label class="form-label">NIK Siswa</label>
                <input type="text" name="nik" class="form-control" value="{{ old('nik', $pendaftaran->nik) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Nama Lengkap <span style="color:red;">*</span></label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama', $pendaftaran->nama) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Jenis Kelamin <span style="color:red;">*</span></label>
                <select name="jenis_kelamin" class="form-control form-select" required>
                    <option value="L" {{ old('jenis_kelamin', $pendaftaran->jenis_kelamin)=='L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin', $pendaftaran->jenis_kelamin)=='P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Tempat Lahir</label>
                <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $pendaftaran->tempat_lahir) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $pendaftaran->tanggal_lahir?->format('Y-m-d')) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Hobi</label>
                <select name="hobi" class="form-control">
                    <option value="">-- Pilih --</option>
                    @foreach(['Olahraga','Kesenian','Membaca','Menulis','Jalan-jalan','Lainnya'] as $h)
                    <option value="{{ $h }}" {{ old('hobi', $pendaftaran->hobi)==$h?'selected':'' }}>{{ $h }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Cita-cita</label>
                <select name="cita_cita" class="form-control">
                    <option value="">-- Pilih --</option>
                    @foreach(['PNS','TNI/Polri','Guru/Dosen','Dokter','Politikus','Wiraswasta','Seniman/Artis','Ilmuwan','Lainnya'] as $c)
                    <option value="{{ $c }}" {{ old('cita_cita', $pendaftaran->cita_cita)==$c?'selected':'' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Anak Ke-</label>
                <input type="number" name="anak_ke" class="form-control" value="{{ old('anak_ke', $pendaftaran->anak_ke) }}" min="1">
            </div>
            <div class="form-group">
                <label class="form-label">Jumlah Saudara</label>
                <input type="number" name="jumlah_saudara" class="form-control" value="{{ old('jumlah_saudara', $pendaftaran->jumlah_saudara) }}" min="0">
            </div>
            <div class="form-group">
                <label class="form-label">No. HP Siswa</label>
                <input type="text" name="no_telp" class="form-control" value="{{ old('no_telp', $pendaftaran->no_telp) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Status Tinggal</label>
                <select name="status_tinggal" class="form-control">
                    <option value="">-- Pilih --</option>
                    @foreach(['Tinggal dengan Ayah Kandung','Tinggal dengan Ibu Kandung','Tinggal dengan Wali','Ikut Saudara/Kerabat','Asrama Madrasah','Kontrak/Kost','Tinggal di Asrama Pesantren','Panti Asuhan','Rumah Singgah','Lainnya'] as $st)
                    <option value="{{ $st }}" {{ old('status_tinggal', $pendaftaran->status_tinggal)==$st?'selected':'' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Alamat Lengkap</label>
            <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $pendaftaran->alamat) }}</textarea>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div class="form-group">
                <label class="form-label">Desa/Kelurahan</label>
                <input type="text" name="desa_kelurahan" class="form-control" value="{{ old('desa_kelurahan', $pendaftaran->desa_kelurahan) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Kecamatan</label>
                <input type="text" name="kecamatan" class="form-control" value="{{ old('kecamatan', $pendaftaran->kecamatan) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Kabupaten/Kota</label>
                <input type="text" name="kabupaten_kota" class="form-control" value="{{ old('kabupaten_kota', $pendaftaran->kabupaten_kota) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Kode Pos</label>
                <input type="text" name="kode_pos" class="form-control" value="{{ old('kode_pos', $pendaftaran->kode_pos) }}">
            </div>
        </div>
    </div>
</div>

{{-- ===== ASAL SEKOLAH ===== --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-header"><span class="card-title">🏫 Asal Sekolah</span></div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div class="form-group">
                <label class="form-label">Nama Sekolah <span style="color:red;">*</span></label>
                <input type="text" name="asal_sekolah" class="form-control" value="{{ old('asal_sekolah', $pendaftaran->asal_sekolah) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">NPSN Sekolah</label>
                <input type="text" name="npsn_sekolah" class="form-control" value="{{ old('npsn_sekolah', $pendaftaran->npsn_sekolah) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Jenis Sekolah</label>
                <select name="jenis_sekolah" class="form-control">
                    <option value="">-- Pilih --</option>
                    @foreach(['SD','MI'] as $js)
                    <option value="{{ $js }}" {{ old('jenis_sekolah', $pendaftaran->jenis_sekolah)==$js?'selected':'' }}>{{ $js }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Status Sekolah</label>
                <select name="status_sekolah" class="form-control">
                    <option value="">-- Pilih --</option>
                    @foreach(['Negeri','Swasta'] as $ss)
                    <option value="{{ $ss }}" {{ old('status_sekolah', $pendaftaran->status_sekolah)==$ss?'selected':'' }}>{{ $ss }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Jalur <span style="color:red;">*</span></label>
                <select name="jalur" class="form-control" required>
                    <option value="reguler" {{ old('jalur', $pendaftaran->jalur)=='reguler'?'selected':'' }}>Reguler</option>
                    <option value="prestasi" {{ old('jalur', $pendaftaran->jalur)=='prestasi'?'selected':'' }}>Prestasi</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control form-select">
                    <option value="pending"    {{ old('status', $pendaftaran->status)=='pending'    ? 'selected' : '' }}>⏳ Pending</option>
                    <option value="verifikasi" {{ old('status', $pendaftaran->status)=='verifikasi' ? 'selected' : '' }}>🔵 Verifikasi</option>
                    <option value="lulus"      {{ old('status', $pendaftaran->status)=='lulus'      ? 'selected' : '' }}>✅ Lulus</option>
                    <option value="ditolak"    {{ old('status', $pendaftaran->status)=='ditolak'    ? 'selected' : '' }}>❌ Ditolak</option>
                </select>
            </div>
        </div>
    </div>
</div>

{{-- ===== INFORMASI ORANG TUA ===== --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-header"><span class="card-title">👨‍👩‍👧 Informasi Orang Tua</span></div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div class="form-group">
                <label class="form-label">No. Kartu Keluarga</label>
                <input type="text" name="no_kk" class="form-control" value="{{ old('no_kk', $pendaftaran->no_kk) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Nama Kepala Keluarga</label>
                <input type="text" name="nama_kepala_keluarga" class="form-control" value="{{ old('nama_kepala_keluarga', $pendaftaran->nama_kepala_keluarga) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Status Kepemilikan Rumah</label>
                <select name="status_kepemilikan_rumah" class="form-control">
                    <option value="">-- Pilih --</option>
                    @foreach(['Milik Sendiri','Rumah Orang Tua','Rumah Saudara/Kerabat','Rumah Dinas','Sewa/Kontrak','Lainnya'] as $sr)
                    <option value="{{ $sr }}" {{ old('status_kepemilikan_rumah', $pendaftaran->status_kepemilikan_rumah)==$sr?'selected':'' }}>{{ $sr }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Nama Orang Tua (Bin/Binti)</label>
                <input type="text" name="nama_orang_tua" class="form-control" value="{{ old('nama_orang_tua', $pendaftaran->nama_orang_tua) }}">
            </div>
        </div>

        {{-- Ayah --}}
        <div style="background:#f8f9fc;border-radius:10px;padding:16px;margin:16px 0;">
            <div style="font-size:12px;font-weight:700;color:#33528A;margin-bottom:12px;">👨 Ayah Kandung</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div class="form-group">
                    <label class="form-label">Nama Ayah</label>
                    <input type="text" name="nama_ayah" class="form-control" value="{{ old('nama_ayah', $pendaftaran->nama_ayah) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">NIK Ayah</label>
                    <input type="text" name="nik_ayah" class="form-control" value="{{ old('nik_ayah', $pendaftaran->nik_ayah) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Status Ayah</label>
                    <select name="status_ayah" class="form-control">
                        <option value="">-- Pilih --</option>
                        @foreach(['Masih Hidup','Sudah Meninggal','Tidak Diketahui'] as $sa)
                        <option value="{{ $sa }}" {{ old('status_ayah', $pendaftaran->status_ayah)==$sa?'selected':'' }}>{{ $sa }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">No. HP Ayah</label>
                    <input type="text" name="no_hp_ayah" class="form-control" value="{{ old('no_hp_ayah', $pendaftaran->no_hp_ayah) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Pendidikan Ayah</label>
                    <select name="pendidikan_ayah" class="form-control">
                        <option value="">-- Pilih --</option>
                        @foreach(['SD/Sederajat','SMP/Sederajat','SMA/Sederajat','D1','D2','D3','D4/S1','S2','S3','Tidak Bersekolah','Lainnya'] as $pd)
                        <option value="{{ $pd }}" {{ old('pendidikan_ayah', $pendaftaran->pendidikan_ayah)==$pd?'selected':'' }}>{{ $pd }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Pekerjaan Ayah</label>
                    <select name="pekerjaan_ayah" class="form-control">
                        <option value="">-- Pilih --</option>
                        @foreach(['Tidak Bekerja','Pensiunan','PNS','TNI/Polri','Guru/Dosen','Pegawai Swasta','Wiraswasta','Pedagang','Petani/Peternak','Nelayan','Buruh','Lainnya'] as $pk)
                        <option value="{{ $pk }}" {{ old('pekerjaan_ayah', $pendaftaran->pekerjaan_ayah)==$pk?'selected':'' }}>{{ $pk }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Penghasilan Ayah</label>
                    <select name="penghasilan_ayah" class="form-control">
                        <option value="">-- Pilih --</option>
                        @foreach(['dibawah 800.000','800.000 - 1.200.000','1.200.000 - 1.800.000','1.800.000 - 2.500.000','2.500.000 - 3.500.000','3.500.000 - 4.800.000','4.800.000 - 6.500.000','6.500.000 - 10.000.000','10.000.000 - 20.000.000','diatas 20.000.000'] as $pg)
                        <option value="{{ $pg }}" {{ old('penghasilan_ayah', $pendaftaran->penghasilan_ayah)==$pg?'selected':'' }}>{{ $pg }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Ibu --}}
        <div style="background:#f8f9fc;border-radius:10px;padding:16px;margin-bottom:16px;">
            <div style="font-size:12px;font-weight:700;color:#33528A;margin-bottom:12px;">👩 Ibu Kandung</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div class="form-group">
                    <label class="form-label">Nama Ibu</label>
                    <input type="text" name="nama_ibu" class="form-control" value="{{ old('nama_ibu', $pendaftaran->nama_ibu) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">NIK Ibu</label>
                    <input type="text" name="nik_ibu" class="form-control" value="{{ old('nik_ibu', $pendaftaran->nik_ibu) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Status Ibu</label>
                    <select name="status_ibu" class="form-control">
                        <option value="">-- Pilih --</option>
                        @foreach(['Masih Hidup','Sudah Meninggal','Tidak Diketahui'] as $si)
                        <option value="{{ $si }}" {{ old('status_ibu', $pendaftaran->status_ibu)==$si?'selected':'' }}>{{ $si }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">No. HP Ibu</label>
                    <input type="text" name="no_hp_ibu" class="form-control" value="{{ old('no_hp_ibu', $pendaftaran->no_hp_ibu) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Pendidikan Ibu</label>
                    <select name="pendidikan_ibu" class="form-control">
                        <option value="">-- Pilih --</option>
                        @foreach(['SD/Sederajat','SMP/Sederajat','SMA/Sederajat','D1','D2','D3','D4/S1','S2','S3','Tidak Bersekolah','Lainnya'] as $pd)
                        <option value="{{ $pd }}" {{ old('pendidikan_ibu', $pendaftaran->pendidikan_ibu)==$pd?'selected':'' }}>{{ $pd }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Pekerjaan Ibu</label>
                    <select name="pekerjaan_ibu" class="form-control">
                        <option value="">-- Pilih --</option>
                        @foreach(['Tidak Bekerja','Pensiunan','PNS','TNI/Polri','Guru/Dosen','Pegawai Swasta','Wiraswasta','Pedagang','Petani/Peternak','Nelayan','Buruh','Lainnya'] as $pk)
                        <option value="{{ $pk }}" {{ old('pekerjaan_ibu', $pendaftaran->pekerjaan_ibu)==$pk?'selected':'' }}>{{ $pk }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Penghasilan Ibu</label>
                    <select name="penghasilan_ibu" class="form-control">
                        <option value="">-- Pilih --</option>
                        @foreach(['dibawah 800.000','800.000 - 1.200.000','1.200.000 - 1.800.000','1.800.000 - 2.500.000','2.500.000 - 3.500.000','3.500.000 - 4.800.000','4.800.000 - 6.500.000','6.500.000 - 10.000.000','10.000.000 - 20.000.000','diatas 20.000.000'] as $pg)
                        <option value="{{ $pg }}" {{ old('penghasilan_ibu', $pendaftaran->penghasilan_ibu)==$pg?'selected':'' }}>{{ $pg }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Wali --}}
        <div style="margin-bottom:12px;">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;font-weight:600;">
                <input type="checkbox" id="toggle-wali-edit" onchange="document.getElementById('section-wali-edit').style.display=this.checked?'block':'none'"
                    {{ $pendaftaran->nama_wali ? 'checked' : '' }}>
                Tambah/Edit Data Wali
            </label>
        </div>

        <div id="section-wali-edit" style="display:{{ $pendaftaran->nama_wali ? 'block' : 'none' }};background:#f8f9fc;border-radius:10px;padding:16px;">
            <div style="font-size:12px;font-weight:700;color:#33528A;margin-bottom:12px;">🧑 Wali Siswa</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div class="form-group">
                    <label class="form-label">Nama Wali</label>
                    <input type="text" name="nama_wali" class="form-control" value="{{ old('nama_wali', $pendaftaran->nama_wali) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">NIK Wali</label>
                    <input type="text" name="nik_wali" class="form-control" value="{{ old('nik_wali', $pendaftaran->nik_wali) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Status Wali</label>
                    <select name="status_wali" class="form-control">
                        <option value="">-- Pilih --</option>
                        @foreach(['Kakak','Nenek','Paman','Bibi'] as $sw)
                        <option value="{{ $sw }}" {{ old('status_wali', $pendaftaran->status_wali)==$sw?'selected':'' }}>{{ $sw }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">No. HP Wali</label>
                    <input type="text" name="no_hp_wali" class="form-control" value="{{ old('no_hp_wali', $pendaftaran->no_hp_wali) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Pendidikan Wali</label>
                    <select name="pendidikan_wali" class="form-control">
                        <option value="">-- Pilih --</option>
                        @foreach(['SD/Sederajat','SMP/Sederajat','SMA/Sederajat','D1','D2','D3','D4/S1','S2','S3','Tidak Bersekolah','Lainnya'] as $pd)
                        <option value="{{ $pd }}" {{ old('pendidikan_wali', $pendaftaran->pendidikan_wali)==$pd?'selected':'' }}>{{ $pd }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Pekerjaan Wali</label>
                    <select name="pekerjaan_wali" class="form-control">
                        <option value="">-- Pilih --</option>
                        @foreach(['Tidak Bekerja','Pensiunan','PNS','TNI/Polri','Guru/Dosen','Pegawai Swasta','Wiraswasta','Pedagang','Petani/Peternak','Nelayan','Buruh','Lainnya'] as $pk)
                        <option value="{{ $pk }}" {{ old('pekerjaan_wali', $pendaftaran->pekerjaan_wali)==$pk?'selected':'' }}>{{ $pk }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Penghasilan Wali</label>
                    <select name="penghasilan_wali" class="form-control">
                        <option value="">-- Pilih --</option>
                        @foreach(['dibawah 800.000','800.000 - 1.200.000','1.200.000 - 1.800.000','1.800.000 - 2.500.000','2.500.000 - 3.500.000','3.500.000 - 4.800.000','4.800.000 - 6.500.000','6.500.000 - 10.000.000','10.000.000 - 20.000.000','diatas 20.000.000'] as $pg)
                        <option value="{{ $pg }}" {{ old('penghasilan_wali', $pendaftaran->penghasilan_wali)==$pg?'selected':'' }}>{{ $pg }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== PIP ===== --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-header"><span class="card-title">📋 Informasi PIP</span></div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;">
            <div class="form-group">
                <label class="form-label">No. KKS</label>
                <input type="text" name="no_kks" class="form-control" value="{{ old('no_kks', $pendaftaran->no_kks) }}">
            </div>
            <div class="form-group">
                <label class="form-label">No. PKH</label>
                <input type="text" name="no_pkh" class="form-control" value="{{ old('no_pkh', $pendaftaran->no_pkh) }}">
            </div>
            <div class="form-group">
                <label class="form-label">No. KIP</label>
                <input type="text" name="no_kip" class="form-control" value="{{ old('no_kip', $pendaftaran->no_kip) }}">
            </div>
        </div>
    </div>
</div>

{{-- ===== CATATAN ===== --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-header"><span class="card-title">📝 Catatan</span></div>
    <div class="card-body">
        <div class="form-group">
            <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan tambahan (opsional)">{{ old('catatan', $pendaftaran->catatan) }}</textarea>
        </div>
    </div>
</div>

{{-- Tombol --}}
<div style="display:flex;gap:12px;justify-content:flex-end;margin-bottom:32px;">
    <a href="{{ route('pendaftaran.index') }}" class="btn btn-outline">Batal</a>
    <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
</div>

</form>

@endsection