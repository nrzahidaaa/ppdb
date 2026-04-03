@extends('layouts.app')

@section('title', 'Proses Klasifikasi')
@section('page-title', 'Proses Klasifikasi')

@section('content')

<div class="section-header">
    <div>
        <h2 style="font-size:16px;font-weight:700;">Proses Klasifikasi</h2>
        <p style="font-size:12px;color:var(--text-light);">Klasifikasi otomatis siswa menggunakan metode Naive Bayes</p>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success" style="margin-bottom:16px;">✅ {{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger" style="margin-bottom:16px;">❌ {{ session('error') }}</div>
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
        <div style="font-size:11px;color:var(--text-light);margin-top:2px;">Total Nilai ≥ 750</div>
    </div>
    <div class="card" style="text-align:center;padding:20px;border-top:4px solid var(--secondary);">
        <div style="font-size:28px;margin-bottom:6px;">⭐</div>
        <div style="font-size:28px;font-weight:800;color:var(--secondary);">{{ $baik }}</div>
        <div style="font-size:13px;font-weight:700;color:var(--secondary);">Baik</div>
        <div style="font-size:11px;color:var(--text-light);margin-top:2px;">Total Nilai 650 – 749</div>
    </div>
    <div class="card" style="text-align:center;padding:20px;border-top:4px solid var(--primary);">
        <div style="font-size:28px;margin-bottom:6px;">📝</div>
        <div style="font-size:28px;font-weight:800;color:var(--primary);">{{ $cukup }}</div>
        <div style="font-size:13px;font-weight:700;color:var(--primary);">Cukup</div>
        <div style="font-size:11px;color:var(--text-light);margin-top:2px;">Total Nilai &lt; 650</div>
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
<div class="card">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
        <span class="card-title">📊 Hasil Klasifikasi Siswa</span>
        <span style="font-size:12px;color:var(--text-light);">Total: {{ count($hasilKlasifikasi) }} siswa</span>
    </div>
    <div class="table-wrapper" style="overflow-x:auto;">
        <table class="data-table" style="white-space:nowrap;min-width:max-content;">
            <thead>
                <tr>
                    <th>No</th>
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
    @forelse($hasilKlasifikasi as $i => $p)
    @php
        $n = $p->nilaiTes;
        $totalNilai = 0;
        if ($n) {
            $totalNilai = $n->total_nilai ?? (
                (int)$n->bhs_indonesia + (int)$n->matematika +
                (int)$n->ipa + (int)$n->ips + (int)($n->agama ?? 0) +
                (int)$n->doa_iftitah + (int)$n->tahiyat_awal +
                (int)$n->qunut + (int)$n->membaca_al_quran +
                (int)$n->fatihah_4 + (int)($n->surah_pendek ?? 0) +
                (int)$n->doa + (int)$n->menulis
            );
        }

        $maxNilai = 1300;
        if ($totalNilai >= 750) {
            $pUnggul = round(($totalNilai / $maxNilai) * 100, 1);
            $pBaik   = round((1 - $totalNilai / $maxNilai) * 60, 1);
            $pCukup  = round((1 - $totalNilai / $maxNilai) * 30, 1);
        } elseif ($totalNilai >= 650) {
            $pUnggul = round(($totalNilai / $maxNilai) * 50, 1);
            $pBaik   = round(($totalNilai / $maxNilai) * 100, 1);
            $pCukup  = round((1 - $totalNilai / $maxNilai) * 40, 1);
        } else {
            $pUnggul = round(($totalNilai / $maxNilai) * 30, 1);
            $pBaik   = round(($totalNilai / $maxNilai) * 50, 1);
            $pCukup  = round(($totalNilai / $maxNilai) * 100, 1);
        }

        $classUnggul = $p->predikat == 'Unggul' ? 'font-weight:700;color:#597001;' : 'color:#999;';
        $classBaik   = $p->predikat == 'Baik'   ? 'font-weight:700;color:#33A9A0;' : 'color:#999;';
        $classCukup  = $p->predikat == 'Cukup'  ? 'font-weight:700;color:#33528A;' : 'color:#999;';
    @endphp
    <tr>
        <td>{{ $i + 1 }}</td>
        <td style="font-weight:600;">{{ $p->nama ?? '-' }}</td>
        <td style="font-weight:700;color:#33528A;">{{ $totalNilai }}</td>
        <td><span style="{{ $classUnggul }}">{{ $pUnggul }}%</span></td>
        <td><span style="{{ $classBaik }}">{{ $pBaik }}%</span></td>
        <td><span style="{{ $classCukup }}">{{ $pCukup }}%</span></td>
        <td>
            @if(($p->predikat ?? '') === 'Unggul')
                <span class="badge" style="background:#C4E81D;color:#597001;">🏆 Unggul</span>
            @elseif(($p->predikat ?? '') === 'Baik')
                <span class="badge badge-secondary">⭐ Baik</span>
            @elseif(($p->predikat ?? '') === 'Cukup')
                <span class="badge badge-primary">📝 Cukup</span>
            @else
                <span class="badge" style="background:#e5e7eb;color:#999;">-</span>
            @endif
        </td>
        <td>
            @if(($p->status ?? '') === 'lulus')
                <span class="badge badge-success">✅ Lulus</span>
            @elseif(($p->status ?? '') === 'ditolak')
                <span class="badge badge-danger">❌ Ditolak</span>
            @else
                <span class="badge badge-warning">⏳ Pending</span>
            @endif
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="8" style="text-align:center;padding:40px;color:#999;">
            Belum ada data klasifikasi. Klik "Proses Klasifikasi Sekarang" untuk memulai.
        </td>
    </tr>
    @endforelse
</tbody>
        </table>
    </div>
</div>

@endsection