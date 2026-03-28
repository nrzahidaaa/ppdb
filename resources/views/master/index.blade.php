@extends('layouts.app')

@section('title', 'Data Master')
@section('page-title', 'Data Master')

@section('content')

{{-- Header --}}
<div class="section-header">
    <div>
        <h2 style="font-size:16px;font-weight:700;">Data Master</h2>
        <p style="font-size:12px;color:var(--text-light);">Kelola data user/admin dan kelas</p>
    </div>
</div>

{{-- Alert --}}
@if(session('success'))
<div class="alert alert-success" style="margin-bottom:16px;">✅ {{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger" style="margin-bottom:16px;">❌ {{ session('error') }}</div>
@endif

{{-- Tabs --}}
<div style="display:flex;gap:0;border-bottom:2px solid var(--border);margin-bottom:24px;">
    <button onclick="showTab('user')" id="tab-user"
        style="padding:10px 24px;font-size:13px;font-weight:600;border:none;background:none;cursor:pointer;border-bottom:3px solid var(--primary);color:var(--primary);margin-bottom:-2px;">
        👤 Data User / Admin
    </button>
    <button onclick="showTab('kelas')" id="tab-kelas"
        style="padding:10px 24px;font-size:13px;font-weight:600;border:none;background:none;cursor:pointer;border-bottom:3px solid transparent;color:var(--text-light);margin-bottom:-2px;">
        🏫 Data Kelas
    </button>
</div>

{{-- ==================== TAB USER ==================== --}}
<div id="content-user">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
        <p style="font-size:12px;color:var(--text-light);">Kelola akun admin yang dapat mengakses sistem</p>
        <button onclick="openModal('modal-tambah-user')" class="btn btn-primary">➕ Tambah User</button>
    </div>

    <div class="card">
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Tgl. Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users ?? [] as $i => $user)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <div class="avatar-sm" style="background:var(--secondary);">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                                <div style="font-weight:600;">{{ $user->name }}</div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge badge-primary">Admin</span></td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div style="display:flex;gap:6px;">
                        <button 
                            class="btn btn-secondary btn-sm btn-edit-user"
                            data-id="{{ $user->id }}"
                            data-name="{{ $user->name }}"
                            data-email="{{ $user->email }}">✏️</button> 
                                @if(auth()->id() !== $user->id)
                                <form method="POST" action="{{ route('master.user.destroy', $user->id) }}" onsubmit="return confirm('Yakin hapus user ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">🗑</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;color:var(--text-light);padding:32px;">Belum ada data user</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ==================== TAB KELAS ==================== --}}
<div id="content-kelas" style="display:none;">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
        <p style="font-size:12px;color:var(--text-light);">Kelola data kelas yang tersedia</p>
        <button onclick="openModal('modal-tambah-kelas')" class="btn btn-primary">➕ Tambah Kelas</button>
    </div>

    <div class="card">
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kelas</th>
                        <!-- <th>Jurusan</th> -->
                        <th>Wali Kelas</th>
                        <th>kouta</th>
                        <th>Terisi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelas ?? [] as $i => $k)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td style="font-weight:600;">{{ $k->nama_kelas }}</td>
                        <!-- <td>
                            @if($k->jurusan === 'MIPA') <span class="badge badge-primary">MIPA</span>
                            @elseif($k->jurusan === 'IPS') <span class="badge badge-success">IPS</span>
                            @else <span class="badge badge-secondary">Bahasa</span>
                            @endif
                        </td> -->
                        <td>{{ $k->wali_kelas ?? '-' }}</td>
                        <td>{{ $k->kouta }}</td>
                        <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            @php
                                $pct = $k->kouta > 0 ? min(100, ($k->siswa_count / $k->kouta) * 100) : 0;
                                $pct = round($pct);
                            @endphp
                            <div style="flex:1;background:var(--border);border-radius:99px;height:6px;min-width:60px;">
                                <div class="progress-bar-fill" data-width="{{ $pct }}"></div>
                            </div>
                            <span style="font-size:11px;font-weight:600;">{{ $k->siswa_count ?? 0 }}/{{ $k->kouta }}</span>
                        </div>
                        </td>
                        <td>
                            <div style="display:flex;gap:6px;">
                        <button
                            class="btn btn-secondary btn-sm btn-edit-kelas"
                            data-id="{{ $k->id }}"
                            data-nama="{{ $k->nama_kelas }}"
                            data-jurusan="{{ $k->jurusan }}"
                            data-wali="{{ $k->wali_kelas }}"
                            data-kouta="{{ $k->kouta }}">✏️</button>                                
                                <form method="POST" action="{{ route('master.kelas.destroy', $k->id) }}" onsubmit="return confirm('Yakin hapus kelas ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">🗑</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align:center;color:var(--text-light);padding:32px;">Belum ada data kelas</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ==================== MODAL TAMBAH USER ==================== --}}
