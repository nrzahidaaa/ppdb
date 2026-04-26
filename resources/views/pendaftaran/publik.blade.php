@extends('layouts.guest')

@section('title', 'Formulir Pendaftaran')

@section('content')
<div style="max-width:800px;margin:40px auto;padding:0 16px;">
    <div style="background:#fff;border-radius:16px;box-shadow:0 10px 25px rgba(0,0,0,0.08);overflow:hidden;">

        <div style="background:linear-gradient(135deg,#33528A,#33A9A0);padding:24px 28px;color:#fff;text-align:center;">
            <h2 style="margin:0;font-size:18px;font-weight:700;">FORMULIR PENDAFTARAN SISTEM PENERIMAAN MURID BARU</h2>
@php
    $tahunAktif = \App\Models\TahunAjaran::where('is_active', true)->first();
@endphp

<p style="margin:4px 0 0;font-size:12px;opacity:.85;">
    Tahun Ajaran {{ $tahunAktif->nama_tahun_ajaran ?? '-' }} — Isi data dengan lengkap dan benar
</p>
        </div>

        <div style="padding:28px;">
            <form method="POST" action="{{ route('pendaftaran.storePublik') }}" enctype="multipart/form-data">
                @csrf

                @include('pendaftaran._form')

                <div style="margin-top:28px;">
                    <button type="submit" class="btn btn-primary" style="width:100%;padding:14px;font-size:14px;font-weight:700;border-radius:10px;">
                        🚀 Kirim Pendaftaran
                    </button>

                    <p style="text-align:center;font-size:11px;color:var(--text-light);margin-top:12px;">
                        Sudah pernah daftar?
                        <a href="{{ route('pengumuman') }}" style="color:var(--primary);font-weight:600;">Cek pengumuman →</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection