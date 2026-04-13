@extends('layouts.app')

@section('title', 'Pembagian Kelas')
@section('page-title', 'Pembagian Kelas')

@section('content')

<div class="section-header">
    <h2>Pembagian Kelas</h2>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form method="POST" action="{{ route('klasifikasi.prosesKelas') }}" style="margin-bottom: 20px;">
    @csrf
    <button type="submit" class="btn btn-primary">🚀 Proses Pembagian Kelas</button>
</form>

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