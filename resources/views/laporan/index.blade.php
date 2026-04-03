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
                <a href="{{ route('laporan.pdf.pendaftar') }}" class="btn btn-danger btn-sm" style="flex:1;justify-content:center;">
                    📄 Export PDF
                </a>
                <a href="{{ route('laporan.excel.pendaftar') }}" class="btn btn-success btn-sm" style="flex:1;justify-content:center;">
                    📊 Export Excel
                </a>
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
                <a href="{{ route('laporan.pdf.klasifikasi') }}" class="btn btn-danger btn-sm" style="flex:1;justify-content:center;">
                    📄 Export PDF
                </a>
                <a href="{{ route('laporan.excel.klasifikasi') }}" class="btn btn-success btn-sm" style="flex:1;justify-content:center;">
                    📊 Export Excel
                </a>
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
            <div style="margin-bottom:12px;">
                <div style="font-size:11px;color:var(--text-light);margin-bottom:6px;">Pilih kelas yang akan diexport:</div>
                <div style="display:flex;gap:6px;flex-wrap:wrap;" id="chips-nilai">
                    <span class="kelas-chip selected" data-val="all" onclick="toggleChip('nilai', this)">Semua</span>
                    <span class="kelas-chip" data-val="7A" onclick="toggleChip('nilai', this)">7A</span>
                    <span class="kelas-chip" data-val="7B" onclick="toggleChip('nilai', this)">7B</span>
                    <span class="kelas-chip" data-val="7C" onclick="toggleChip('nilai', this)">7C</span>
                </div>
                <div style="font-size:11px;color:#888;margin-top:6px;" id="info-nilai">Semua kelas akan diexport</div>
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

<script>
function toggleChip(type, el) {
    var container = document.getElementById('chips-' + type);
    var allChip   = container.querySelector('[data-val="all"]');
    var regulars  = container.querySelectorAll('.kelas-chip:not([data-val="all"])');
    var info      = document.getElementById('info-' + type);

    if (el.dataset.val === 'all') {
        regulars.forEach(function(c) { c.classList.remove('selected'); });
        allChip.classList.add('selected');
    } else {
        allChip.classList.remove('selected');
        el.classList.toggle('selected');
        var any = Array.from(regulars).some(function(c) { return c.classList.contains('selected'); });
        if (!any) allChip.classList.add('selected');
    }

    var sel = Array.from(container.querySelectorAll('.kelas-chip.selected'));
    if (sel.some(function(c) { return c.dataset.val === 'all'; })) {
        info.textContent = 'Semua kelas akan diexport';
    } else {
        info.textContent = sel.map(function(c) { return 'Kelas ' + c.dataset.val; }).join(', ') + ' akan diexport';
    }
}

function doExport(type, format) {
    var container = document.getElementById('chips-' + type);
    var allChip   = container.querySelector('[data-val="all"]');
    var params    = '';

    if (!allChip.classList.contains('selected')) {
        var sel = Array.from(container.querySelectorAll('.kelas-chip.selected'));
        params  = sel.map(function(c) { return 'kelas[]=' + encodeURIComponent(c.dataset.val); }).join('&');
    }

    var routes = {
        pembagian: { pdf: '{{ route("laporan.pdf.pembagian") }}', excel: '{{ route("laporan.excel.pembagian") }}' },
        nilai:     { pdf: '{{ route("laporan.pdf.nilai") }}',     excel: '{{ route("laporan.excel.nilai") }}' }
    };

    var url = routes[type][format];
    if (params) url += '?' + params;
    window.location.href = url;
}
</script>

@endsection
