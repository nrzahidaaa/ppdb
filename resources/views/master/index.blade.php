@extends('layouts.app')

@section('title', 'Data Master')
@section('page-title', 'Data Master')

@section('content')

{{-- Header --}}
<div class="section-header">
    <div>
        <h2 style="font-size:16px;font-weight:700;">Data Master</h2>
        <p style="font-size:12px;color:var(--text-light);">Kelola akun user dan admin sistem</p>
    </div>
</div>

{{-- Alert --}}
@if(session('success'))
<div class="alert alert-success" style="margin-bottom:16px;">✅ {{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger" style="margin-bottom:16px;">❌ {{ session('error') }}</div>
@endif

{{-- Toolbar --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
    <p style="font-size:12px;color:var(--text-light);">Kelola akun admin yang dapat mengakses sistem</p>
    <button onclick="openModal('modal-tambah-user')" class="btn btn-primary">➕ Tambah User</button>
</div>

{{-- Tabel User --}}
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

<script>
function openModal(id)  { document.getElementById(id).style.display = 'flex'; }
function closeModal(id) { document.getElementById(id).style.display = 'none'; }

document.querySelectorAll('.btn-edit-user').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.getElementById('edit-user-name').value  = this.dataset.name;
        document.getElementById('edit-user-email').value = this.dataset.email;
        document.getElementById('form-edit-user').action = '/master/user/' + this.dataset.id;
        openModal('modal-edit-user');
    });
});
</script>

@endsection
