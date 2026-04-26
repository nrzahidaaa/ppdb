@extends('layouts.app')

@section('title', 'Tahun Ajaran')
@section('page-title', 'Tahun Ajaran')

@section('content')
<div class="section-header" style="margin-bottom:20px;">
    <div>
        <h2 style="font-size:16px;font-weight:700;margin:0;">Kelola Tahun Ajaran</h2>
        <p style="font-size:12px;color:var(--text-light);margin-top:4px;">
            Tambah tahun ajaran baru dan atur status aktif
        </p>
    </div>
</div>

@if(session('success'))
    <div style="background:#dcfce7;color:#166534;padding:12px 16px;border-radius:8px;margin-bottom:16px;">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div style="background:#fee2e2;color:#991b1b;padding:12px 16px;border-radius:8px;margin-bottom:16px;">
        <ul style="margin:0;padding-left:18px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card" style="margin-bottom:20px;">
    <div class="card-header">
        <span class="card-title">➕ Tambah Tahun Ajaran</span>
    </div>
    <div class="card-body">
        <form action="{{ route('tahun-ajaran.store') }}" method="POST">
            @csrf
            <div style="display:flex;gap:12px;align-items:center;">
                <input type="text"
                       name="nama_tahun_ajaran"
                       class="form-control"
                       placeholder="Contoh: 2026/2027"
                       required>
                <button type="submit" class="btn btn-primary">Tambah</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title">📘 Daftar Tahun Ajaran</span>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tahun Ajaran</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tahunAjarans as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->nama_tahun_ajaran }}</td>
                        <td>
                            @if($item->is_active)
                                <span style="background:#dcfce7;color:#166534;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:600;">
                                    Aktif
                                </span>
                            @else
                                <span style="background:#f1f5f9;color:#475569;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:600;">
                                    Nonaktif
                                </span>
                            @endif
                        </td>
                        <td>
                            @if(!$item->is_active)
                                <form action="{{ route('tahun-ajaran.toggle', $item->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        Set Aktif
                                    </button>
                                </form>
                            @else
                                <span style="color:#64748b;">Sedang aktif</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Belum ada data tahun ajaran.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection