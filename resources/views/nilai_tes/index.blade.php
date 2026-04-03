@extends('layouts.app')

@section('title', 'Nilai Tes')
@section('page-title', 'Nilai Tes')

@section('content')

<style>
.table-wrapper {
    overflow-x: auto !important;
    -webkit-overflow-scrolling: touch;
}
.table-wrapper::-webkit-scrollbar {
    height: 8px;
}
.table-wrapper::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}
.table-wrapper::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}
.table-wrapper::-webkit-scrollbar-thumb:hover {
    background: #888;
}
.data-table {
    white-space: nowrap !important;
    min-width: max-content !important;
    font-size: 12px !important;
}
.data-table th {
    font-size: 11px !important;
    white-space: nowrap !important;
    padding: 10px 10px !important;
}
.data-table td {
    font-size: 12px !important;
    padding: 8px 10px !important;
}
.badge-success {
    background: #d1fae5;
    color: #065f46;
    padding: 6px 10px;
    border-radius: 999px;
    font-weight: 600;
}

.badge-danger {
    background: #fee2e2;
    color: #991b1b;
    padding: 6px 10px;
    border-radius: 999px;
    font-weight: 600;
}
</style>

<div class="section-header">
    <div>
        <h2 style="font-size:16px;font-weight:700;">Data Nilai Tes</h2>
        <p style="font-size:12px;color:var(--text-light);">Import dan kelola nilai tes seleksi siswa</p>
    </div>

    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
        <button onclick="openModal('modal-tambah-nilai')" class="btn btn-primary">➕ Tambah Manual</button>

        <form method="POST" action="{{ route('nilai-tes.import') }}" enctype="multipart/form-data" style="display:flex;gap:8px;align-items:center;">
            @csrf
            <input type="file" name="file" accept=".xlsx,.xls" class="form-control" style="width:200px;">
            <button type="submit" class="btn btn-secondary">📥 Import Excel</button>
        </form>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success" style="margin-bottom:16px;">✅ {{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger" style="margin-bottom:16px;">❌ {{ session('error') }}</div>
@endif
@if($errors->any())
<div class="alert alert-danger" style="margin-bottom:16px;">❌ {{ $errors->first() }}</div>
@endif

<div class="card">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>B.Indo</th>
                    <th>MTK</th>
                    <th>IPA</th>
                    <th>IPS</th>
                    <th>Agama</th>
                    <th>Iftitah</th>
                    <th>Tahiyat</th>
                    <th>Qunut</th>
                    <th>Al-Qur'an</th>
                    <th>Fatihah</th>
                    <th>Surah</th>
                    <th>Do'a</th>
                    <th>Menulis</th>
                    <th>Tgl. Input</th>
                    <th>Total</th>
                    <th>STATUS</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($nilaiTes as $i => $nilai)
                <tr>
                    <td>{{ $nilaiTes->firstItem() + $i }}</td>
                    <td>
                        <div style="font-weight:600;">{{ $nilai->siswa->nama ?? '-' }}</div>
                        <div style="font-size:10px;color:var(--text-light);">{{ $nilai->siswa->nisn ?? '-' }}</div>
                    </td>
                    <td>{{ $nilai->bhs_indonesia }}</td>
                    <td>{{ $nilai->matematika }}</td>
                    <td>{{ $nilai->ipa }}</td>
                    <td>{{ $nilai->ips }}</td>
                    <td>{{ $nilai->agama }}</td>
                    <td>{{ $nilai->doa_iftitah }}</td>
                    <td>{{ $nilai->tahiyat_awal }}</td>
                    <td>{{ $nilai->qunut }}</td>
                    <td>{{ $nilai->membaca_al_quran }}</td>
                    <td>{{ $nilai->fatihah_4 }}</td>
                    <td>{{ $nilai->surah_pendek }}</td>
                    <td>{{ $nilai->doa }}</td>
                    <td>{{ $nilai->menulis }}</td>
                    
                    <td>{{ $nilai->tanggal_input?->format('d/m/Y') }}</td>
                    @php
$total = $nilai->bhs_indonesia + $nilai->matematika + $nilai->ipa + $nilai->ips +
         $nilai->agama + $nilai->doa_iftitah + $nilai->tahiyat_awal + $nilai->qunut +
         $nilai->membaca_al_quran + $nilai->fatihah_4 + $nilai->surah_pendek +
         $nilai->doa + $nilai->menulis;
