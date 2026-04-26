@extends('layouts.app')

@section('title', 'Laporan')
@section('page-title', 'Laporan')

@section('content')

<div class="section-header">
    <div>
        <h2 style="font-size:16px;font-weight:700;">Laporan</h2>
        <p style="font-size:12px;color:var(--text-light);">Export laporan data PPDB dalam format PDF dan Excel</p>
    </div>
</div>

<form method="GET" action="{{ route('laporan.index') }}" style="margin-bottom:20px;">
    <div style="display:flex;gap:10px;align-items:end;flex-wrap:wrap;">

        <div>
            <label>Tahun Ajaran</label>
            <select name="tahun_ajaran_id" id="tahun_ajaran_id" class="form-control">
                <option value="">Semua Tahun Ajaran</option>

                @foreach($tahunAjaranList as $ta)
                    <option value="{{ $ta->id }}"
                        {{ (string)$selectedTahunAjaranId === (string)$ta->id ? 'selected' : '' }}>
                        {{ $ta->nama_tahun_ajaran }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">
            Tampilkan
        </button>

    </div>
</form>

{{-- Statistik --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;">
    <div class="card" style="text-align:center;padding:20px;">
        <div style="font-size:28px;font-weight:800;color:var(--primary);">{{ $totalPendaftar }}</div>
        <div style="font-size:12px;color:var(--text-light);margin-top:4px;">Total Pendaftar</div>
    </div>
    <div class="card" style="text-align:center;padding:20px;">
        <div style="font-size:28px;font-weight:800;color:var(--success);">{{ $totalLulus }}</div>
        <div style="font-size:12px;color:var(--text-light);margin-top:4px;">✅ Lulus</div>
    </div>
    <div class="card" style="text-align:center;padding:20px;">
        <div style="font-size:28px;font-weight:800;color:#e05454;">{{ $totalDitolak }}</div>
        <div style="font-size:12px;color:var(--text-light);margin-top:4px;">❌ Tidak Lulus</div>
    </div>
    <div class="card" style="text-align:center;padding:20px;">
        <div style="font-size:28px;font-weight:800;color:#f59e0b;">{{ $totalPending }}</div>
        <div style="font-size:12px;color:var(--text-light);margin-top:4px;">⏳ Pending</div>
    </div>
</div>

{{-- Laporan Cards --}}
<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:20px;">

    {{-- Laporan Pendaftar --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">📋 Laporan Data Pendaftar</span>
        </div>
        <div class="card-body">
            <p style="font-size:12px;color:var(--text-light);margin-bottom:16px;">
                Berisi seluruh data pendaftar PPDB beserta status pendaftaran.
                Total <strong>{{ $totalPendaftar }}</strong> pendaftar.
            </p>
            <div style="display:flex;gap:8px;">
<button type="button" onclick="doExport('pendaftar', 'pdf')" class="btn btn-danger btn-sm" style="flex:1;justify-content:center;">
    📄 Export PDF
</button>

<button type="button" onclick="doExport('pendaftar', 'excel')" class="btn btn-success btn-sm" style="flex:1;justify-content:center;">
    📊 Export Excel
</button>
            </div>
        </div>
    </div>

    {{-- Laporan Klasifikasi --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">🧮 Laporan Hasil Klasifikasi</span>
        </div>
        <div class="card-body">
            <p style="font-size:12px;color:var(--text-light);margin-bottom:16px;">
                Hasil klasifikasi Naive Bayes. Unggul: <strong>{{ $totalUnggul }}</strong>,
                Baik: <strong>{{ $totalBaik }}</strong>, Cukup: <strong>{{ $totalCukup }}</strong>.
            </p>
            <div style="display:flex;gap:8px;">
                <button type="button" onclick="doExport('klasifikasi', 'pdf')" class="btn btn-danger btn-sm" style="flex:1;justify-content:center;">
    📄 Export PDF
</button>
<button type="button" onclick="doExport('klasifikasi', 'excel')" class="btn btn-success btn-sm" style="flex:1;justify-content:center;">
    📊 Export Excel
</button>
            </div>
        </div>
    </div>

    {{-- Laporan Pembagian Kelas --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">🏫 Laporan Pembagian Kelas</span>
        </div>
        <div class="card-body">
            <p style="font-size:12px;color:var(--text-light);margin-bottom:12px;">
                Data pembagian kelas siswa lulus menggunakan metode Stratified.
                Total <strong>{{ $totalLulus }}</strong> siswa lulus.
            </p>

            {{-- Filter Kelas --}}
            <div style="margin-bottom:12px;">
                <div style="font-size:11px;color:var(--text-light);margin-bottom:6px;">Pilih kelas yang akan diexport:</div>
                <div style="display:flex;gap:6px;flex-wrap:wrap;" id="chips-pembagian">
                    <span class="kelas-chip selected" data-val="all" onclick="toggleChip('pembagian', this)">Semua</span>
                    <span class="kelas-chip" data-val="7A" onclick="toggleChip('pembagian', this)">7A</span>
                    <span class="kelas-chip" data-val="7B" onclick="toggleChip('pembagian', this)">7B</span>
                    <span class="kelas-chip" data-val="7C" onclick="toggleChip('pembagian', this)">7C</span>
                </div>
                <div style="font-size:11px;color:#888;margin-top:6px;" id="info-pembagian">Semua kelas akan diexport</div>
            </div>

            <div style="display:flex;gap:8px;">
                <button onclick="doExport('pembagian', 'pdf')" class="btn btn-danger btn-sm" style="flex:1;justify-content:center;">
                    📄 Export PDF
                </button>
                <button onclick="doExport('pembagian', 'excel')" class="btn btn-success btn-sm" style="flex:1;justify-content:center;">
                    📊 Export Excel
                </button>
            </div>
        </div>
    </div>

    {{-- Laporan Nilai Tes --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">📝 Laporan Rekap Nilai Tes</span>
        </div>
        <div class="card-body">
            <p style="font-size:12px;color:var(--text-light);margin-bottom:12px;">
                Rekap seluruh nilai tes seleksi siswa beserta total nilai masing-masing.
            </p>

            {{-- Filter Kelas --}}
            <div style="display:flex;gap:6px;flex-wrap:wrap;" id="chips-nilai">
    <span class="kelas-chip selected" data-val="all" onclick="toggleChip('nilai', this)">Semua</span>
    <span class="kelas-chip" data-val="lulus" onclick="toggleChip('nilai', this)">Lulus</span>
    <span class="kelas-chip" data-val="tidak_lulus" onclick="toggleChip('nilai', this)">Tidak Lulus</span>
</div>

<div style="font-size:11px;color:#888;margin-top:6px;" id="info-nilai">
    Semua data nilai tes akan diexport
</div>

            <div style="display:flex;gap:8px;">
                <button onclick="doExport('nilai', 'pdf')" class="btn btn-danger btn-sm" style="flex:1;justify-content:center;">
                    📄 Export PDF
                </button>
                <button onclick="doExport('nilai', 'excel')" class="btn btn-success btn-sm" style="flex:1;justify-content:center;">
                    📊 Export Excel
                </button>
            </div>
        </div>
    </div>

</div>

<style>
.kelas-chip {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    border: 1px solid #d1d5db;
    background: #f3f4f6;
    color: #6b7280;
    transition: all 0.15s;
    user-select: none;
}
.kelas-chip.selected {
    background: #dbeafe;
    border-color: #3b82f6;
    color: #1d4ed8;
}
.kelas-chip.selected[data-val="all"] {
    background: #d1fae5;
    border-color: #10b981;
    color: #065f46;
}
</style>

@push('scripts')
<script>
    function toggleChip(type, el) {
        const container = document.getElementById(`chips-${type}`);
        if (!container) return;

        const chips = container.querySelectorAll('.kelas-chip');
        chips.forEach(chip => chip.classList.remove('selected'));

        el.classList.add('selected');

        const val = el.dataset.val;

        if (type === 'nilai') {
            const info = document.getElementById('info-nilai');
            if (!info) return;

            if (val === 'all') {
                info.innerText = 'Semua data nilai tes akan diexport';
            } else if (val === 'lulus') {
                info.innerText = 'Hanya data siswa lulus yang akan diexport';
            } else if (val === 'tidak_lulus') {
                info.innerText = 'Hanya data siswa tidak lulus yang akan diexport';
            }
        }
    }

    function doExport(type, format) {
        const tahunAjaran = document.getElementById('tahun_ajaran_id');
        const tahunAjaranId = tahunAjaran ? tahunAjaran.value : '';

        let url = '';
        let params = new URLSearchParams();

        if (tahunAjaranId) {
            params.append('tahun_ajaran_id', tahunAjaranId);
        }

        if (type === 'pendaftar') {
            url = format === 'pdf'
                ? "{{ route('laporan.pdf.pendaftar') }}"
                : "{{ route('laporan.excel.pendaftar') }}";
        } else if (type === 'klasifikasi') {
            url = format === 'pdf'
                ? "{{ route('laporan.pdf.klasifikasi') }}"
                : "{{ route('laporan.excel.klasifikasi') }}";
        } else if (type === 'pembagian') {
            const kelas = document.getElementById('selected-kelas-pembagian')?.value || 'semua';

            if (kelas !== 'semua') {
                params.append('kelas', kelas);
            }

            url = format === 'pdf'
                ? "{{ route('laporan.pdf.pembagian') }}"
                : "{{ route('laporan.excel.pembagian') }}";
        } else if (type === 'nilai') {
            const statusHasil = document.querySelector('#chips-nilai .kelas-chip.selected')?.dataset.val || 'all';

            if (statusHasil !== 'all') {
                params.append('status_hasil', statusHasil);
            }

            url = format === 'pdf'
                ? "{{ route('laporan.pdf.nilai') }}"
                : "{{ route('laporan.excel.nilai') }}";
        }

        if (!url) {
            alert('Jenis laporan tidak dikenali');
            return;
        }

        const finalUrl = params.toString() ? `${url}?${params.toString()}` : url;

        console.log(finalUrl);
        window.location.href = finalUrl;
    }
</script>
@endpush
@endsection
