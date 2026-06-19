@extends('layouts.app')

@section('title', 'Pembagian Kelas')
@section('page-title', 'Pembagian Kelas')

@section('content')

<div class="section-header">
    <h2>Pembagian Kelas</h2>
</div>



<form method="POST" action="{{ route('klasifikasi.prosesKelas') }}" style="margin-bottom: 20px;">
    @csrf
    <button type="submit" class="btn btn-primary">🚀 Proses Pembagian Kelas</button>
</form>

@php
    $jumlahKelas = $kelas->count();

    $totalUnggul = $kelas->sum('unggul_count');
    $totalBaik   = $kelas->sum('baik_count');
    $totalCukup  = $kelas->sum('cukup_count');

    $predikatData = [
        'Unggul' => $totalUnggul,
        'Baik'   => $totalBaik,
        'Cukup'  => $totalCukup,
    ];
@endphp

<div class="card" style="padding:20px;margin-bottom:24px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <div>
            <h3 style="font-size:16px;font-weight:800;margin:0;">📊 Proses Perhitungan Stratified Assignment</h3>
            <p style="font-size:12px;color:var(--text-light);margin:4px 0 0;">
                Menampilkan proses pembagian siswa berdasarkan predikat ke setiap kelas.
            </p>
        </div>
    </div>

    <h4 style="font-size:14px;font-weight:700;margin-bottom:10px;">1. Hasil Klasifikasi Predikat</h4>
    <table class="data-table" width="100%" style="margin-bottom:20px;">
        <thead>
            <tr>
                <th>Predikat</th>
                <th>Jumlah Siswa</th>
            </tr>
        </thead>
        <tbody>
            @foreach($predikatData as $predikat => $jumlah)
                <tr>
                    <td>{{ $predikat }}</td>
                    <td>{{ $jumlah }}</td>
                </tr>
            @endforeach
            <tr>
                <th>Total</th>
                <th>{{ array_sum($predikatData) }}</th>
            </tr>
        </tbody>
    </table>

    <h4 style="font-size:14px;font-weight:700;margin-bottom:10px;">2. Perhitungan Kuota Dasar dan Sisa</h4>
    <table class="data-table" width="100%" style="margin-bottom:20px;">
        <thead>
            <tr>
                <th>Predikat</th>
                <th>Jumlah Siswa</th>
                <th>Jumlah Kelas</th>
                <th>Kuota Dasar</th>
                <th>Sisa</th>
                <th>Perhitungan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($predikatData as $predikat => $jumlah)
                @php
                    $kuotaDasar = $jumlahKelas > 0 ? floor($jumlah / $jumlahKelas) : 0;
                    $sisa = $jumlahKelas > 0 ? $jumlah - ($kuotaDasar * $jumlahKelas) : 0;
                @endphp
                <tr>
                    <td>{{ $predikat }}</td>
                    <td>{{ $jumlah }}</td>
                    <td>{{ $jumlahKelas }}</td>
                    <td>{{ $kuotaDasar }}</td>
                    <td>{{ $sisa }}</td>
                    <td>{{ $jumlah }} ÷ {{ $jumlahKelas }} = {{ $kuotaDasar }} sisa {{ $sisa }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4 style="font-size:14px;font-weight:700;margin-bottom:10px;">3. Hasil Distribusi per Kelas</h4>
    <table class="data-table" width="100%">
        <thead>
            <tr>
                <th>Kelas</th>
                <th>Unggul</th>
                <th>Baik</th>
                <th>Cukup</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kelas as $k)
                <tr>
                    <td>{{ $k->nama_kelas }}</td>
                    <td>{{ $k->unggul_count }}</td>
                    <td>{{ $k->baik_count }}</td>
                    <td>{{ $k->cukup_count }}</td>
                    <td>{{ $k->total_siswa }}</td>
                </tr>
            @endforeach
            <tr>
                <th>Total</th>
                <th>{{ $totalUnggul }}</th>
                <th>{{ $totalBaik }}</th>
                <th>{{ $totalCukup }}</th>
                <th>{{ $kelas->sum('total_siswa') }}</th>
            </tr>
        </tbody>
    </table>

    <div style="margin-top:16px;background:#f8fafc;border-radius:12px;padding:14px;font-size:13px;color:var(--text);">
        <strong>Keterangan:</strong><br>
        Kuota dasar diperoleh dari jumlah siswa pada setiap predikat dibagi jumlah kelas.
        Jika terdapat sisa pembagian, siswa sisa dialokasikan ke kelas yang masih membutuhkan tambahan
        agar total siswa antar kelas tetap seimbang.
    </div>
</div>

<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin:24px 0;">
    @foreach($kelas as $k)
        @php
            $sisaKuota = max(($k->kuota ?? 0) - ($k->total_siswa ?? 0), 0);
        @endphp

        <div class="card" style="padding:18px;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
                <div style="font-size:18px;font-weight:800;color:var(--primary);">
                    {{ $k->nama_kelas }}
                </div>
                <div style="font-size:12px;color:var(--text-light);">
                    {{ $k->total_siswa }} / {{ $k->kuota }}
                </div>
            </div>

            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:10px;margin-bottom:12px;">
                <div style="background:#f8fafc;border-radius:10px;padding:10px;text-align:center;">
                    <div style="font-size:20px;font-weight:800;color:var(--text);">{{ $k->total_siswa }}</div>
                    <div style="font-size:11px;color:var(--text-light);">Total Siswa</div>
                </div>
                <div style="background:#f8fafc;border-radius:10px;padding:10px;text-align:center;">
                    <div style="font-size:20px;font-weight:800;color:#f59e0b;">{{ $sisaKuota }}</div>
                    <div style="font-size:11px;color:var(--text-light);">Sisa Kuota</div>
                </div>
            </div>

            <div style="display:flex;flex-wrap:wrap;gap:8px;">
                <span class="badge" style="background:#eef9d7;color:#597001;">🏆 Unggul: {{ $k->unggul_count }}</span>
                <span class="badge" style="background:#e6f7f5;color:#1f8f87;">⭐ Baik: {{ $k->baik_count }}</span>
                <span class="badge" style="background:#eaf1ff;color:#33528A;">📝 Cukup: {{ $k->cukup_count }}</span>
            </div>
        </div>
    @endforeach
</div>
<hr>

@forelse($kelas as $k)
    <div class="card" style="margin-bottom:20px;">
        <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
            <strong>{{ $k->nama_kelas }}</strong>
            <span style="font-size:12px;color:gray;">
                ({{ $k->siswa->count() }} / {{ $k->kuota }})
            </span>
        </div>

        <div class="card-body">
            @if($k->siswa->count() > 0)
                <table class="data-table" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Predikat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($k->siswa as $i => $s)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $s->nama }}</td>
                                <td>
                                    <span class="badge 
                                        {{ $s->predikat == 'Unggul' ? 'badge-success' : ($s->predikat == 'Baik' ? 'badge-warning' : 'badge-secondary') }}">
                                        {{ $s->predikat }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="font-size:12px;color:gray;margin:0;">Belum ada siswa</p>
            @endif
        </div>
    </div>
@empty
    <div class="alert alert-info">Belum ada data kelas.</div>
@endforelse

@endsection