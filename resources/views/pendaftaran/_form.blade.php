@php
    $isEdit = isset($pendaftaran);
@endphp

@if ($errors->any())
    <div style="background:#fee2e2;color:#991b1b;padding:12px 16px;border-radius:8px;margin-bottom:20px;font-size:12px;border-left:4px solid #e05454;">
        <strong>⚠️ Terdapat kesalahan:</strong>
        <ul style="margin:6px 0 0;padding-left:18px;">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- ===== INFORMASI PRIBADI SISWA ===== --}}
<div style="font-size:11px;font-weight:700;color:#33528A;text-transform:uppercase;letter-spacing:1px;margin-bottom:12px;padding-bottom:6px;border-bottom:2px solid #C4E81D;">
    👤 Informasi Pribadi Siswa
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
    <div class="form-group">
        <label class="form-label">NISN</label>
        <input type="text" name="nisn" class="form-control"
            value="{{ old('nisn', $pendaftaran->nisn ?? '') }}"
            placeholder="Nomor Induk Siswa Nasional">
    </div>
    <div class="form-group">
        <label class="form-label">NIK Siswa</label>
        <input type="text" name="nik" class="form-control"
            value="{{ old('nik', $pendaftaran->nik ?? '') }}"
            placeholder="Nomor Induk Kependudukan">
    </div>
</div>

<div class="form-group">
    <label class="form-label">Nama Siswa <span style="color:#e05454">*</span></label>
    <input type="text" name="nama" class="form-control"
        value="{{ old('nama', $pendaftaran->nama ?? '') }}"
        required placeholder="Nama lengkap sesuai akta">
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
    <div class="form-group">
        <label class="form-label">Tempat Lahir <span style="color:#e05454">*</span></label>
        <input type="text" name="tempat_lahir" class="form-control"
            value="{{ old('tempat_lahir', $pendaftaran->tempat_lahir ?? '') }}"
            required>
    </div>
    <div class="form-group">
        <label class="form-label">Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" class="form-control"
            value="{{ old('tanggal_lahir', $pendaftaran->tanggal_lahir ?? '') }}">
    </div>
</div>

<div class="form-group">
    <label class="form-label">Jenis Kelamin <span style="color:#e05454">*</span></label>
    <div style="display:flex;gap:20px;margin-top:6px;">
        <label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer;">
            <input type="radio" name="jenis_kelamin" value="L"
                {{ old('jenis_kelamin', $pendaftaran->jenis_kelamin ?? '') == 'L' ? 'checked' : '' }}>
            Laki-laki
        </label>
        <label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer;">
            <input type="radio" name="jenis_kelamin" value="P"
                {{ old('jenis_kelamin', $pendaftaran->jenis_kelamin ?? '') == 'P' ? 'checked' : '' }}>
            Perempuan
        </label>
    </div>
</div>

<div class="form-group">
    <label class="form-label">Hobi</label>
    <div style="display:flex;flex-wrap:wrap;gap:12px;margin-top:6px;">
        @foreach (['Olahraga','Kesenian','Membaca','Menulis','Jalan-jalan','Lainnya'] as $h)
            <label style="display:flex;align-items:center;gap:6px;font-size:12px;cursor:pointer;">
                <input type="radio" name="hobi" value="{{ $h }}"
                    {{ old('hobi', $pendaftaran->hobi ?? '') == $h ? 'checked' : '' }}>
                {{ $h }}
            </label>
        @endforeach
    </div>
</div>

<div class="form-group">
    <label class="form-label">Cita-cita</label>
    <div style="display:flex;flex-wrap:wrap;gap:12px;margin-top:6px;">
        @foreach (['PNS','TNI/Polri','Guru/Dosen','Dokter','Politikus','Wiraswasta','Seniman/Artis','Ilmuwan','Lainnya'] as $c)
            <label style="display:flex;align-items:center;gap:6px;font-size:12px;cursor:pointer;">
                <input type="radio" name="cita_cita" value="{{ $c }}"
                    {{ old('cita_cita', $pendaftaran->cita_cita ?? '') == $c ? 'checked' : '' }}>
                {{ $c }}
            </label>
        @endforeach
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
    <div class="form-group">
        <label class="form-label">Anak Ke-</label>
        <input type="number" name="anak_ke" class="form-control"
            value="{{ old('anak_ke', $pendaftaran->anak_ke ?? '') }}"
            min="1" placeholder="contoh: 1">
    </div>
    <div class="form-group">
        <label class="form-label">Jumlah Saudara</label>
        <input type="number" name="jumlah_saudara" class="form-control"
            value="{{ old('jumlah_saudara', $pendaftaran->jumlah_saudara ?? '') }}"
            min="0" placeholder="contoh: 2">
    </div>
