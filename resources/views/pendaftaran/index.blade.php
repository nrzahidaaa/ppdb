@extends('layouts.app')

@section('title', 'Pendaftaran')
@section('page-title', 'Pendaftaran')

@section('content')

<style>
.pagination {
    display: flex;
    align-items: center;
    gap: 4px;
    list-style: none;
    margin: 0;
    padding: 0;
}
.pagination li a,
.pagination li span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    color: var(--primary);
    border: 1px solid var(--border);
    text-decoration: none;
    transition: .2s;
}
.pagination li.active span {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}
.pagination li a:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}
.pagination li.disabled span {
    color: #ccc;
    border-color: #eee;
    cursor: not-allowed;
}
</style>

{{-- Header --}}
<div class="section-header">
    <div>
        <h2 style="font-size:16px;font-weight:700;">Daftar Pendaftar</h2>
        <p style="font-size:12px;color:var(--text-light);">Kelola semua data pendaftaran siswa baru</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom:16px;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger" style="margin-bottom:16px;">
        {{ session('error') }}
    </div>
@endif

<div style="display:flex;gap:10px;align-items:center;margin-bottom:20px;flex-wrap:wrap;">
    <form method="POST" action="{{ route('pendaftaran.import') }}" enctype="multipart/form-data" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
        @csrf
        <input type="file" name="file" accept=".xlsx,.xls" class="form-control" style="width:220px;">
        <button type="submit" class="btn btn-secondary">📥 Import Siswa</button>
    </form>
    
<a href="{{ route('pendaftaran.template') }}" class="btn btn-success">
    Download Template
</a>

    <a href="{{ route('pendaftaran.create') }}" class="btn btn-primary">➕ Tambah Pendaftar</a>
</div>

@if($notifRevisi > 0)
    <div style="margin-bottom:16px;padding:12px 16px;background:#e8f4fd;border:1px solid #b6e0fe;border-radius:10px;color:#0c5460;font-weight:600;">
        Ada {{ $notifRevisi }} data yang sudah diperbaiki siswa dan menunggu verifikasi ulang.
    </div>
@endif

