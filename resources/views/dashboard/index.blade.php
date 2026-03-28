@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Alert Cards --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;">
    <div style="background:linear-gradient(135deg,#e8f4fd,#d1eaf8);border-radius:12px;padding:16px 20px;display:flex;align-items:center;gap:12px;">
        <div style="font-size:28px;">📋</div>
        <div>
            <div style="font-size:14px;font-weight:800;color:#1a5276;">{{ $totalPending }} Pendaftaran Baru</div>
            <div style="font-size:11px;color:#2980b9;">Menunggu verifikasi</div>
        </div>
    </div>
    <div style="background:linear-gradient(135deg,#fef9e7,#fdebd0);border-radius:12px;padding:16px 20px;display:flex;align-items:center;gap:12px;">
        <div style="font-size:28px;">⚠️</div>
        <div>
            <div style="font-size:14px;font-weight:800;color:#784212;">{{ $belumKelas }} Siswa Belum Ada Kelas</div>
            <div style="font-size:11px;color:#e67e22;">Perlu pembagian kelas</div>
        </div>
    </div>
    <div style="background:linear-gradient(135deg,#eafaf1,#d5f5e3);border-radius:12px;padding:16px 20px;display:flex;align-items:center;gap:12px;">
        <div style="font-size:28px;">✅</div>
        <div>
            <div style="font-size:14px;font-weight:800;color:#1e8449;">{{ $totalNilaiTes }} Data Nilai Tes</div>
            <div style="font-size:11px;color:#27ae60;">Siap diklasifikasi</div>
        </div>
    </div>
</div>