<div id="modal-tambah-user" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:999;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:16px;padding:28px;width:100%;max-width:440px;margin:20px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <h3 style="font-size:15px;font-weight:700;">➕ Tambah User</h3>
            <button onclick="closeModal('modal-tambah-user')" style="background:none;border:none;font-size:20px;cursor:pointer;color:var(--text-light);">✕</button>
        </div>
        <form method="POST" action="{{ route('master.user.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Lengkap <span style="color:red;">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="Nama admin" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email <span style="color:red;">*</span></label>
                <input type="email" name="email" class="form-control" placeholder="email@example.com" required>
            </div>
            <div class="form-group">
                <label class="form-label">Password <span style="color:red;">*</span></label>
                <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required minlength="8">
            </div>
            <div class="form-group">
                <label class="form-label">Konfirmasi Password <span style="color:red;">*</span></label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:20px;">
                <button type="button" onclick="closeModal('modal-tambah-user')" class="btn btn-outline">Batal</button>
                <button type="submit" class="btn btn-primary">💾 Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- ==================== MODAL EDIT USER ==================== --}}
<div id="modal-edit-user" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:999;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:16px;padding:28px;width:100%;max-width:440px;margin:20px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <h3 style="font-size:15px;font-weight:700;">✏️ Edit User</h3>
            <button onclick="closeModal('modal-edit-user')" style="background:none;border:none;font-size:20px;cursor:pointer;color:var(--text-light);">✕</button>
        </div>
        <form method="POST" id="form-edit-user">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Nama Lengkap <span style="color:red;">*</span></label>
                <input type="text" name="name" id="edit-user-name" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email <span style="color:red;">*</span></label>
                <input type="email" name="email" id="edit-user-email" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Password Baru <span style="color:var(--text-light);font-weight:400;">(kosongkan jika tidak diubah)</span></label>
                <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" minlength="8">
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:20px;">
                <button type="button" onclick="closeModal('modal-edit-user')" class="btn btn-outline">Batal</button>
                <button type="submit" class="btn btn-primary">💾 Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- ==================== MODAL TAMBAH KELAS ==================== --}}
<div id="modal-tambah-kelas" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:999;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:16px;padding:28px;width:100%;max-width:440px;margin:20px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <h3 style="font-size:15px;font-weight:700;">➕ Tambah Kelas</h3>
            <button onclick="closeModal('modal-tambah-kelas')" style="background:none;border:none;font-size:20px;cursor:pointer;color:var(--text-light);">✕</button>
        </div>
        <form method="POST" action="{{ route('master.kelas.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Kelas <span style="color:red;">*</span></label>
                <input type="text" name="nama_kelas" class="form-control" placeholder="contoh: X MIPA 1" required>
            </div>
            <!-- <div class="form-group">
                <label class="form-label">Jurusan <span style="color:red;">*</span></label>
                <select name="jurusan" class="form-control form-select" required>
                    <option value="">-- Pilih --</option>
                    <option value="MIPA">MIPA</option>
                    <option value="IPS">IPS</option>
                    <option value="Bahasa">Bahasa</option>
                </select>
            </div> -->
            <div class="form-group">
                <label class="form-label">Wali Kelas</label>
                <input type="text" name="wali_kelas" class="form-control" placeholder="Nama wali kelas (opsional)">
            </div>
            <div class="form-group">
                <label class="form-label">kouta <span style="color:red;">*</span></label>
                <input type="number" name="kouta" class="form-control" placeholder="contoh: 36" min="1" max="50" required>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:20px;">
                <button type="button" onclick="closeModal('modal-tambah-kelas')" class="btn btn-outline">Batal</button>
                <button type="submit" class="btn btn-primary">💾 Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- ==================== MODAL EDIT KELAS ==================== --}}
