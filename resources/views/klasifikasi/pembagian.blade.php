@extends('layouts.app')

@section('title','Pembagian Kelas')

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

<form method="POST" action="{{ route('klasifikasi.prosesKelas') }}">
    @csrf
    <button class="btn btn-primary">🚀 Proses Pembagian Kelas</button>
</form>

<hr>

@foreach($kelas as $k)
<div class="card" style="margin-bottom:20px;">
    
    <div class="card-header" style="display:flex;justify-content:space-between;">
        <strong>{{ $k->nama_kelas }}</strong>
        <span style="font-size:12px;color:gray;">
            ({{ $k->siswa->count() }} / {{ $k->kouta }})
        </span>
    </div>

    <div class="card-body">

        @if($k->siswa->count())
        <table class="data-table">
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
                        {{ $s->predikat=='Unggul'?'badge-success':
                           ($s->predikat=='Baik'?'badge-warning':'badge-secondary') }}">
                            {{ $s->predikat }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <p style="font-size:12px;color:gray;">Belum ada siswa</p>
        @endif

    </div>
</div>
@endforeach

@endsection