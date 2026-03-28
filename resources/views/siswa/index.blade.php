@extends('layouts.app')

@section('title', 'Data Siswa')
@section('page-title', 'Data Siswa')

@section('content')

<div class="section-header">
    <div>
        <h2 style="font-size:16px;font-weight:700;">Data Siswa</h2>
        <p style="font-size:12px;color:var(--text-light);">Daftar siswa yang telah dinyatakan lulus seleksi</p>
    </div>
</div>

{{-- Filter --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-body" style="padding:16px 20px;">
        <form method="GET" action="{{ route('siswa.index') }}" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;">
            <div style="flex:1;min-width:200px;">
                <label class="form-label" style="margin-bottom:5px;">Cari Nama / NISN</label>
                <input type="text" name="search" class="form-control" placeholder="Cari..." value="{{ request('search') }}">
            </div>
            <div style="min-width:160px;">
                <!-- <label class="form-label" style="margin-bottom:5px;">Jurusan</label>
                <select name="jurusan" class="form-control form-select">
                    <option value="">Semua Jurusan</option>
                    <option value="MIPA"   {{ request('jurusan')=='MIPA'   ? 'selected' : '' }}>MIPA</option>
                    <option value="IPS"    {{ request('jurusan')=='IPS'    ? 'selected' : '' }}>IPS</option>
                    <option value="Bahasa" {{ request('jurusan')=='Bahasa' ? 'selected' : '' }}>Bahasa</option>
                </select> -->
            </div>
            <button type="submit" class="btn btn-primary">🔍 Filter</button>
            <a href="{{ route('siswa.index') }}" class="btn btn-outline">Reset</a>
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
                    <th>Asal Sekolah</th>
                    <!-- <th>Jurusan</th> -->
                    <th>Nilai Rata-rata</th>
                    <th>Kelas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswa as $i => $s)
                <tr>
                    <td style="font-weight:600;color:var(--primary);">{{ $s->nomor_pendaftaran }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div class="avatar-sm" style="background:var(--primary);">{{ strtoupper(substr($s->nama, 0, 2)) }}</div>
                            <div>
                                <div style="font-weight:600;">{{ $s->nama }}</div>
                                <div style="font-size:10px;color:var(--text-light);">{{ $s->tempat_lahir }}, {{ $s->tanggal_lahir?->format('d/m/Y') }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $s->nisn }}</td>
                    <td>{{ $s->asal_sekolah }}</td>
                    <!-- <td>
                        @if($s->pilihan_jurusan === 'MIPA') <span class="badge badge-primary">MIPA</span>
                        @elseif($s->pilihan_jurusan === 'IPS') <span class="badge badge-success">IPS</span>
                        @else <span class="badge badge-secondary">Bahasa</span>
                        @endif
                    </td> -->
                    <td style="font-weight:600;">{{ $s->nilai_rata_rata }}</td>
                    <td>
                        @if($s->kelas)
                            <span class="badge badge-primary">{{ $s->kelas->nama_kelas }}</span>
                        @else
                            <span style="color:var(--text-light);font-size:11px;">Belum ditempatkan</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;color:var(--text-light);padding:32px;">
                        Belum ada data siswa lulus
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="pagination-wrapper">
        <span class="pagination-info">
            Menampilkan {{ $siswa->firstItem() }}–{{ $siswa->lastItem() }} dari {{ $siswa->total() }} data
        </span>
        {{ $siswa->links('pagination::bootstrap-5') }}
    </div>
</div>

@endsection