</div>

<div class="form-group">
    <label class="form-label">No. HP Siswa</label>
    <input type="text" name="no_telp" class="form-control"
        value="{{ old('no_telp', $pendaftaran->no_telp ?? '') }}"
        placeholder="08xxxxxxxxxx">
</div>

<div class="form-group">
    <label class="form-label">Alamat Siswa <span style="color:#e05454">*</span></label>
    <div style="display:flex;flex-wrap:wrap;gap:10px;margin-top:6px;margin-bottom:10px;">
        @foreach (['Tinggal dengan Ayah Kandung','Tinggal dengan Ibu Kandung','Tinggal dengan Wali','Ikut Saudara/Kerabat','Asrama Madrasah','Kontrak/Kost','Tinggal di Asrama Pesantren','Panti Asuhan','Rumah Singgah','Lainnya'] as $st)
            <label style="display:flex;align-items:center;gap:6px;font-size:12px;cursor:pointer;">
                <input type="radio" name="status_tinggal" value="{{ $st }}"
                    {{ old('status_tinggal', $pendaftaran->status_tinggal ?? '') == $st ? 'checked' : '' }}>
                {{ $st }}
            </label>
        @endforeach
    </div>
    <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat lengkap" required>{{ old('alamat', $pendaftaran->alamat ?? '') }}</textarea>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
    <div class="form-group">
        <label class="form-label">Desa/Kelurahan</label>
        <input type="text" name="desa_kelurahan" class="form-control"
            value="{{ old('desa_kelurahan', $pendaftaran->desa_kelurahan ?? '') }}">
    </div>
    <div class="form-group">
        <label class="form-label">Kecamatan</label>
        <input type="text" name="kecamatan" class="form-control"
            value="{{ old('kecamatan', $pendaftaran->kecamatan ?? '') }}">
    </div>
    <div class="form-group">
        <label class="form-label">Kabupaten/Kota</label>
        <input type="text" name="kabupaten_kota" class="form-control"
            value="{{ old('kabupaten_kota', $pendaftaran->kabupaten_kota ?? '') }}">
    </div>
    <div class="form-group">
        <label class="form-label">Kode Pos</label>
        <input type="text" name="kode_pos" class="form-control"
            value="{{ old('kode_pos', $pendaftaran->kode_pos ?? '') }}"
            placeholder="contoh: 70700">
    </div>
</div>

{{-- ===== ASAL SEKOLAH ===== --}}
<div style="font-size:11px;font-weight:700;color:#33528A;text-transform:uppercase;letter-spacing:1px;margin:20px 0 12px;padding-bottom:6px;border-bottom:2px solid #C4E81D;">
    🏫 Asal Sekolah
</div>

<div class="form-group">
    <label class="form-label">Nama Sekolah <span style="color:#e05454">*</span></label>
    <input type="text" name="asal_sekolah" class="form-control"
        value="{{ old('asal_sekolah', $pendaftaran->asal_sekolah ?? '') }}"
        required placeholder="Nama SD/MI asal">
</div>

<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;">
    <div class="form-group">
        <label class="form-label">Jenis Sekolah</label>
        <div style="display:flex;gap:12px;margin-top:6px;">
            @foreach (['SD','MI'] as $js)
                <label style="display:flex;align-items:center;gap:6px;font-size:12px;cursor:pointer;">
                    <input type="radio" name="jenis_sekolah" value="{{ $js }}"
                        {{ old('jenis_sekolah', $pendaftaran->jenis_sekolah ?? '') == $js ? 'checked' : '' }}>
                    {{ $js }}
                </label>
            @endforeach
        </div>
    </div>
    <div class="form-group">
        <label class="form-label">Status Sekolah</label>
        <div style="display:flex;gap:12px;margin-top:6px;">
            @foreach (['Negeri','Swasta'] as $ss)
                <label style="display:flex;align-items:center;gap:6px;font-size:12px;cursor:pointer;">
                    <input type="radio" name="status_sekolah" value="{{ $ss }}"
                        {{ old('status_sekolah', $pendaftaran->status_sekolah ?? '') == $ss ? 'checked' : '' }}>
                    {{ $ss }}
                </label>
            @endforeach
        </div>
    </div>
    <div class="form-group">
        <label class="form-label">NPSN Sekolah</label>
        <input type="text" name="npsn_sekolah" class="form-control"
            value="{{ old('npsn_sekolah', $pendaftaran->npsn_sekolah ?? '') }}"
            placeholder="8 digit">
    </div>