{{-- Stat Cards --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;">

    <div class="card" style="padding:20px;">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;">
            <div style="width:40px;height:40px;background:#eef2fa;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;">📝</div>
            <span class="badge badge-primary" style="font-size:10px;">Total</span>
        </div>
        <div style="font-size:32px;font-weight:800;color:var(--primary);">{{ $totalPendaftar }}</div>
        <div style="font-size:12px;color:var(--text-light);margin-top:4px;">Total Pendaftar</div>
        <div style="margin-top:10px;height:3px;background:var(--border);border-radius:99px;">
            <div class="progress-bar-fill" data-width="100" style="background:var(--primary);border-radius:99px;height:3px;"></div>
        </div>
    </div>

    <div class="card" style="padding:20px;">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;">
            <div style="width:40px;height:40px;background:#eafaf1;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;">✅</div>
            <span class="badge badge-success" style="font-size:10px;">Lulus</span>
        </div>
        <div style="font-size:32px;font-weight:800;color:var(--success);">{{ $totalLulus }}</div>
        <div style="font-size:12px;color:var(--text-light);margin-top:4px;">Dinyatakan Lulus</div>
        <div style="margin-top:10px;height:3px;background:var(--border);border-radius:99px;">
            @php $pctLulus = $totalPendaftar > 0 ? round(($totalLulus/$totalPendaftar)*100) : 0; @endphp
            <div class="progress-bar-fill" data-width="{{ $pctLulus }}" style="background:var(--success);border-radius:99px;height:3px;"></div>
        </div>
    </div>

    <div class="card" style="padding:20px;">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;">
            <div style="width:40px;height:40px;background:#fef9e7;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;">🏫</div>
            <span class="badge badge-warning" style="font-size:10px;">Kuota</span>
        </div>
        <div style="font-size:32px;font-weight:800;color:#e67e22;">{{ $totalKuota }}</div>
        <div style="font-size:12px;color:var(--text-light);margin-top:4px;">Total Kuota Kelas</div>
        <div style="margin-top:10px;height:3px;background:var(--border);border-radius:99px;">
            @php $pctKuota = $totalKuota > 0 ? round(($totalLulus/$totalKuota)*100) : 0; @endphp
            <div class="progress-bar-fill" data-width="{{ $pctKuota }}" style="background:#e67e22;border-radius:99px;height:3px;"></div>
        </div>
    </div>

    <div class="card" style="padding:20px;">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;">
            <div style="width:40px;height:40px;background:#fee2e2;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;">❌</div>
            <span class="badge badge-danger" style="font-size:10px;">Ditolak</span>
        </div>
        <div style="font-size:32px;font-weight:800;color:#e05454;">{{ $totalDitolak }}</div>
        <div style="font-size:12px;color:var(--text-light);margin-top:4px;">Tidak Lulus</div>
        <div style="margin-top:10px;height:3px;background:var(--border);border-radius:99px;">
            @php $pctDitolak = $totalPendaftar > 0 ? round(($totalDitolak/$totalPendaftar)*100) : 0; @endphp
            <div class="progress-bar-fill" data-width="{{ $pctDitolak }}" style="background:#e05454;border-radius:99px;height:3px;"></div>
        </div>
    </div>

</div>

{{-- Row 2: Pendaftar Terbaru + Predikat + Tren --}}
<div style="display:grid;grid-template-columns:1.5fr 1fr;gap:20px;margin-bottom:24px;">

    {{-- Pendaftar Terbaru --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">📋 Pendaftar Terbaru</span>
            <a href="{{ route('pendaftaran.index') }}" class="btn btn-outline btn-sm">Lihat semua →</a>
        </div>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Siswa</th>
                        <th>Asal Sekolah</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendaftarTerbaru as $p)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <div class="avatar-sm" style="background:var(--primary);">{{ strtoupper(substr($p->nama, 0, 2)) }}</div>
                                <div>
                                    <div style="font-weight:600;font-size:12px;">{{ $p->nama }}</div>
                                    <div style="font-size:10px;color:var(--text-light);">{{ $p->nomor_pendaftaran }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:12px;">{{ $p->asal_sekolah }}</td>
                        <td>
                            @if($p->status === 'lulus') <span class="badge badge-success">✅ Lulus</span>
                            @elseif($p->status === 'ditolak') <span class="badge badge-danger">❌ Ditolak</span>
                            @elseif($p->status === 'verifikasi') <span class="badge badge-secondary">🔵 Verifikasi</span>
                            @else <span class="badge badge-warning">⏳ Pending</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align:center;color:var(--text-light);padding:24px;">Belum ada data pendaftar</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Predikat + Status --}}
    <div style="display:flex;flex-direction:column;gap:16px;">

        {{-- Predikat --}}
        <div class="card" style="flex:1;">
            <div class="card-header">
                <span class="card-title">🏆 Hasil Klasifikasi</span>
                <a href="{{ route('klasifikasi.index') }}" class="btn btn-outline btn-sm">Detail →</a>
            </div>
            <div class="card-body">
                <div style="display:flex;flex-direction:column;gap:12px;">
                    @php
                        $totalKlasifikasi = $totalUnggul + $totalBaik + $totalCukup;
                        $pU = $totalKlasifikasi > 0 ? round(($totalUnggul/$totalKlasifikasi)*100) : 0;
                        $pB = $totalKlasifikasi > 0 ? round(($totalBaik/$totalKlasifikasi)*100) : 0;
                        $pC = $totalKlasifikasi > 0 ? round(($totalCukup/$totalKlasifikasi)*100) : 0;
                    @endphp

                    <div>
                        <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px;">
                            <span style="font-weight:600;">🏆 Unggul</span>
                            <span>{{ $totalUnggul }} siswa ({{ $pU }}%)</span>
                        </div>
                        <div style="height:8px;background:var(--border);border-radius:99px;">
                            <div class="progress-bar-fill" data-width="{{ $pU }}" style="background:#C4E81D;border-radius:99px;height:8px;"></div>
                        </div>
                    </div>

                    <div>
                        <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px;">
                            <span style="font-weight:600;">⭐ Baik</span>
                            <span>{{ $totalBaik }} siswa ({{ $pB }}%)</span>
                        </div>
                        <div style="height:8px;background:var(--border);border-radius:99px;">
                            <div class="progress-bar-fill" data-width="{{ $pB }}" style="background:var(--secondary);border-radius:99px;height:8px;"></div>
                        </div>
                    </div>

                    <div>
                        <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px;">
                            <span style="font-weight:600;">📝 Cukup</span>
                            <span>{{ $totalCukup }} siswa ({{ $pC }}%)</span>
                        </div>
                        <div style="height:8px;background:var(--border);border-radius:99px;">
                            <div class="progress-bar-fill" data-width="{{ $pC }}" style="background:var(--primary);border-radius:99px;height:8px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pembagian Kelas --}}
        <div class="card" style="flex:1;">
            <div class="card-header">
                <span class="card-title">🏫 Pembagian Kelas</span>
                <a href="{{ route('klasifikasi.pembagian') }}" class="btn btn-outline btn-sm">Detail →</a>
            </div>
            <div class="card-body">
                <div style="display:flex;gap:16px;">
                    <div style="flex:1;text-align:center;background:#eafaf1;border-radius:10px;padding:14px;">
                        <div style="font-size:24px;font-weight:800;color:var(--success);">{{ $sudahKelas }}</div>
                        <div style="font-size:11px;color:var(--success);">Sudah Dapat Kelas</div>
                    </div>
                    <div style="flex:1;text-align:center;background:#fef3c7;border-radius:10px;padding:14px;">
                        <div style="font-size:24px;font-weight:800;color:#e67e22;">{{ $belumKelas }}</div>
                        <div style="font-size:11px;color:#e67e22;">Belum Dapat Kelas</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Tren Pendaftaran --}}
<div class="card">
    <div class="card-header">
        <span class="card-title">📈 Tren Pendaftaran (5 Minggu Terakhir)</span>
    </div>
    <div class="card-body">
        @php $maxTren = max(array_column($tren, 'total')) ?: 1; @endphp
        <div style="display:flex;align-items:flex-end;gap:16px;height:120px;padding-bottom:8px;">
            @foreach($tren as $t)
            @php $h = round(($t['total']/$maxTren)*100); @endphp
            <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:6px;height:100%;justify-content:flex-end;">
                <div style="font-size:11px;font-weight:700;color:var(--primary);">{{ $t['total'] }}</div>
                <div class="progress-bar-fill" data-width="{{ $h }}" data-vertical="true"
                     style="width:100%;background:linear-gradient(180deg,var(--secondary),var(--primary));border-radius:6px 6px 0 0;min-height:4px;"></div>
                <div style="font-size:11px;color:var(--text-light);">{{ $t['minggu'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.progress-bar-fill').forEach(function(el) {
    if (el.dataset.vertical === 'true') {
        el.style.height = el.dataset.width + '%';
        el.style.width  = '100%';
    } else {
        el.style.width = el.dataset.width + '%';
    }
});
</script>

@endsection