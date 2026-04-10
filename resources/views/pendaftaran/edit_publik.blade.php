@extends('layouts.guest')

@section('title', 'Edit Data Pendaftaran')

@section('content')
<div style="max-width:520px;margin:50px auto;padding:0 16px;">

    <div style="background:#fff;border-radius:16px;box-shadow:0 10px 25px rgba(0,0,0,0.08);overflow:hidden;">

        {{-- HEADER --}}
        <div style="background:linear-gradient(135deg,#33528A,#33A9A0);padding:24px 28px;color:#fff;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:44px;height:44px;background:rgba(255,255,255,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;">✏️</div>
                <div>
                    <h2 style="margin:0;font-size:18px;font-weight:700;">Edit Data Pendaftaran</h2>
                    <p style="margin:2px 0 0;font-size:12px;opacity:.85;">Masukkan nomor pendaftaran untuk mengubah data</p>
                </div>
            </div>
        </div>

        <div style="padding:28px;">

            @if(session('error'))
            <div style="background:#fee2e2;color:#991b1b;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:12px;border-left:4px solid #e05454;">
                ❌ {{ session('error') }}
            </div>
            @endif

            <p style="font-size:13px;color:var(--text-light);margin-bottom:20px;">
                Data hanya dapat diedit selama status pendaftaran masih <strong>Pending</strong>. 
                Setelah diverifikasi, data tidak dapat diubah.
            </p>

           <form method="POST" action="{{ route('pendaftaran.cariEdit') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">NISN <span style="color:#e05454">*</span></label>
                    <input type="text" name="nisn" class="form-control"
                        placeholder="Masukkan NISN kamu"
                        value="{{ old('nisn') }}"
                        required>
                    @error('nisn')
                        <div style="color:#e05454;font-size:11px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;padding:13px;margin-top:8px;">
                    🔍 Cari Data Saya
                </button>

                <p style="text-align:center;font-size:11px;color:var(--text-light);margin-top:16px;">
                    <a href="{{ route('beranda') }}" style="color:var(--primary);">← Kembali ke Beranda</a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection