@extends('layouts.app')

@section('title', 'Nilai Tes')
@section('page-title', 'Nilai Tes')

@section('content')

<div class="section-header">
    <div>
        <h2 style="font-size:16px;font-weight:700;">Data Nilai Tes</h2>
        <p style="font-size:12px;color:var(--text-light);">Import dan kelola nilai tes seleksi siswa</p>
    </div>

    {{-- Form Import --}}
    <form method="POST" action="{{ route('nilai-tes.import') }}" enctype="multipart/form-data" style="display:flex;gap:8px;align-items:center;">
        @csrf
        <input type="file" name="file" accept=".xlsx,.xls" class="form-control" style="width:220px;">
        <button type="submit" class="btn btn-secondary">📥 Import Excel</button>
    </form>
</div>

<div class="card">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Bhs. Indonesia</th>
                    <th>Matematika</th>
                    <th>IPA</th>
                    <th>IPS</th>
                    <th>Agama</th>
                    <th>Do'a Iftitah</th>
                    <th>Tahiyat Awal</th>
                    <th>Qunut</th>
                    <th>Baca Al-Qur'an</th>
                    <th>Fatihah 4</th>
                    <th>Surah Pendek</th>
                    <th>Do'a</th>
                    <th>Menulis</th>
                    <th>Tgl. Input</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
    @forelse($nilaiTes as $i => $nilai)
    <tr>
        <td>{{ $nilaiTes->firstItem() + $i }}</td>
        <td>
            <div style="font-weight:600;">{{ $nilai->siswa->nama ?? '-' }}</div>
            <div style="font-size:10px;color:var(--text-light);">{{ $nilai->pendaftaran->nisn ?? '-' }}</div>
        </td>
        <td>{{ $nilai->bhs_indonesia }}</td>
        <td>{{ $nilai->matematika }}</td>
        <td>{{ $nilai->ipa }}</td>
        <td>{{ $nilai->ips }}</td>
        <td>{{ $nilai->agama }}</td>
        <td>{{ $nilai->doa_iftitah }}</td>
        <td>{{ $nilai->tahiyat_awal }}</td>
        <td>{{ $nilai->qunut }}</td>
        <td>{{ $nilai->membaca_al_quran }}</td>
        <td>{{ $nilai->fatihah_4 }}</td>
        <td>{{ $nilai->surah_pendek }}</td>
        <td>{{ $nilai->doa }}</td>
        <td>{{ $nilai->menulis }}</td>
        <td>{{ $nilai->tanggal_input?->format('d/m/Y') }}</td>
        <td>
                        <form method="POST" action="{{ route('nilai-tes.destroy', $nilai->id_nilai) }}" onsubmit="return confirm('Yakin hapus data ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">🗑</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="14" style="text-align:center;padding:40px;color:var(--text-light);">
                        Belum ada data nilai. Import file Excel untuk menambahkan data.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper">
        <span class="pagination-info">
            Menampilkan {{ $nilaiTes->firstItem() ?? 0 }}–{{ $nilaiTes->lastItem() ?? 0 }} dari {{ $nilaiTes->total() }} data
        </span>
        {{ $nilaiTes->links() }}
    </div>
</div>

@endsection