@endphp
<td><strong>{{ $total }}</strong></td>
                    <td>
                        <span class="badge {{ $nilai->status_hasil == 'lulus' ? 'badge-success' : 'badge-danger' }}">
                            {{ ucfirst($nilai->status_hasil) }}
                        </span>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('nilai-tes.destroy', $nilai->id_nilai) }}" onsubmit="return confirm('Yakin hapus data ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">🗑</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="17" style="text-align:center;padding:40px;color:var(--text-light);">
                        Belum ada data nilai. Import file Excel atau tambah manual.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper">
        <span class="pagination-info">
            Menampilkan {{ $nilaiTes->firstItem() ?? 0 }}–{{ $nilaiTes->lastItem() ?? 0 }} dari {{ $nilaiTes->total() }} data
        </span>
        {{ $nilaiTes->links() }}
    </div>
</div>

{{-- ==================== MODAL TAMBAH NILAI ==================== --}}
<div id="modal-tambah-nilai" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:999;align-items:flex-start;justify-content:center;overflow-y:auto;padding:20px;">
    <div style="background:white;border-radius:16px;padding:28px;width:100%;max-width:600px;margin:auto;">

        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <h3 style="font-size:15px;font-weight:700;">➕ Tambah Nilai Tes Manual</h3>
            <button onclick="closeModal('modal-tambah-nilai')" style="background:none;border:none;font-size:20px;cursor:pointer;color:var(--text-light);">✕</button>
        </div>

        <form method="POST" action="{{ route('nilai-tes.store') }}">
            @csrf

            <div class="form-group" style="margin-bottom:16px;">
                <label class="form-label" style="font-size:12px;font-weight:600;display:block;margin-bottom:6px;">
                    Nama Siswa <span style="color:red;">*</span>
                </label>
                <select name="id_siswa" class="form-control form-select" required>
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($pendaftaran as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->nisn }})</option>
                    @endforeach
                </select>
            </div>

            <div style="font-size:12px;font-weight:700;color:var(--primary);margin-bottom:10px;padding-bottom:6px;border-bottom:1px solid var(--border);">
                Nilai Akademik
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
                <div class="form-group">
                    <label class="form-label" style="font-size:12px;font-weight:600;display:block;margin-bottom:4px;">Bhs. Indonesia</label>
                    <input type="number" name="bhs_indonesia" class="form-control" min="0" max="100" placeholder="0–100" required>
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-size:12px;font-weight:600;display:block;margin-bottom:4px;">Matematika</label>
                    <input type="number" name="matematika" class="form-control" min="0" max="100" placeholder="0–100" required>
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-size:12px;font-weight:600;display:block;margin-bottom:4px;">IPA</label>
                    <input type="number" name="ipa" class="form-control" min="0" max="100" placeholder="0–100" required>
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-size:12px;font-weight:600;display:block;margin-bottom:4px;">IPS</label>
                    <input type="number" name="ips" class="form-control" min="0" max="100" placeholder="0–100" required>
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-size:12px;font-weight:600;display:block;margin-bottom:4px;">Agama</label>
                    <input type="number" name="agama" class="form-control" min="0" max="100" placeholder="0–100" required>
                </div>
            </div>

            <div style="font-size:12px;font-weight:700;color:var(--primary);margin-bottom:10px;padding-bottom:6px;border-bottom:1px solid var(--border);">
                Nilai Praktek Agama
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
                <div class="form-group">
                    <label class="form-label" style="font-size:12px;font-weight:600;display:block;margin-bottom:4px;">Do'a Iftitah</label>
                    <input type="number" name="doa_iftitah" class="form-control" min="0" max="100" placeholder="0–100" required>
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-size:12px;font-weight:600;display:block;margin-bottom:4px;">Tahiyat Awal</label>
                    <input type="number" name="tahiyat_awal" class="form-control" min="0" max="100" placeholder="0–100" required>
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-size:12px;font-weight:600;display:block;margin-bottom:4px;">Qunut</label>
                    <input type="number" name="qunut" class="form-control" min="0" max="100" placeholder="0–100" required>
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-size:12px;font-weight:600;display:block;margin-bottom:4px;">Baca Al-Qur'an</label>
                    <input type="number" name="membaca_al_quran" class="form-control" min="0" max="100" placeholder="0–100" required>
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-size:12px;font-weight:600;display:block;margin-bottom:4px;">Fatihah 4</label>
                    <input type="number" name="fatihah_4" class="form-control" min="0" max="100" placeholder="0–100" required>
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-size:12px;font-weight:600;display:block;margin-bottom:4px;">Surah Pendek</label>
                    <input type="number" name="surah_pendek" class="form-control" min="0" max="100" placeholder="0–100" required>
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-size:12px;font-weight:600;display:block;margin-bottom:4px;">Do'a</label>
                    <input type="number" name="doa" class="form-control" min="0" max="100" placeholder="0–100" required>
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-size:12px;font-weight:600;display:block;margin-bottom:4px;">Menulis</label>
                    <input type="number" name="menulis" class="form-control" min="0" max="100" placeholder="0–100" required>
                </div>
            </div>

            <div class="form-group" style="margin-bottom:20px;">
                <label class="form-label" style="font-size:12px;font-weight:600;display:block;margin-bottom:4px;">Tanggal Input</label>
                <input type="date" name="tanggal_input" class="form-control" value="{{ date('Y-m-d') }}">
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" onclick="closeModal('modal-tambah-nilai')" class="btn btn-outline">Batal</button>
                <button type="submit" class="btn btn-primary">💾 Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id)  { document.getElementById(id).style.display = 'flex'; }
    function closeModal(id) { document.getElementById(id).style.display = 'none'; }

    document.addEventListener('DOMContentLoaded', function() {
        var adaError = "<?php echo $errors->any() ? '1' : '0'; ?>";
        if (adaError === '1') {
            openModal('modal-tambah-nilai');
        }
    });
</script>

@endsection