{{-- Filters --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-body" style="padding:16px 20px;">
        <form method="GET" action="{{ route('pendaftaran.index') }}" class="d-flex gap-2 mb-3">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / no pendaftaran / NISN" class="form-control">

    <select name="status" class="form-control">
        <option value="">Semua Status</option>
        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="lulus" {{ request('status') == 'lulus' ? 'selected' : '' }}>Lulus</option>
        <option value="tidak_lulus" {{ request('status') == 'tidak_lulus' ? 'selected' : '' }}>Tidak Lulus</option>
    </select>

    <select name="status_berkas" class="form-control">
        <option value="">Semua Status Berkas</option>
        <option value="pending" {{ request('status_berkas') == 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="perlu_perbaikan" {{ request('status_berkas') == 'perlu_perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
        <option value="sudah_diperbaiki" {{ request('status_berkas') == 'sudah_diperbaiki' ? 'selected' : '' }}>Sudah Diperbaiki</option>
        <option value="diterima" {{ request('status_berkas') == 'diterima' ? 'selected' : '' }}>Diterima</option>
        <option value="ditolak" {{ request('status_berkas') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
    </select>

    <button type="submit" class="btn btn-primary">Filter</button>
</form>
    </div>
</div>

@if(isset($tahunAjaranAktif) && $tahunAjaranAktif)
    <div class="alert alert-info" style="margin-bottom:16px;">
        Tahun ajaran aktif: <strong>{{ $tahunAjaranAktif->nama_tahun_ajaran }}</strong>
    </div>
@endif

{{-- Table --}}
<div class="card">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No. Daftar</th>
                    <th>Nama Lengkap</th>
                    <th>NISN</th>
                    <th>Tempat Tanggal Lahir</th>
                    <th>Nama Orang Tua</th>
                    <th>Asal Sekolah</th>
                    <th>Alamat</th>
                    <th>Jalur</th>
                    <th>Berkas</th>
                    <th>Status</th>
                    <th>Status Berkas</th>
                    <th>Waktu Revisi</th>
                    <th>Aksi</th>
                </tr>
            </thead>

 <tbody>
    @forelse($pendaftaran ?? [] as $p)
    <tr>
        <td style="font-weight:600;color:var(--primary);">
            {{ $p->nomor_pendaftaran }}
        </td>

        <td>
            <div class="d-flex align-items-center">
                <div class="avatar me-2">
                    {{ strtoupper(substr($p->nama, 0, 2)) }}
                </div>
                {{ $p->nama }}
            </div>
        </td>

        <td>{{ $p->nisn }}</td>

        <td>
            {{ $p->tempat_lahir }},
            {{ $p->tanggal_lahir ? $p->tanggal_lahir->format('d/m/Y') : '-' }}
        </td>

        <td>{{ $p->nama_orang_tua }}</td>
        <td>{{ $p->asal_sekolah }}</td>
        <td>{{ $p->alamat }}</td>

        <td>
            <span class="badge {{ strtolower($p->jalur ?? '') == 'prestasi' ? 'badge-warning' : 'badge-secondary' }}">
                {{ ucfirst($p->jalur ?? '-') }}
            </span>
        </td>

        <td>
            @php
                $berkasLengkap = !empty($p->nisn_file)
                    && !empty($p->kartu_keluarga)
                    && !empty($p->akta_kelahiran)
                    && !empty($p->foto)
                    && !empty($p->ijazah);
            @endphp

            @if($berkasLengkap)
                <span class="badge badge-success">Sudah upload</span>
            @else
                <span class="badge badge-warning">Belum upload</span>
            @endif
        </td>

        <td>
            @if($p->status_berkas === 'perlu_perbaikan')
                <span class="badge badge-warning">Perlu Perbaikan</span>
            @elseif($p->status_berkas === 'sudah_diperbaiki')
                <span class="badge badge-info">Sudah Diperbaiki</span>
            @else
                <span class="badge badge-secondary">Pending</span>
            @endif
        </td>


                    <td>
                        <form method="POST" action="{{ route('pendaftaran.updateStatus', $p->id) }}" style="display:flex;gap:6px;align-items:center;flex-wrap:wrap;">
                            @csrf
                            @method('PATCH')

                            <select
                                name="status"
                                class="form-control form-select status-select"
                                style="width:155px;font-size:11px;padding:5px 10px;"
                                data-target="catatan-{{ $p->id }}"
                            >
                                <option value="waiting_proses" {{ $p->status == 'waiting_proses' ? 'selected' : '' }}>⏳ Waiting Proses</option>
                                <option value="pending" {{ $p->status == 'pending' ? 'selected' : '' }}>⚠️ Pending</option>
                                <option value="verifikasi" {{ $p->status == 'verifikasi' ? 'selected' : '' }}>🔵 Verifikasi</option>
                                <option value="lulus" {{ $p->status == 'lulus' ? 'selected' : '' }}>✅ Lulus</option>
                                <option value="ditolak" {{ $p->status == 'ditolak' ? 'selected' : '' }}>❌ Tidak Lulus</option>
                            </select>

                            <div
                                id="catatan-{{ $p->id }}"
                                class="{{ $p->status === 'pending' ? '' : 'd-none' }}"
                                style="width:100%; margin-top:6px;"
                            >
                                <textarea
                                    name="catatan_admin"
                                    class="form-control"
                                    rows="2"
                                    placeholder="Masukkan catatan untuk siswa..."
                                    style="font-size:11px;"
                                >{{ old('catatan_admin', $p->catatan_admin) }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary btn-sm" style="margin-top:6px;">Simpan</button>
                        </form>
                    </td>

                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('pendaftaran.show', $p->id) }}" class="btn btn-outline btn-sm">👁</a>

                            @if(!empty($p->nisn_file) || !empty($p->kartu_keluarga) || !empty($p->akta_kelahiran) || !empty($p->foto) || !empty($p->ijazah))
                                <a href="{{ route('pendaftaran.berkas', $p->id) }}" class="btn btn-secondary btn-sm">📁</a>
                            @endif

                            <a href="{{ route('pendaftaran.edit', $p->id) }}" class="btn btn-secondary btn-sm">✏️</a>

                            <form method="POST" action="{{ route('pendaftaran.destroy', $p->id) }}" onsubmit="return confirm('Yakin hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">🗑</button>
                            </form>
                        </div>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="11" class="text-center text-muted">
                        Tidak ada data pendaftar
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="pagination-wrapper">
        <span class="pagination-info">
            @if(isset($pendaftaran) && method_exists($pendaftaran, 'total'))
                Menampilkan {{ $pendaftaran->firstItem() }}–{{ $pendaftaran->lastItem() }} dari {{ $pendaftaran->total() }} data
            @else
                Data tidak tersedia
            @endif
        </span>

        @if(isset($pendaftaran) && method_exists($pendaftaran, 'links'))
            {{ $pendaftaran->links() }}
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selects = document.querySelectorAll('.status-select');

    selects.forEach(select => {
        function toggleCatatan() {
            const targetId = select.dataset.target;
            const catatanBox = document.getElementById(targetId);

            if (!catatanBox) return;

            if (select.value === 'pending') {
                catatanBox.classList.remove('d-none');
            } else {
                catatanBox.classList.add('d-none');
            }
        }

        select.addEventListener('change', toggleCatatan);
        toggleCatatan();
    });
});
</script>

@endsection