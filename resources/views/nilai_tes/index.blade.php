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

.badge-warning {
    background: #fef3c7;
    color: #92400e;
    padding: 6px 10px;
    border-radius: 999px;
    font-weight: 600;
}

.badge-secondary {
    background: #e5e7eb;
    color: #6b7280;
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

            <a href="{{ route('nilai-tes.template') }}"
            style="
                display:inline-flex;
                align-items:center;
                gap:8px;
                padding:10px 14px;
                border-radius:10px;
                background:#ecfdf5;
                color:#059669;
                font-size:13px;
                font-weight:600;
                text-decoration:none;
                border:1px solid #d1fae5;
                transition:.2s;
            "
            onmouseover="this.style.background='#d1fae5'"
            onmouseout="this.style.background='#ecfdf5'"
            >
                ⬇️ Template Excel
            </a>
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

<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin:20px 0 24px;">
    
    <div class="card" style="padding:18px;text-align:center;">
        <div style="font-size:28px;font-weight:800;color:var(--primary);">{{ $totalNilaiTes }}</div>
        <div style="font-size:12px;color:var(--text-light);margin-top:4px;">📋 Total Nilai</div>
    </div>

    <div class="card" style="padding:18px;text-align:center;">
        <div style="font-size:28px;font-weight:800;color:var(--success);">{{ $totalLulus }}</div>
        <div style="font-size:12px;color:var(--text-light);margin-top:4px;">✅ Lulus</div>
    </div>

    <div class="card" style="padding:18px;text-align:center;">
        <div style="font-size:28px;font-weight:800;color:#e05454;">{{ $totalTidakLulus }}</div>
        <div style="font-size:12px;color:var(--text-light);margin-top:4px;">❌ Tidak Lulus</div>
    </div>

    <div class="card" style="padding:18px;text-align:center;">
        <div style="font-size:28px;font-weight:800;color:#f59e0b;">{{ $totalBelumDinilai }}</div>
        <div style="font-size:12px;color:var(--text-light);margin-top:4px;">⏳ Belum Dinilai</div>
    </div>

</div>

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
    @forelse($nilaiTes as $item)
    @php
    $nt = $item->nilaiTes;
@endphp
        <tr>
            <td>{{ $loop->iteration + ($nilaiTes->firstItem() - 1) }}</td>
            <td>{{ $item->nama ?? '-' }}</td>

            <td>{{ $item->nilaiTes->bhs_indonesia ?? '-' }}</td>
            <td>{{ $item->nilaiTes->matematika ?? '-' }}</td>
            <td>{{ $item->nilaiTes->ipa ?? '-' }}</td>
            <td>{{ $item->nilaiTes->ips ?? '-' }}</td>
            <td>{{ $item->nilaiTes->agama ?? '-' }}</td>
            <td>{{ $item->nilaiTes->doa_iftitah ?? '-' }}</td>
            <td>{{ $item->nilaiTes->tahiyat_awal ?? '-' }}</td>
            <td>{{ $item->nilaiTes->qunut ?? '-' }}</td>
            <td>{{ $item->nilaiTes->membaca_al_quran ?? '-' }}</td>
            <td>{{ $item->nilaiTes->fatihah_4 ?? '-' }}</td>
            <td>{{ $item->nilaiTes->surah_pendek ?? '-' }}</td>
            <td>{{ $item->nilaiTes->doa ?? '-' }}</td>
            <td>{{ $item->nilaiTes->menulis ?? '-' }}</td>

            <td>
    {{ $nt && $nt->tanggal_input
        ? \Carbon\Carbon::parse($nt->tanggal_input)->format('d-m-Y')
        : '-' }}
</td>

        @php
    $total = ($nt->bhs_indonesia ?? 0)
        + ($nt->matematika ?? 0)
        + ($nt->ipa ?? 0)
        + ($nt->ips ?? 0)
        + ($nt->agama ?? 0)
        + ($nt->doa_iftitah ?? 0)
        + ($nt->tahiyat_awal ?? 0)
        + ($nt->qunut ?? 0)
        + ($nt->membaca_al_quran ?? 0)
        + ($nt->fatihah_4 ?? 0)
        + ($nt->surah_pendek ?? 0)
        + ($nt->doa ?? 0)
        + ($nt->menulis ?? 0);

    $statusHasil = $nt->status_hasil ?? '';
@endphp

<td><strong>{{ $total }}</strong></td>

           <td>
    @if($statusHasil === 'lulus')
        <span class="badge badge-success">Lulus</span>
    @elseif(in_array($statusHasil, ['ditolak', 'tidak_lulus']))
        <span class="badge badge-danger">Tidak Lulus</span>
    @elseif($statusHasil === 'pending')
        <span class="badge badge-warning">Pending</span>
    @else
        <span class="badge badge-secondary">-</span>
    @endif
</td>

    <td>
    @if($item->nilaiTes && $item->nilaiTes->id_nilai)
        <form method="POST"
              action="{{ route('nilai-tes.destroy', $item->nilaiTes->id_nilai) }}"
              onsubmit="return confirm('Yakin hapus data ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">
                🗑
            </button>
        </form>
    @else
        <span class="text-muted">-</span>
    @endif
</td>
        </tr>
    @empty
        <tr>
            <td colspan="19" style="text-align:center;padding:40px;color:var(--text-light);">
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