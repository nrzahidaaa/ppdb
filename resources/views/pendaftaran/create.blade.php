@extends('layouts.app')

@section('title', 'Tambah Pendaftaran')
@section('page-title', 'Tambah Pendaftaran')

@section('content')
<div class="section-header" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
    <div>
        <h2 style="font-size:16px;font-weight:700;margin:0;">Tambah Pendaftar Baru</h2>
        <p style="font-size:12px;color:var(--text-light);margin-top:4px;">
            Isi formulir data pendaftaran siswa baru
        </p>
    </div>
    <a href="{{ route('pendaftaran.index') }}" class="btn btn-outline">← Kembali</a>
</div>

@if(isset($tahunAjaranAktif) && $tahunAjaranAktif)
    <div style="background:#eff6ff;color:#1d4ed8;padding:12px 16px;border-radius:10px;margin-bottom:16px;border:1px solid #bfdbfe;">
        Tahun ajaran aktif: <strong>{{ $tahunAjaranAktif->nama_tahun_ajaran }}</strong>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <span class="card-title">📝 Formulir Pendaftaran</span>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('pendaftaran.store') }}" enctype="multipart/form-data">
            @csrf

            @include('pendaftaran._form')

            <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:24px;">
                <a href="{{ route('pendaftaran.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">💾 Simpan Pendaftaran</button>
            </div>
        </form>
    </div>
</div>
@endsection