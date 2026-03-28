@extends('layouts.app')

@section('title', 'Proses Klasifikasi')
@section('page-title', 'Proses Klasifikasi')

@section('content')

<div class="section-header">
    <div>
        <h2 style="font-size:16px;font-weight:700;">Proses Klasifikasi</h2>
        <p style="font-size:12px;color:var(--text-light);">Klasifikasi otomatis siswa berdasarkan nilai rata-rata</p>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success" style="margin-bottom:16px;">✅ {{ session('success') }}</div>
@endif

{{-- Statistik --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;">
    <div class="card" style="text-align:center;padding:20px;">
        <div style="font-size:32px;font-weight:800;color:var(--primary);">{{ $pending }}</div>
        <div style="font-size:12px;color:var(--text-light);margin-top:4px;">⏳ Menunggu Klasifikasi</div>
    </div>
    <div class="card" style="text-align:center;padding:20px;">
        <div style="font-size:32px;font-weight:800;color:var(--success);">{{ $lulus }}</div>
        <div style="font-size:12px;color:var(--text-light);margin-top:4px;">✅ Dinyatakan Lulus</div>
    </div>
    <div class="card" style="text-align:center;padding:20px;">
        <div style="font-size:32px;font-weight:800;color:#e05454;">{{ $ditolak }}</div>
        <div style="font-size:12px;color:var(--text-light);margin-top:4px;">❌ Tidak Lulus</div>
    </div>
</div>

{{-- Predikat --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;">
    <div class="card" style="text-align:center;padding:20px;border-top:4px solid #C4E81D;">
        <div style="font-size:28px;margin-bottom:6px;">🏆</div>
        <div style="font-size:28px;font-weight:800;color:#597001;">{{ $unggul }}</div>
        <div style="font-size:13px;font-weight:700;color:#597001;">Unggul</div>
        <div style="font-size:11px;color:var(--text-light);margin-top:2px;">Nilai ≥ 700</div>
    </div>
    <div class="card" style="text-align:center;padding:20px;border-top:4px solid var(--secondary);">
        <div style="font-size:28px;margin-bottom:6px;">⭐</div>
        <div style="font-size:28px;font-weight:800;color:var(--secondary);">{{ $baik }}</div>
        <div style="font-size:13px;font-weight:700;color:var(--secondary);">Baik</div>
        <div style="font-size:11px;color:var(--text-light);margin-top:2px;">Nilai 550 – 699</div>
    </div>
    <div class="card" style="text-align:center;padding:20px;border-top:4px solid var(--primary);">
        <div style="font-size:28px;margin-bottom:6px;">📝</div>
        <div style="font-size:28px;font-weight:800;color:var(--primary);">{{ $cukup }}</div>
        <div style="font-size:13px;font-weight:700;color:var(--primary);">Cukup</div>
        <div style="font-size:11px;color:var(--text-light);margin-top:2px;">Nilai &lt; 550</div>
    </div>
</div>

{{-- Form Klasifikasi --}}
<div class="card" style="margin-bottom:24px;">
    <div class="card-header">
        <span class="card-title">⚙️ Pengaturan Klasifikasi</span>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('klasifikasi.proses') }}" onsubmit="return confirm('Yakin proses klasifikasi? Status semua siswa PENDING akan diubah.')">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
                <div class="form-group">
                    <label class="form-label">Nilai Minimum Lulus <span style="color:red;">*</span></label>
                    <input type="number" name="nilai_min" class="form-control" value="75" min="0" max="100" step="0.01" required>
                    <div style="font-size:11px;color:var(--text-light);margin-top:4px;">Siswa dengan nilai rata-rata di atas angka ini dinyatakan lulus</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Status yang Diproses</label>
                    <input type="text" class="form-control" value="Pending" disabled style="background:var(--bg);">
                    <div style="font-size:11px;color:var(--text-light);margin-top:4px;">Hanya siswa berstatus pending yang akan diproses</div>
                </div>
            </div>

            @if($pending > 0)
            <div class="alert alert-warning" style="margin-bottom:16px;">
                ⚠️ Terdapat <strong>{{ $pending }} siswa</strong> dengan status pending yang akan diproses.
            </div>
            @else
            <div class="alert alert-info" style="margin-bottom:16px;">
                ℹ️ Tidak ada siswa berstatus pending saat ini.
            </div>
            @endif

            <div style="display:flex;justify-content:flex-end;">
                <button type="submit" class="btn btn-primary" {{ $pending == 0 ? 'disabled' : '' }}>
                    🚀 Proses Klasifikasi Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Hasil Klasifikasi --}}
@if(session('hasil'))
<div class="card" style="margin-bottom:24px;">
    <div class="card-header">
        <span class="card-title">📊 Hasil Klasifikasi Naive Bayes</span>
    </div>
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama Siswa</th>
                    <th>Total Nilai</th>
                    <th>P(Unggul)</th>
                    <th>P(Baik)</th>
                    <th>P(Cukup)</th>
                    <th>Predikat</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($hasilKlasifikasi as $h)
                <tr>
                    <td style="font-weight:600;">{{ $h['nama'] }}</td>
                    <td style="font-weight:600;">{{ $h['total'] }}</td>
                    <td>{{ $h['probabilitas']['Unggul'] }}%</td>
                    <td>{{ $h['probabilitas']['Baik'] }}%</td>
                    <td>{{ $h['probabilitas']['Cukup'] }}%</td>
                    <td>
                        @if($h['predikat'] === 'Unggul')
                            <span class="badge" style="background:#C4E81D;color:#597001;">🏆 Unggul</span>
                        @elseif($h['predikat'] === 'Baik')
                            <span class="badge badge-secondary">⭐ Baik</span>
                        @else
                            <span class="badge badge-primary">📝 Cukup</span>
                        @endif
                    </td>
                    <td>
                        @if($h['status'] === 'lulus')
                            <span class="badge badge-success">✅ Lulus</span>
                        @else
                            <span class="badge badge-danger">❌ Ditolak</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- kouta Kelas --}}
<div class="card">
    <div class="card-header">
        <span class="card-title">🏫 kouta Kelas</span>
    </div>
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama Kelas</th>
                    <th>Wali Kelas</th>
                    <th>kouta</th>
                    <th>Terisi</th>
                    <th>Sisa</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kelas as $k)
                @php $sisa = $k->kouta - ($k->siswa_count ?? 0); @endphp
                <tr>
                    <td style="font-weight:600;">{{ $k->nama_kelas }}</td>
                    <td>{{ $k->wali_kelas ?? '-' }}</td>
                    <td>{{ $k->kouta }}</td>
                    <td>{{ $k->siswa_count ?? 0 }}</td>
                    <td>
                        <span class="badge {{ $sisa > 0 ? 'badge-success' : 'badge-danger' }}">
                            {{ $sisa > 0 ? $sisa.' tersisa' : 'Penuh' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;color:var(--text-light);padding:32px;">
                        Belum ada data kelas. Tambahkan kelas di menu Master.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection