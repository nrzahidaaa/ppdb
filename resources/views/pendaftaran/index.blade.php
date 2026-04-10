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

<div style="display:flex;gap:10px;align-items:center;">
    <form method="POST" action="{{ route('pendaftaran.import') }}" enctype="multipart/form-data" style="display:flex;gap:8px;align-items:center;">
        @csrf
        <input type="file" name="file" accept=".xlsx,.xls" class="form-control" style="width:220px;">
        <button type="submit" class="btn btn-secondary">📥 Import Siswa</button>
    </form>
    <a href="{{ route('pendaftaran.create') }}" class="btn btn-primary">➕ Tambah Pendaftar</a>
</div>

{{-- Filters --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-body" style="padding:16px 20px;">
        <form method="GET" action="{{ route('pendaftaran.index') }}" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;">
            <div style="flex:1;min-width:200px;">
                <label class="form-label" style="margin-bottom:5px;">Cari Nama / No. Pendaftaran</label>
                <input type="text" name="search" class="form-control" placeholder="Cari..." value="{{ request('search') }}">
            </div>

            <div style="min-width:160px;">
                <label class="form-label">Status</label>
                <select name="status" class="form-control form-select">
                    <option value="">Semua Status</option>
                    <option value="pending"     {{ request('status')=='pending'     ? 'selected' : '' }}>Pending</option>
                    <option value="verifikasi"  {{ request('status')=='verifikasi'  ? 'selected' : '' }}>Verifikasi</option>
                    <option value="lulus"       {{ request('status')=='lulus'       ? 'selected' : '' }}>Lulus</option>
                    <option value="ditolak"     {{ request('status')=='ditolak'     ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">🔍 Filter</button>
            <a href="{{ route('pendaftaran.index') }}" class="btn btn-outline">Reset</a>
        </form>
    </div>
</div>

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

                    <td>
                        {{ $p->nisn }}
                    </td>

                    <td>
                        {{ $p->tempat_lahir }},
                        {{ $p->tanggal_lahir ? $p->tanggal_lahir->format('d/m/Y') : '-' }}
                    </td>

                    <td>{{ $p->nama_orang_tua }}</td>
                    <td>{{ $p->asal_sekolah }}</td>
                    <td>{{ $p->alamat }}</td>

                    <td>
                        <span class="badge {{ $p->jalur == 'prestasi' ? 'badge-warning' : 'badge-secondary' }}">
                            {{ ucfirst($p->jalur) }}
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
                        <form method="POST" action="{{ route('pendaftaran.updateStatus', $p->id) }}">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="form-control form-select" style="width:130px;font-size:11px;padding:5px 10px;" onchange="this.form.submit()">
                                <option value="pending"    {{ $p->status=='pending'    ? 'selected' : '' }}>⏳ Pending</option>
                                <option value="verifikasi" {{ $p->status=='verifikasi' ? 'selected' : '' }}>🔵 Verifikasi</option>
                                <option value="lulus"      {{ $p->status=='lulus'      ? 'selected' : '' }}>✅ Lulus</option>
                                <option value="ditolak"    {{ $p->status=='ditolak'    ? 'selected' : '' }}>❌ Ditolak</option>
                            </select>
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
                    <td colspan="10" class="text-center text-muted">
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

@endsection
