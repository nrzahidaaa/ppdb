@extends('layouts.guest')

@section('title', 'Formulir Pendaftaran')

@section('content')
<div style="max-width:720px;margin:50px auto;padding:0 16px;">

    <div style="background:#fff;border-radius:16px;box-shadow:0 10px 25px rgba(0,0,0,0.08);overflow:hidden;">

        {{-- HEADER --}}
        <div style="background:linear-gradient(135deg,#33528A,#33A9A0);padding:24px 28px;color:#fff;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:44px;height:44px;background:rgba(255,255,255,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;">📝</div>
                <div>
                    <h2 style="margin:0;font-size:18px;font-weight:700;">Formulir Pendaftaran PPDB</h2>
                    <p style="margin:2px 0 0;font-size:12px;opacity:.85;">Silakan isi data dengan lengkap dan benar</p>
                </div>
            </div>
        </div>

        {{-- STEP INDICATOR --}}
        <div style="background:#f8f9fc;padding:12px 28px;border-bottom:1px solid #e5e7eb;display:flex;gap:8px;align-items:center;">
            <span style="background:#33528A;color:#fff;border-radius:99px;width:22px;height:22px;display:inline-flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;">1</span>
            <span style="font-size:12px;color:#33528A;font-weight:600;">Data Diri</span>
            <span style="color:#ddd;font-size:12px;">——</span>
            <span style="background:#33528A;color:#fff;border-radius:99px;width:22px;height:22px;display:inline-flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;">2</span>
            <span style="font-size:12px;color:#33528A;font-weight:600;">Upload Berkas</span>
            <span style="color:#ddd;font-size:12px;">——</span>
            <span style="background:#e5e7eb;color:#999;border-radius:99px;width:22px;height:22px;display:inline-flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;">3</span>
            <span style="font-size:12px;color:#999;">Konfirmasi</span>
        </div>

        <div style="padding:28px;">

            {{-- ERROR --}}
            @if($errors->any())
            <div style="background:#fee2e2;color:#991b1b;padding:12px 16px;border-radius:8px;margin-bottom:20px;font-size:12px;border-left:4px solid #e05454;">
                <strong>⚠️ Terdapat kesalahan:</strong>
                <ul style="margin:6px 0 0;padding-left:18px;">
                    @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('pendaftaran.storePublik') }}" enctype="multipart/form-data">
                @csrf

                {{-- ======================== SECTION 1: Data Diri ======================== --}}
                <div style="font-size:11px;font-weight:700;color:#33528A;text-transform:uppercase;letter-spacing:1px;margin-bottom:12px;padding-bottom:6px;border-bottom:2px solid #C4E81D;">
                    👤 Data Diri Siswa
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span style="color:#e05454">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" class="form-control" placeholder="Masukkan nama lengkap siswa">
                </div>

                <div class="form-group">
                    <label class="form-label">NISN <span style="color:#e05454">*</span></label>
                    <input type="text" name="nisn" value="{{ old('nisn') }}" class="form-control"
                        placeholder="Contoh: 1234567890"
                        maxlength="10"
                        oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                    <div style="font-size:11px;color:#9ca3af;margin-top:4px;">
                        NISN terdiri dari 10 digit angka. Bisa dicek di
                        <a href="https://nisn.data.kemdikbud.go.id" target="_blank" style="color:#33528A;">nisn.data.kemdikbud.go.id</a>
                    </div>
                    @error('nisn')
                        <div style="color:#e05454;font-size:11px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div class="form-group">
                        <label class="form-label">Tempat Lahir <span style="color:#e05454">*</span></label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="form-control" placeholder="Kota tempat lahir">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="form-control">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div class="form-group">
                        <label class="form-label">Nama Orang Tua <span style="color:#e05454">*</span></label>
                        <input type="text" name="nama_orang_tua" value="{{ old('nama_orang_tua') }}" class="form-control" placeholder="bin/binti ...">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin <span style="color:#e05454">*</span></label>
                        <select name="jenis_kelamin" class="form-control">
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('jenis_kelamin')=='L'?'selected':'' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin')=='P'?'selected':'' }}>Perempuan</option>
                        </select>
                    </div>
                </div>

                {{-- ======================== SECTION 2: Data Sekolah ======================== --}}
                <div style="font-size:11px;font-weight:700;color:#33528A;text-transform:uppercase;letter-spacing:1px;margin:20px 0 12px;padding-bottom:6px;border-bottom:2px solid #C4E81D;">
                    🏫 Data Sekolah & Alamat
                </div>

                <div class="form-group">
                    <label class="form-label">Asal Sekolah <span style="color:#e05454">*</span></label>
                    <input type="text" name="asal_sekolah" value="{{ old('asal_sekolah') }}" class="form-control" placeholder="Nama SD/MI asal">
                </div>

                <div class="form-group">
                    <label class="form-label">Alamat Lengkap <span style="color:#e05454">*</span></label>
                    <textarea name="alamat" rows="3" class="form-control" placeholder="Jl. ... RT/RW ... Desa/Kel ... Kec ...">{{ old('alamat') }}</textarea>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div class="form-group">
                        <label class="form-label">Jalur Pendaftaran <span style="color:#e05454">*</span></label>
                        <select name="jalur" class="form-control">
                            <option value="">-- Pilih Jalur --</option>
                            <option value="reguler"  {{ old('jalur')=='reguler'?'selected':'' }}>📋 Reguler</option>
                            <option value="prestasi" {{ old('jalur')=='prestasi'?'selected':'' }}>🏆 Prestasi</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="no_telp" value="{{ old('no_telp') }}" class="form-control" placeholder="08xxxxxxxxxx">
                    </div>
                </div>

                {{-- ======================== SECTION 3: Upload Berkas ======================== --}}
                <div style="font-size:11px;font-weight:700;color:#33528A;text-transform:uppercase;letter-spacing:1px;margin:20px 0 12px;padding-bottom:6px;border-bottom:2px solid #C4E81D;">
                    📁 Upload Berkas
                </div>

                <div style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:12px;color:#0369a1;">
                    ℹ️ Upload berkas dalam format <strong>JPG, PNG, atau PDF</strong>. Ukuran maksimal masing-masing file <strong>2MB</strong>.
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">

                    {{-- NISN --}}
                    <div class="form-group">
                        <label class="form-label">NISN <span style="color:#e05454">*</span></label>
                        <input type="file" name="nisn_file" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                        <div style="font-size:11px;color:#9ca3af;margin-top:4px;">
                            NISN terdiri dari 10 digit angka. Bisa dicek di
                            <a href="https://nisn.data.kemdikbud.go.id" target="_blank" style="color:#33528A;">nisn.data.kemdikbud.go.id</a>
                        </div>
                        @error('nisn')
                            <div style="color:#e05454;font-size:11px;margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Kartu Keluarga --}}
                    <div class="form-group" style="background:#f8f9fc;border:1px solid #e5e7eb;border-radius:10px;padding:14px;">
                        <label class="form-label" style="display:flex;align-items:center;gap:6px;margin-bottom:8px;">
                            <span style="font-size:18px;">👨‍👩‍👧</span>
                            <span>Kartu Keluarga (KK) <span style="color:#e05454">*</span></span>
                        </label>
                        <input type="file" name="kartu_keluarga" class="form-control" accept=".jpg,.jpeg,.png,.pdf"
                               onchange="previewFile(this, 'prev-kk')">
                        <div id="prev-kk" style="display:none;margin-top:8px;"></div>
                        @error('kartu_keluarga')
                            <div style="color:#e05454;font-size:11px;margin-top:4px;">{{ $message }}</div>
                        @enderror
                        <div style="font-size:10px;color:#9ca3af;margin-top:6px;">Format: JPG/PNG/PDF, maks 2MB</div>
                    </div>

                    {{-- Akta Kelahiran --}}
                    <div class="form-group" style="background:#f8f9fc;border:1px solid #e5e7eb;border-radius:10px;padding:14px;">
                        <label class="form-label" style="display:flex;align-items:center;gap:6px;margin-bottom:8px;">
                            <span style="font-size:18px;">📜</span>
                            <span>Akta Kelahiran <span style="color:#e05454">*</span></span>
                        </label>
                        <input type="file" name="akta_kelahiran" class="form-control" accept=".jpg,.jpeg,.png,.pdf"
                               onchange="previewFile(this, 'prev-akta')">
                        <div id="prev-akta" style="display:none;margin-top:8px;"></div>
                        @error('akta_kelahiran')
                            <div style="color:#e05454;font-size:11px;margin-top:4px;">{{ $message }}</div>
                        @enderror
                        <div style="font-size:10px;color:#9ca3af;margin-top:6px;">Format: JPG/PNG/PDF, maks 2MB</div>
                    </div>

                    {{-- Foto Diri --}}
                    <div class="form-group" style="background:#f8f9fc;border:1px solid #e5e7eb;border-radius:10px;padding:14px;">
                        <label class="form-label" style="display:flex;align-items:center;gap:6px;margin-bottom:8px;">
                            <span style="font-size:18px;">📸</span>
                            <span>Foto Diri 3×4 <span style="color:#e05454">*</span></span>
                        </label>
                        <input type="file" name="foto" class="form-control" accept=".jpg,.jpeg,.png"
                               onchange="previewFile(this, 'prev-foto')">
                        <div id="prev-foto" style="display:none;margin-top:8px;"></div>
                        @error('foto')
                            <div style="color:#e05454;font-size:11px;margin-top:4px;">{{ $message }}</div>
                        @enderror
                        <div style="font-size:10px;color:#9ca3af;margin-top:6px;">Format: JPG/PNG, background merah, maks 2MB</div>
                    </div>

                </div>

                {{-- Ijazah / SKL (full width) --}}
                <div class="form-group" style="background:#f8f9fc;border:1px solid #e5e7eb;border-radius:10px;padding:14px;margin-top:12px;">
                    <label class="form-label" style="display:flex;align-items:center;gap:6px;margin-bottom:8px;">
                        <span style="font-size:18px;">🎓</span>
                        <span>Ijazah / SKL <span style="color:#e05454">*</span></span>
                    </label>
                    <input type="file" name="ijazah" class="form-control" accept=".jpg,.jpeg,.png,.pdf"
                           onchange="previewFile(this, 'prev-ijazah')">
                    <div id="prev-ijazah" style="display:none;margin-top:8px;"></div>
                    @error('ijazah')
                        <div style="color:#e05454;font-size:11px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                    <div style="font-size:10px;color:#9ca3af;margin-top:6px;">Ijazah asli atau Surat Keterangan Lulus (SKL). Format: JPG/PNG/PDF, maks 2MB</div>
                </div>

                {{-- BUTTON --}}
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

<script>
function previewFile(input, previewId) {
    const preview = document.getElementById(previewId);
    const file = input.files[0];
    if (!file) { preview.style.display = 'none'; return; }

    preview.style.display = 'block';

    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.innerHTML = `<img src="${e.target.result}" style="max-width:100%;max-height:120px;border-radius:8px;border:1px solid #e5e7eb;object-fit:cover;">`;
        };
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = `<div style="display:flex;align-items:center;gap:8px;background:#e0f2fe;padding:8px 12px;border-radius:8px;font-size:12px;color:#0369a1;">
            📄 <span>${file.name}</span>
            <span style="color:#9ca3af;">(${(file.size/1024).toFixed(1)} KB)</span>
        </div>`;
    }
}
</script>

@endsection