</div>

{{-- ===== INFORMASI ORANG TUA ===== --}}
<div style="font-size:11px;font-weight:700;color:#33528A;text-transform:uppercase;letter-spacing:1px;margin:20px 0 12px;padding-bottom:6px;border-bottom:2px solid #C4E81D;">
    👨‍👩‍👧 Informasi Orang Tua
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
    <div class="form-group">
        <label class="form-label">No. Kartu Keluarga</label>
        <input type="text" name="no_kk" class="form-control"
            value="{{ old('no_kk', $pendaftaran->no_kk ?? '') }}"
            placeholder="16 digit">
    </div>
    <div class="form-group">
        <label class="form-label">Nama Kepala Keluarga</label>
        <input type="text" name="nama_kepala_keluarga" class="form-control"
            value="{{ old('nama_kepala_keluarga', $pendaftaran->nama_kepala_keluarga ?? '') }}">
    </div>
</div>

<div class="form-group">
    <label class="form-label">Status Kepemilikan Rumah</label>
    <div style="display:flex;flex-wrap:wrap;gap:12px;margin-top:6px;">
        @foreach (['Milik Sendiri','Rumah Orang Tua','Rumah Saudara/Kerabat','Rumah Dinas','Sewa/Kontrak','Lainnya'] as $sr)
            <label style="display:flex;align-items:center;gap:6px;font-size:12px;cursor:pointer;">
                <input type="radio" name="status_kepemilikan_rumah" value="{{ $sr }}"
                    {{ old('status_kepemilikan_rumah', $pendaftaran->status_kepemilikan_rumah ?? '') == $sr ? 'checked' : '' }}>
                {{ $sr }}
            </label>
        @endforeach
    </div>
</div>

