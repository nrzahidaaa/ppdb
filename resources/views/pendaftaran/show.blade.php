@extends('layouts.app')

@section('title', 'Detail Pendaftar')
@section('page-title', 'Detail Pendaftar')

@section('content')

<div class="section-header">
    <div>
        <h2 style="font-size:16px;font-weight:700;">📋 Detail Pendaftar</h2>
        <p style="font-size:12px;color:var(--text-light);">Informasi lengkap data pendaftaran siswa</p>
    </div>
</div>

<div class="card" style="margin-bottom:20px;">
    <div class="card-body" style="padding:24px;">

        {{-- Header Profil --}}
        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:20px;flex-wrap:wrap;margin-bottom:24px;">
            <div style="display:flex;align-items:center;gap:16px;">
                <div style="width:64px;height:64px;border-radius:16px;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;color:white;font-size:24px;font-weight:800;">
                    {{ strtoupper(substr($pendaftaran->nama ?? '-', 0, 1)) }}
                </div>
                <div>
                    <div style="font-size:20px;font-weight:800;color:var(--text);">
                        {{ $pendaftaran->nama ?? '-' }}
                    </div>
                    <div style="font-size:13px;color:var(--text-light);margin-top:4px;">
                        {{ $pendaftaran->nomor_pendaftaran ?? '-' }}
                    </div>
                    <div style="margin-top:10px;display:flex;gap:8px;flex-wrap:wrap;">
                        <span class="badge {{ strtolower($pendaftaran->jalur ?? '') == 'prestasi' ? 'badge-warning' : 'badge-secondary' }}">
                            {{ ucfirst($pendaftaran->jalur ?? '-') }}
                        </span>

                        @if(($pendaftaran->status ?? '') === 'lulus')
                            <span class="badge badge-success">✅ Lulus</span>
                        @elseif(($pendaftaran->status ?? '') === 'ditolak' || ($pendaftaran->status ?? '') === 'tidak_lulus')
                            <span class="badge badge-danger">❌ Tidak Lulus</span>
                        @elseif(($pendaftaran->status ?? '') === 'pending')
                            <span class="badge badge-warning">⏳ Pending</span>
                        @elseif(($pendaftaran->status ?? '') === 'waiting_proses')
                            <span class="badge badge-primary">🕒 Waiting Proses</span>
                        @elseif(($pendaftaran->status ?? '') === 'verifikasi')
                            <span class="badge badge-secondary">🔵 Verifikasi</span>
                        @else
                            <span class="badge badge-secondary">{{ ucfirst($pendaftaran->status ?? '-') }}</span>
                        @endif

                        @if(!empty($pendaftaran->predikat))
                            @if($pendaftaran->predikat === 'Unggul')
                                <span class="badge" style="background:#eef9d7;color:#597001;">🏆 Unggul</span>
                            @elseif($pendaftaran->predikat === 'Baik')
                                <span class="badge" style="background:#e6f7f5;color:#1f8f87;">⭐ Baik</span>
                            @elseif($pendaftaran->predikat === 'Cukup')
                                <span class="badge" style="background:#eaf1ff;color:#33528A;">📝 Cukup</span>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                <a href="{{ route('pendaftaran.index') }}" class="btn btn-secondary">← Kembali</a>
                <a href="{{ route('pendaftaran.edit', $pendaftaran->id) }}" class="btn btn-primary">✏️ Edit</a>
            </div>
        </div>

        {{-- Catatan Admin --}}
        @if(!empty($pendaftaran->catatan))
            <div class="alert alert-warning" style="margin-bottom:20px;">
                <strong>📌 Catatan Admin:</strong><br>
                {{ $pendaftaran->catatan }}
            </div>
        @endif

        {{-- Informasi Pribadi --}}
        <div class="card" style="margin-bottom:18px;">
            <div class="card-header">
                <span class="card-title">👤 Informasi Pribadi Siswa</span>
            </div>
            <div class="card-body" style="padding:20px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px 24px;">
                    <div><strong>NISN:</strong><br>{{ $pendaftaran->nisn ?? '-' }}</div>
                    <div><strong>NIK:</strong><br>{{ $pendaftaran->nik ?? '-' }}</div>
                    <div><strong>Nama Lengkap:</strong><br>{{ $pendaftaran->nama ?? '-' }}</div>
                    <div><strong>Jenis Kelamin:</strong><br>
                        @if(($pendaftaran->jenis_kelamin ?? '') === 'L')
                            Laki-laki
                        @elseif(($pendaftaran->jenis_kelamin ?? '') === 'P')
                            Perempuan
                        @else
                            -
                        @endif
                    </div>
                    <div><strong>Tempat Lahir:</strong><br>{{ $pendaftaran->tempat_lahir ?? '-' }}</div>
                    <div><strong>Tanggal Lahir:</strong><br>{{ $pendaftaran->tanggal_lahir ? \Carbon\Carbon::parse($pendaftaran->tanggal_lahir)->format('d/m/Y') : '-' }}</div>
                    <div><strong>Hobi:</strong><br>{{ $pendaftaran->hobi ?? '-' }}</div>
                    <div><strong>Cita-cita:</strong><br>{{ $pendaftaran->cita_cita ?? '-' }}</div>
                    <div><strong>Anak Ke-:</strong><br>{{ $pendaftaran->anak_ke ?? '-' }}</div>
                    <div><strong>Jumlah Saudara:</strong><br>{{ $pendaftaran->jumlah_saudara ?? '-' }}</div>
                    <div><strong>No. HP Siswa:</strong><br>{{ $pendaftaran->no_telp ?? '-' }}</div>
                    <div><strong>Status Tinggal:</strong><br>{{ $pendaftaran->status_tinggal ?? '-' }}</div>
                </div>
            </div>
        </div>

        {{-- Alamat --}}
        <div class="card" style="margin-bottom:18px;">
            <div class="card-header">
                <span class="card-title">📍 Alamat Siswa</span>
            </div>
            <div class="card-body" style="padding:20px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px 24px;">
                    <div style="grid-column:1 / -1;"><strong>Alamat Lengkap:</strong><br>{{ $pendaftaran->alamat ?? '-' }}</div>
                    <div><strong>Desa/Kelurahan:</strong><br>{{ $pendaftaran->desa_kelurahan ?? '-' }}</div>
                    <div><strong>Kecamatan:</strong><br>{{ $pendaftaran->kecamatan ?? '-' }}</div>
                    <div><strong>Kabupaten/Kota:</strong><br>{{ $pendaftaran->kabupaten_kota ?? '-' }}</div>
                    <div><strong>Kode Pos:</strong><br>{{ $pendaftaran->kode_pos ?? '-' }}</div>
                </div>
            </div>
        </div>

        {{-- Asal Sekolah --}}
        <div class="card" style="margin-bottom:18px;">
            <div class="card-header">
                <span class="card-title">🏫 Asal Sekolah</span>
            </div>
            <div class="card-body" style="padding:20px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px 24px;">
                    <div><strong>Asal Sekolah:</strong><br>{{ $pendaftaran->asal_sekolah ?? '-' }}</div>
                    <div><strong>Nama Sekolah:</strong><br>{{ $pendaftaran->nama_sekolah ?? '-' }}</div>
                    <div><strong>Jenis Sekolah:</strong><br>{{ $pendaftaran->jenis_sekolah ?? '-' }}</div>
                    <div><strong>Status Sekolah:</strong><br>{{ $pendaftaran->status_sekolah ?? '-' }}</div>
                    <div><strong>NPSN Sekolah:</strong><br>{{ $pendaftaran->npsn_sekolah ?? '-' }}</div>
                    <div><strong>Jalur Pendaftaran:</strong><br>{{ ucfirst($pendaftaran->jalur ?? '-') }}</div>
                </div>
            </div>
        </div>

        {{-- Data Keluarga --}}
        <div class="card" style="margin-bottom:18px;">
            <div class="card-header">
                <span class="card-title">👨‍👩‍👧 Data Keluarga</span>
            </div>
            <div class="card-body" style="padding:20px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px 24px;">
                    <div><strong>No. KK:</strong><br>{{ $pendaftaran->no_kk ?? '-' }}</div>
                    <div><strong>Nama Kepala Keluarga:</strong><br>{{ $pendaftaran->nama_kepala_keluarga ?? '-' }}</div>
                    <div><strong>Status Kepemilikan Rumah:</strong><br>{{ $pendaftaran->status_kepemilikan_rumah ?? '-' }}</div>
                    <div><strong>Bin/Binti (Nama Orang Tua):</strong><br>{{ $pendaftaran->nama_orang_tua ?? '-' }}</div>
                </div>
            </div>
        </div>

        {{-- Data Ayah --}}
        <div class="card" style="margin-bottom:18px;">
            <div class="card-header">
                <span class="card-title">👨 Data Ayah</span>
            </div>
            <div class="card-body" style="padding:20px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px 24px;">
                    <div><strong>Nama Ayah:</strong><br>{{ $pendaftaran->nama_ayah ?? '-' }}</div>
                    <div><strong>NIK Ayah:</strong><br>{{ $pendaftaran->nik_ayah ?? '-' }}</div>
                    <div><strong>Status Ayah:</strong><br>{{ $pendaftaran->status_ayah ?? '-' }}</div>
                    <div><strong>Pendidikan Ayah:</strong><br>{{ $pendaftaran->pendidikan_ayah ?? '-' }}</div>
                    <div><strong>Pekerjaan Ayah:</strong><br>{{ $pendaftaran->pekerjaan_ayah ?? '-' }}</div>
                    <div><strong>Penghasilan Ayah:</strong><br>{{ $pendaftaran->penghasilan_ayah ?? '-' }}</div>
                    <div><strong>No. HP Ayah:</strong><br>{{ $pendaftaran->no_hp_ayah ?? '-' }}</div>
                </div>
            </div>
        </div>

        {{-- Data Ibu --}}
        <div class="card" style="margin-bottom:18px;">
            <div class="card-header">
                <span class="card-title">👩 Data Ibu</span>
            </div>
            <div class="card-body" style="padding:20px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px 24px;">
                    <div><strong>Nama Ibu:</strong><br>{{ $pendaftaran->nama_ibu ?? '-' }}</div>
                    <div><strong>NIK Ibu:</strong><br>{{ $pendaftaran->nik_ibu ?? '-' }}</div>
                    <div><strong>Status Ibu:</strong><br>{{ $pendaftaran->status_ibu ?? '-' }}</div>
                    <div><strong>Pendidikan Ibu:</strong><br>{{ $pendaftaran->pendidikan_ibu ?? '-' }}</div>
                    <div><strong>Pekerjaan Ibu:</strong><br>{{ $pendaftaran->pekerjaan_ibu ?? '-' }}</div>
                    <div><strong>Penghasilan Ibu:</strong><br>{{ $pendaftaran->penghasilan_ibu ?? '-' }}</div>
                    <div><strong>No. HP Ibu:</strong><br>{{ $pendaftaran->no_hp_ibu ?? '-' }}</div>
                </div>
            </div>
        </div>

        {{-- Data Wali --}}
        <div class="card" style="margin-bottom:18px;">
            <div class="card-header">
                <span class="card-title">🧑 Data Wali</span>
            </div>
            <div class="card-body" style="padding:20px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px 24px;">
                    <div><strong>Nama Wali:</strong><br>{{ $pendaftaran->nama_wali ?? '-' }}</div>
                    <div><strong>NIK Wali:</strong><br>{{ $pendaftaran->nik_wali ?? '-' }}</div>
                    <div><strong>Status Wali:</strong><br>{{ $pendaftaran->status_wali ?? '-' }}</div>
                    <div><strong>Pendidikan Wali:</strong><br>{{ $pendaftaran->pendidikan_wali ?? '-' }}</div>
                    <div><strong>Pekerjaan Wali:</strong><br>{{ $pendaftaran->pekerjaan_wali ?? '-' }}</div>
                    <div><strong>Penghasilan Wali:</strong><br>{{ $pendaftaran->penghasilan_wali ?? '-' }}</div>
                    <div><strong>No. HP Wali:</strong><br>{{ $pendaftaran->no_hp_wali ?? '-' }}</div>
                </div>
            </div>
        </div>

        {{-- Bantuan --}}
        <div class="card" style="margin-bottom:18px;">
            <div class="card-header">
                <span class="card-title">🎁 Program Bantuan</span>
            </div>
            <div class="card-body" style="padding:20px;">
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px 24px;">
                    <div><strong>No. KKS:</strong><br>{{ $pendaftaran->no_kks ?? '-' }}</div>
                    <div><strong>No. PKH:</strong><br>{{ $pendaftaran->no_pkh ?? '-' }}</div>
                    <div><strong>No. KIP:</strong><br>{{ $pendaftaran->no_kip ?? '-' }}</div>
                </div>
            </div>
        </div>

        {{-- Berkas --}}
        <div class="card" style="margin-bottom:18px;">
            <div class="card-header">
                <span class="card-title">📁 Berkas Pendaftaran</span>
            </div>
            <div class="card-body" style="padding:20px;">
                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:12px;">
                    <div>
                        <strong>File NISN:</strong><br>
                        @if($pendaftaran->nisn_file)
                            <a href="{{ asset('storage/'.$pendaftaran->nisn_file) }}" target="_blank">Lihat Berkas</a>
                        @else
                            -
                        @endif
                    </div>
                    <div>
                        <strong>Kartu Keluarga:</strong><br>
                        @if($pendaftaran->kartu_keluarga)
                            <a href="{{ asset('storage/'.$pendaftaran->kartu_keluarga) }}" target="_blank">Lihat Berkas</a>
                        @else
                            -
                        @endif
                    </div>
                    <div>
                        <strong>Akta Kelahiran:</strong><br>
                        @if($pendaftaran->akta_kelahiran)
                            <a href="{{ asset('storage/'.$pendaftaran->akta_kelahiran) }}" target="_blank">Lihat Berkas</a>
                        @else
                            -
                        @endif
                    </div>
                    <div>
                        <strong>Foto:</strong><br>
                        @if($pendaftaran->foto)
                            <a href="{{ asset('storage/'.$pendaftaran->foto) }}" target="_blank">Lihat Berkas</a>
                        @else
                            -
                        @endif
                    </div>
                    <div>
                        <strong>Ijazah / SKL:</strong><br>
                        @if($pendaftaran->ijazah)
                            <a href="{{ asset('storage/'.$pendaftaran->ijazah) }}" target="_blank">Lihat Berkas</a>
                        @else
                            -
                        @endif
                    </div>
                </div>
            </div>
        </div>

        
        <div style="margin-top:20px;padding:20px;background:#fff;border-radius:12px;">
            <h4>Catatan Perbaikan Berkas</h4>

            <form action="{{ route('admin.pendaftaran.mintaPerbaikan', $data->id) }}" method="POST">
                @csrf
                <div style="margin-bottom:12px;">
                    <label for="catatan_admin">Catatan Admin</label>
                    <textarea name="catatan_admin" id="catatan_admin" rows="4" class="form-control" required>{{ old('catatan_admin', $data->catatan_admin) }}</textarea>
                </div>

                <button type="submit" class="btn btn-warning">
                    Minta Perbaikan
                </button>
            </form>
        </div>

        {{-- Status --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">📌 Status Pendaftaran</span>
            </div>
            <div class="card-body" style="padding:20px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px 24px;">
                    <div><strong>Status:</strong><br>{{ ucfirst(str_replace('_', ' ', $pendaftaran->status ?? '-')) }}</div>
                    <div><strong>Predikat:</strong><br>{{ $pendaftaran->predikat ?? '-' }}</div>
                    <div><strong>ID Kelas:</strong><br>{{ $pendaftaran->id_kelas ?? '-' }}</div>
                    <div><strong>Berkas Lengkap:</strong><br>{{ $pendaftaran->berkas_lengkap ? 'Ya' : 'Belum' }}</div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection