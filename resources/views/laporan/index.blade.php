@extends('layouts.app')

@section('title', 'Laporan')
@section('page-title', 'Laporan')

@section('content')

<div class="section-header">
    <div>
        <h2 style="font-size:16px;font-weight:700;">Laporan</h2>
        <p style="font-size:12px;color:var(--text-light);">Export laporan data PPDB dalam format PDF dan Excel</p>
    </div>
</div>

{{-- Statistik --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;">
    <div class="card" style="text-align:center;padding:20px;">
        <div style="font-size:28px;font-weight:800;color:var(--primary);">{{ $totalPendaftar }}</div>
        <div style="font-size:12px;color:var(--text-light);margin-top:4px;">Total Pendaftar</div>
    </div>
    <div class="card" style="text-align:center;padding:20px;">
        <div style="font-size:28px;font-weight:800;color:var(--success);">{{ $totalLulus }}</div>
        <div style="font-size:12px;color:var(--text-light);margin-top:4px;">✅ Lulus</div>
    </div>
    <div class="card" style="text-align:center;padding:20px;">
        <div style="font-size:28px;font-weight:800;color:#e05454;">{{ $totalDitolak }}</div>
        <div style="font-size:12px;color:var(--text-light);margin-top:4px;">❌ Tidak Lulus</div>
    </div>
    <div class="card" style="text-align:center;padding:20px;">
        <div style="font-size:28px;font-weight:800;color:#f59e0b;">{{ $totalPending }}</div>
        <div style="font-size:12px;color:var(--text-light);margin-top:4px;">⏳ Pending</div>
    </div>
</div>

{{-- Laporan Cards --}}
<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:20px;">

    {{-- Laporan Pendaftar --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">📋 Laporan Data Pendaftar</span>
        </div>
        <div class="card-body">
            <p style="font-size:12px;color:var(--text-light);margin-bottom:16px;">
                Berisi seluruh data pendaftar PPDB beserta status pendaftaran.
                Total <strong>{{ $totalPendaftar }}</strong> pendaftar.
            </p>
            <div style="display:flex;gap:8px;">
                <a href="{{ route('laporan.pdf.pendaftar') }}" class="btn btn-danger btn-sm" style="flex:1;justify-content:center;">
                    📄 Export PDF
                </a>
                <a href="{{ route('laporan.excel.pendaftar') }}" class="btn btn-success btn-sm" style="flex:1;justify-content:center;">
                    📊 Export Excel
                </a>
            </div>
        </div>
    </div>

    {{-- Laporan Klasifikasi --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">🧮 Laporan Hasil Klasifikasi</span>
        </div>
        <div class="card-body">
            <p style="font-size:12px;color:var(--text-light);margin-bottom:16px;">
                Hasil klasifikasi Naive Bayes. Unggul: <strong>{{ $totalUnggul }}</strong>,
                Baik: <strong>{{ $totalBaik }}</strong>, Cukup: <strong>{{ $totalCukup }}</strong>.
            </p>
            <div style="display:flex;gap:8px;">
                <a href="{{ route('laporan.pdf.klasifikasi') }}" class="btn btn-danger btn-sm" style="flex:1;justify-content:center;">
                    📄 Export PDF
                </a>
                <a href="{{ route('laporan.excel.klasifikasi') }}" class="btn btn-success btn-sm" style="flex:1;justify-content:center;">
                    📊 Export Excel
                </a>
            </div>
        </div>
    </div>

    {{-- Laporan Pembagian Kelas --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">🏫 Laporan Pembagian Kelas</span>
        </div>
        <div class="card-body">
            <p style="font-size:12px;color:var(--text-light);margin-bottom:16px;">
                Data pembagian kelas siswa lulus menggunakan metode Stratified.
                Total <strong>{{ $totalLulus }}</strong> siswa lulus.
            </p>
            <div style="display:flex;gap:8px;">
                <a href="{{ route('laporan.pdf.pembagian') }}" class="btn btn-danger btn-sm" style="flex:1;justify-content:center;">
                    📄 Export PDF
                </a>
                <a href="{{ route('laporan.excel.pembagian') }}" class="btn btn-success btn-sm" style="flex:1;justify-content:center;">
                    📊 Export Excel
                </a>
            </div>
        </div>
    </div>

    {{-- Laporan Nilai Tes --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">📝 Laporan Rekap Nilai Tes</span>
        </div>
        <div class="card-body">
            <p style="font-size:12px;color:var(--text-light);margin-bottom:16px;">
                Rekap seluruh nilai tes seleksi siswa beserta total nilai masing-masing.
            </p>
            <div style="display:flex;gap:8px;">
                <a href="{{ route('laporan.pdf.nilai') }}" class="btn btn-danger btn-sm" style="flex:1;justify-content:center;">
                    📄 Export PDF
                </a>
                <a href="{{ route('laporan.excel.nilai') }}" class="btn btn-success btn-sm" style="flex:1;justify-content:center;">
                    📊 Export Excel
                </a>
            </div>
        </div>
    </div>

</div>

@endsection