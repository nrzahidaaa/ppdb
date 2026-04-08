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