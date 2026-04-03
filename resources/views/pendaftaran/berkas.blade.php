@extends('layouts.app')

@section('title', 'Berkas Pendaftar')
@section('page-title', 'Berkas Pendaftar')

@section('content')
<div class="card" style="padding:24px;">
    <div style="margin-bottom:20px;">
        <h3 style="font-size:18px;font-weight:700;margin-bottom:6px;">Berkas Pendaftar</h3>
        <p style="font-size:12px;color:var(--text-light);margin:0;">
            {{ $pendaftaran->nama }} • {{ $pendaftaran->nisn }}
        </p>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;">
        @php
            $items = [
                'File NISN' => $pendaftaran->nisn_file,
                'Kartu Keluarga' => $pendaftaran->kartu_keluarga,
                'Akta Kelahiran' => $pendaftaran->akta_kelahiran,
                'Foto 3x4' => $pendaftaran->foto,
                'Ijazah / SKL' => $pendaftaran->ijazah,
            ];
        @endphp

        @foreach($items as $label => $file)
            <div style="border:1px solid var(--border);border-radius:14px;padding:16px;background:#fff;">
                <div style="font-size:13px;font-weight:700;margin-bottom:10px;">{{ $label }}</div>

                @if($file)
                    <a href="{{ asset('storage/' . $file) }}" target="_blank" class="btn btn-primary btn-sm">
                        Lihat Berkas
                    </a>
                @else
                    <span style="font-size:12px;color:var(--text-light);">Belum ada file</span>
                @endif
            </div>
        @endforeach
    </div>

    <div style="margin-top:20px;">
        <a href="{{ route('pendaftaran.index') }}" class="btn btn-outline">← Kembali</a>
    </div>
</div>
@endsection