{{-- AYAH --}}
<div style="background:#f8f9fc;border-radius:10px;padding:16px;margin-bottom:16px;">
    <div style="font-size:12px;font-weight:700;color:#33528A;margin-bottom:12px;">👨 Ayah Kandung</div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div class="form-group">
            <label class="form-label">Nama Ayah</label>
            <input type="text" name="nama_ayah" class="form-control"
                value="{{ old('nama_ayah', $pendaftaran->nama_ayah ?? '') }}">
        </div>
        <div class="form-group">
            <label class="form-label">NIK/No. KTP Ayah</label>
            <input type="text" name="nik_ayah" class="form-control"
                value="{{ old('nik_ayah', $pendaftaran->nik_ayah ?? '') }}">
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">Status Ayah</label>
        <div style="display:flex;gap:16px;margin-top:6px;">
            @foreach (['Masih Hidup','Sudah Meninggal','Tidak Diketahui'] as $sa)
                <label style="display:flex;align-items:center;gap:6px;font-size:12px;cursor:pointer;">
                    <input type="radio" name="status_ayah" value="{{ $sa }}"
                        {{ old('status_ayah', $pendaftaran->status_ayah ?? '') == $sa ? 'checked' : '' }}>
                    {{ $sa }}
                </label>
            @endforeach
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div class="form-group">
            <label class="form-label">Pendidikan Ayah</label>
            <select name="pendidikan_ayah" class="form-control">
                <option value="">-- Pilih --</option>
                @foreach (['SD/Sederajat','SMP/Sederajat','SMA/Sederajat','D1','D2','D3','D4/S1','S2','S3','Tidak Bersekolah','Lainnya'] as $pd)
                    <option value="{{ $pd }}" {{ old('pendidikan_ayah', $pendaftaran->pendidikan_ayah ?? '') == $pd ? 'selected' : '' }}>{{ $pd }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Pekerjaan Ayah</label>
            <select name="pekerjaan_ayah" class="form-control">
                <option value="">-- Pilih --</option>
                @foreach (['Tidak Bekerja','Pensiunan','PNS','TNI/Polri','Guru/Dosen','Pegawai Swasta','Wiraswasta','Seniman','Dokter/Bidan/Perawat','Pedagang','Petani/Peternak','Nelayan','Buruh','Sopir','Politikus','Lainnya'] as $pk)
                    <option value="{{ $pk }}" {{ old('pekerjaan_ayah', $pendaftaran->pekerjaan_ayah ?? '') == $pk ? 'selected' : '' }}>{{ $pk }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Penghasilan Ayah</label>
            <select name="penghasilan_ayah" class="form-control">
                <option value="">-- Pilih --</option>
                @foreach (['dibawah 800.000','800.000 - 1.200.000','1.200.000 - 1.800.000','1.800.000 - 2.500.000','2.500.000 - 3.500.000','3.500.000 - 4.800.000','4.800.000 - 6.500.000','6.500.000 - 10.000.000','10.000.000 - 20.000.000','diatas 20.000.000'] as $pg)
                    <option value="{{ $pg }}" {{ old('penghasilan_ayah', $pendaftaran->penghasilan_ayah ?? '') == $pg ? 'selected' : '' }}>{{ $pg }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">No. HP Ayah</label>
            <input type="text" name="no_hp_ayah" class="form-control"
                value="{{ old('no_hp_ayah', $pendaftaran->no_hp_ayah ?? '') }}"
                placeholder="08xxxxxxxxxx">
        </div>
    </div>
</div>

{{-- IBU --}}
<div style="background:#f8f9fc;border-radius:10px;padding:16px;margin-bottom:16px;">
    <div style="font-size:12px;font-weight:700;color:#33528A;margin-bottom:12px;">👩 Ibu Kandung</div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div class="form-group">
            <label class="form-label">Nama Ibu</label>
            <input type="text" name="nama_ibu" class="form-control"
                value="{{ old('nama_ibu', $pendaftaran->nama_ibu ?? '') }}">
        </div>
        <div class="form-group">
            <label class="form-label">NIK/No. KTP Ibu</label>
            <input type="text" name="nik_ibu" class="form-control"
                value="{{ old('nik_ibu', $pendaftaran->nik_ibu ?? '') }}">
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">Status Ibu</label>
        <div style="display:flex;gap:16px;margin-top:6px;">
            @foreach (['Masih Hidup','Sudah Meninggal','Tidak Diketahui'] as $si)
                <label style="display:flex;align-items:center;gap:6px;font-size:12px;cursor:pointer;">
                    <input type="radio" name="status_ibu" value="{{ $si }}"
                        {{ old('status_ibu', $pendaftaran->status_ibu ?? '') == $si ? 'checked' : '' }}>
                    {{ $si }}
                </label>
            @endforeach
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div class="form-group">
            <label class="form-label">Pendidikan Ibu</label>
            <select name="pendidikan_ibu" class="form-control">
                <option value="">-- Pilih --</option>
                @foreach (['SD/Sederajat','SMP/Sederajat','SMA/Sederajat','D1','D2','D3','D4/S1','S2','S3','Tidak Bersekolah','Lainnya'] as $pd)
                    <option value="{{ $pd }}" {{ old('pendidikan_ibu', $pendaftaran->pendidikan_ibu ?? '') == $pd ? 'selected' : '' }}>{{ $pd }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Pekerjaan Ibu</label>
            <select name="pekerjaan_ibu" class="form-control">
                <option value="">-- Pilih --</option>
                @foreach (['Ibu Rumah Tangga','Tidak Bekerja','Pensiunan','PNS','TNI/Polri','Guru/Dosen','Pegawai Swasta','Wiraswasta','Seniman','Dokter/Bidan/Perawat','Pedagang','Petani/Peternak','Nelayan','Buruh','Sopir','Politikus','Lainnya'] as $pk)
                    <option value="{{ $pk }}" {{ old('pekerjaan_ibu', $pendaftaran->pekerjaan_ibu ?? '') == $pk ? 'selected' : '' }}>{{ $pk }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Penghasilan Ibu</label>
            <select name="penghasilan_ibu" class="form-control">
                <option value="">-- Pilih --</option>
                @foreach (['dibawah 800.000','800.000 - 1.200.000','1.200.000 - 1.800.000','1.800.000 - 2.500.000','2.500.000 - 3.500.000','3.500.000 - 4.800.000','4.800.000 - 6.500.000','6.500.000 - 10.000.000','10.000.000 - 20.000.000','diatas 20.000.000'] as $pg)
                    <option value="{{ $pg }}" {{ old('penghasilan_ibu', $pendaftaran->penghasilan_ibu ?? '') == $pg ? 'selected' : '' }}>{{ $pg }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">No. HP Ibu</label>
            <input type="text" name="no_hp_ibu" class="form-control"
                value="{{ old('no_hp_ibu', $pendaftaran->no_hp_ibu ?? '') }}"
                placeholder="08xxxxxxxxxx">
        </div>
    </div>
</div>

{{-- WALI --}}
<div style="background:#f8f9fc;border-radius:10px;padding:16px;margin-bottom:16px;">
    <div style="font-size:12px;font-weight:700;color:#33528A;margin-bottom:12px;">🧑‍💼 Wali</div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div class="form-group">
            <label class="form-label">Nama Wali</label>
            <input type="text" name="nama_wali" class="form-control"
                value="{{ old('nama_wali', $pendaftaran->nama_wali ?? '') }}">
        </div>
        <div class="form-group">
            <label class="form-label">NIK/No. KTP Wali</label>
            <input type="text" name="nik_wali" class="form-control"
                value="{{ old('nik_wali', $pendaftaran->nik_wali ?? '') }}">
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">Status Wali</label>
        <input type="text" name="status_wali" class="form-control"
            value="{{ old('status_wali', $pendaftaran->status_wali ?? '') }}"
            placeholder="Contoh: Paman / Bibi / Kakak / Kakek">
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div class="form-group">
            <label class="form-label">Pendidikan Wali</label>
            <select name="pendidikan_wali" class="form-control">
                <option value="">-- Pilih --</option>
                @foreach (['SD/Sederajat','SMP/Sederajat','SMA/Sederajat','D1','D2','D3','D4/S1','S2','S3','Tidak Bersekolah','Lainnya'] as $pd)
                    <option value="{{ $pd }}" {{ old('pendidikan_wali', $pendaftaran->pendidikan_wali ?? '') == $pd ? 'selected' : '' }}>{{ $pd }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Pekerjaan Wali</label>
            <select name="pekerjaan_wali" class="form-control">
                <option value="">-- Pilih --</option>
                @foreach (['Tidak Bekerja','Pensiunan','PNS','TNI/Polri','Guru/Dosen','Pegawai Swasta','Wiraswasta','Seniman','Dokter/Bidan/Perawat','Pedagang','Petani/Peternak','Nelayan','Buruh','Sopir','Politikus','Lainnya'] as $pk)
                    <option value="{{ $pk }}" {{ old('pekerjaan_wali', $pendaftaran->pekerjaan_wali ?? '') == $pk ? 'selected' : '' }}>{{ $pk }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Penghasilan Wali</label>
            <select name="penghasilan_wali" class="form-control">
                <option value="">-- Pilih --</option>
                @foreach (['dibawah 800.000','800.000 - 1.200.000','1.200.000 - 1.800.000','1.800.000 - 2.500.000','2.500.000 - 3.500.000','3.500.000 - 4.800.000','4.800.000 - 6.500.000','6.500.000 - 10.000.000','10.000.000 - 20.000.000','diatas 20.000.000'] as $pg)
                    <option value="{{ $pg }}" {{ old('penghasilan_wali', $pendaftaran->penghasilan_wali ?? '') == $pg ? 'selected' : '' }}>{{ $pg }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">No. HP Wali</label>
            <input type="text" name="no_hp_wali" class="form-control"
                value="{{ old('no_hp_wali', $pendaftaran->no_hp_wali ?? '') }}"
                placeholder="08xxxxxxxxxx">
        </div>
    </div>
</div>

{{-- ===== JALUR PENDAFTARAN ===== --}}
<div style="font-size:11px;font-weight:700;color:#33528A;text-transform:uppercase;letter-spacing:1px;margin:20px 0 12px;padding-bottom:6px;border-bottom:2px solid #C4E81D;">
    📋 Jalur Pendaftaran
</div>

<div class="form-group">
    <label class="form-label">Jalur <span style="color:#e05454">*</span></label>
    <div style="display:flex;gap:20px;margin-top:6px;">
        <label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer;">
            <input type="radio" name="jalur" value="reguler"
                {{ old('jalur', $pendaftaran->jalur ?? '') == 'reguler' ? 'checked' : '' }}
                required>
            📋 Reguler
        </label>
        <label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer;">
            <input type="radio" name="jalur" value="prestasi"
                {{ old('jalur', $pendaftaran->jalur ?? '') == 'prestasi' ? 'checked' : '' }}>
            🏆 Prestasi
        </label>
    </div>
</div>

<div class="form-group">
    <label class="form-label">Bin/Binti (Nama Orang Tua) <span style="color:#e05454">*</span></label>
    <input type="text" name="nama_orang_tua" class="form-control"
        value="{{ old('nama_orang_tua', $pendaftaran->nama_orang_tua ?? '') }}"
        placeholder="contoh: bin Ahmad / binti Siti"
        required>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;">
    <div class="form-group">
        <label class="form-label">No. KKS</label>
        <input type="text" name="no_kks" class="form-control"
            value="{{ old('no_kks', $pendaftaran->no_kks ?? '') }}">
    </div>
    <div class="form-group">
        <label class="form-label">No. PKH</label>
        <input type="text" name="no_pkh" class="form-control"
            value="{{ old('no_pkh', $pendaftaran->no_pkh ?? '') }}">
    </div>
    <div class="form-group">
        <label class="form-label">No. KIP</label>
        <input type="text" name="no_kip" class="form-control"
            value="{{ old('no_kip', $pendaftaran->no_kip ?? '') }}">
    </div>
</div>

{{-- ===== UPLOAD BERKAS ===== --}}
<div style="font-size:11px;font-weight:700;color:#33528A;text-transform:uppercase;letter-spacing:1px;margin:20px 0 12px;padding-bottom:6px;border-bottom:2px solid #C4E81D;">
    📁 Upload Berkas
</div>

<div style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:12px;color:#0369a1;">
    ℹ️ Upload berkas dalam format <strong>JPG, PNG, atau PDF</strong>. Ukuran maksimal masing-masing file <strong>2MB</strong>.
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
    <div class="form-group">
        <label class="form-label">File NISN</label>
        <input type="file" name="nisn_file" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
        @if (!empty($pendaftaran->nisn_file ?? null))
            <div style="font-size:11px;margin-top:6px;">
                File saat ini:
                <a href="{{ asset('storage/' . $pendaftaran->nisn_file) }}" target="_blank">Lihat file</a>
            </div>
        @endif
    </div>

    <div class="form-group">
        <label class="form-label">Kartu Keluarga</label>
        <input type="file" name="kartu_keluarga" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
        @if (!empty($pendaftaran->kartu_keluarga ?? null))
            <div style="font-size:11px;margin-top:6px;">
                File saat ini:
                <a href="{{ asset('storage/' . $pendaftaran->kartu_keluarga) }}" target="_blank">Lihat file</a>
            </div>
        @endif
    </div>

    <div class="form-group">
        <label class="form-label">Akta Kelahiran</label>
        <input type="file" name="akta_kelahiran" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
        @if (!empty($pendaftaran->akta_kelahiran ?? null))
            <div style="font-size:11px;margin-top:6px;">
                File saat ini:
                <a href="{{ asset('storage/' . $pendaftaran->akta_kelahiran) }}" target="_blank">Lihat file</a>
            </div>
        @endif
    </div>

    <div class="form-group">
        <label class="form-label">Foto 3x4</label>
        <input type="file" name="foto" class="form-control" accept=".jpg,.jpeg,.png">
        @if (!empty($pendaftaran->foto ?? null))
            <div style="font-size:11px;margin-top:6px;">
                File saat ini:
                <a href="{{ asset('storage/' . $pendaftaran->foto) }}" target="_blank">Lihat file</a>
            </div>
        @endif
    </div>
</div>

<div class="form-group" style="margin-top:12px;">
    <label class="form-label">Ijazah / SKL</label>
    <input type="file" name="ijazah" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
    @if (!empty($pendaftaran->ijazah ?? null))
        <div style="font-size:11px;margin-top:6px;">
            File saat ini:
            <a href="{{ asset('storage/' . $pendaftaran->ijazah) }}" target="_blank">Lihat file</a>
        </div>
    @endif
</div>