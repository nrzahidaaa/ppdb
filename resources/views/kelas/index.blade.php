@extends('layouts.app')

@section('title', 'Data Kelas')
@section('page-title', 'Data Kelas')

@section('content')

{{-- Header --}}
<div class="section-header">
    <div>
        <h2 style="font-size:16px;font-weight:700;">Data Kelas</h2>
        <p style="font-size:12px;color:var(--text-light);">Kelola data kelas yang tersedia</p>
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
    <p style="font-size:12px;color:var(--text-light);">Kelola data kelas yang tersedia</p>
    <button onclick="openModal('modal-tambah-kelas')" class="btn btn-primary">➕ Tambah Kelas</button>
</div>

{{-- Tabel Kelas --}}
<div class="card">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kelas</th>
                    <th>Wali Kelas</th>
                    <th>Kuota</th>
                    <th>Terisi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kelas ?? [] as $i => $k)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td style="font-weight:600;">{{ $k->nama_kelas }}</td>
                    <td>{{ $k->wali_kelas ?? '-' }}</td>
                    <td>{{ $k->kuota }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            @php
                                $pct = $k->kuota > 0 ? min(100, ($k->siswa_count / $k->kuota) * 100) : 0;
                                $pct = round($pct);
                            @endphp
                            <div style="flex:1;background:var(--border);border-radius:99px;height:6px;min-width:60px;">
                                <div class="progress-bar-fill" data-width="{{ $pct }}"></div>
                            </div>
                            <span style="font-size:11px;font-weight:600;">{{ $k->siswa_count ?? 0 }}/{{ $k->kuota }}</span>
                        </div>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <button
                                class="btn btn-secondary btn-sm btn-edit-kelas"
                                data-id="{{ $k->id }}"
                                data-nama="{{ $k->nama_kelas }}"
                                data-wali="{{ $k->wali_kelas }}"
                                data-kuota="{{ $k->kuota }}">✏️</button>
                            <form method="POST" action="{{ route('master.kelas.destroy', $k->id) }}" onsubmit="return confirm('Yakin hapus kelas ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">🗑</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;color:var(--text-light);padding:32px;">Belum ada data kelas</td></tr>
                @endforelse
            </tbody>
        </table>
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
                <input type="text" name="nama_kelas" class="form-control" placeholder="contoh: 7A" required>
            </div>
            <div class="form-group">
                <label class="form-label">Wali Kelas</label>
                <input type="text" name="wali_kelas" class="form-control" placeholder="Nama wali kelas (opsional)">
            </div>
            <div class="form-group">
                <label class="form-label">Kuota <span style="color:red;">*</span></label>
                <input type="number" name="kuota" class="form-control" placeholder="contoh: 36" min="1" max="50" required>
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
            <button onclick="closeModal('modal-edit-kelas')" style="background:none;border:none;font-size:20px;cursor:pointer;color:var(--text-light);">✕</button>
        </div>
        <form method="POST" id="form-edit-kelas">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Nama Kelas <span style="color:red;">*</span></label>
                <input type="text" name="nama_kelas" id="edit-kelas-nama" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Wali Kelas</label>
                <input type="text" name="wali_kelas" id="edit-kelas-wali" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">Kuota <span style="color:red;">*</span></label>
                <input type="number" name="kuota" id="edit-kelas-kuota" class="form-control" min="1" max="50" required>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:20px;">
                <button type="button" onclick="closeModal('modal-edit-kelas')" class="btn btn-outline">Batal</button>
                <button type="submit" class="btn btn-primary">💾 Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id)  { document.getElementById(id).style.display = 'flex'; }
function closeModal(id) { document.getElementById(id).style.display = 'none'; }

document.querySelectorAll('.btn-edit-kelas').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.getElementById('edit-kelas-nama').value  = this.dataset.nama;
        document.getElementById('edit-kelas-wali').value  = this.dataset.wali;
        document.getElementById('edit-kelas-kuota').value = this.dataset.kuota;
        document.getElementById('form-edit-kelas').action = '/master/kelas/' + this.dataset.id;
        openModal('modal-edit-kelas');
    });
});

document.querySelectorAll('.progress-bar-fill').forEach(function(el) {
    el.style.cssText += 'width:' + el.dataset.width + '%;height:6px;border-radius:99px;background:var(--primary);';
});
</script>

@endsection