<div id="modal-edit-kelas" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:999;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:16px;padding:28px;width:100%;max-width:440px;margin:20px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <h3 style="font-size:15px;font-weight:700;">✏️ Edit Kelas</h3>
            <button 
                class="btn btn-secondary btn-sm btn-edit-kelas"
                data-id="{{ $k->id }}"
                data-nama="{{ $k->nama_kelas }}"
                data-jurusan="{{ $k->jurusan }}"
                data-wali="{{ $k->wali_kelas }}"
                data-kouta="{{ $k->kouta }}">✏️</button>            
        </div>
        <form method="POST" id="form-edit-kelas">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Nama Kelas <span style="color:red;">*</span></label>
                <input type="text" name="nama_kelas" id="edit-kelas-nama" class="form-control" required>
            </div>
            <!-- <div class="form-group">
                <label class="form-label">Jurusan <span style="color:red;">*</span></label>
                <select name="jurusan" id="edit-kelas-jurusan" class="form-control form-select" required>
                    <option value="MIPA">MIPA</option>
                    <option value="IPS">IPS</option>
                    <option value="Bahasa">Bahasa</option>
                </select>
            </div> -->
            <div class="form-group">
                <label class="form-label">Wali Kelas</label>
                <input type="text" name="wali_kelas" id="edit-kelas-wali" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">kouta <span style="color:red;">*</span></label>
                <input type="number" name="kouta" id="edit-kelas-kouta" class="form-control" min="1" max="50" required>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:20px;">
                <button type="button" onclick="closeModal('modal-edit-kelas')" class="btn btn-outline">Batal</button>
                <button type="submit" class="btn btn-primary">💾 Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function showTab(tab) {
    document.getElementById('content-user').style.display  = tab === 'user'  ? 'block' : 'none';
    document.getElementById('content-kelas').style.display = tab === 'kelas' ? 'block' : 'none';

    document.getElementById('tab-user').style.borderBottomColor  = tab === 'user'  ? 'var(--primary)' : 'transparent';
    document.getElementById('tab-kelas').style.borderBottomColor = tab === 'kelas' ? 'var(--primary)' : 'transparent';
    document.getElementById('tab-user').style.color  = tab === 'user'  ? 'var(--primary)' : 'var(--text-light)';
    document.getElementById('tab-kelas').style.color = tab === 'kelas' ? 'var(--primary)' : 'var(--text-light)';
}

function openModal(id) {
    document.getElementById(id).style.display = 'flex';
}

function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}

document.querySelectorAll('.btn-edit-user').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.getElementById('edit-user-name').value  = this.dataset.name;
        document.getElementById('edit-user-email').value = this.dataset.email;
        document.getElementById('form-edit-user').action = '/master/user/' + this.dataset.id;
        openModal('modal-edit-user');
    });
});

fdocument.querySelectorAll('.btn-edit-kelas').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.getElementById('edit-kelas-nama').value      = this.dataset.nama;
        document.getElementById('edit-kelas-jurusan').value   = this.dataset.jurusan;
        document.getElementById('edit-kelas-wali').value      = this.dataset.wali;
        document.getElementById('edit-kelas-kouta').value = this.dataset.kouta;
        document.getElementById('form-edit-kelas').action     = '/master/kelas/' + this.dataset.id;
        openModal('modal-edit-kelas');
    });
});

document.querySelectorAll('.progress-bar-fill').forEach(function(el) {
    el.style.width = el.dataset.width + '%';
});

// Buka tab dari URL hash
const hash = window.location.hash;
if (hash === '#kelas') showTab('kelas');
</script>

@endsection
