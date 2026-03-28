<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil Klasifikasi</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #1a2340; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #33528A; padding-bottom: 10px; }
        .header h1 { font-size: 16px; color: #33528A; margin: 0 0 4px; }
        .header p { margin: 0; font-size: 10px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #33528A; color: white; padding: 7px 6px; text-align: left; font-size: 10px; }
        td { padding: 6px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        tr:nth-child(even) td { background: #f8f9fc; }
        .badge { padding: 2px 8px; border-radius: 4px; font-size: 9px; font-weight: bold; }
        .Unggul  { background: #C4E81D; color: #597001; }
        .Baik    { background: #d1fae5; color: #065f46; }
        .Cukup   { background: #dbeafe; color: #1e40af; }
        .lulus   { background: #d1fae5; color: #065f46; }
        .ditolak { background: #fee2e2; color: #991b1b; }
        .rekap { margin-top: 20px; display: flex; gap: 10px; }
        .rekap-item { border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px 16px; text-align: center; }
        .footer { margin-top: 20px; font-size: 9px; color: #999; text-align: right; }
    </style>
</head>
<body>
<div class="header">
    <h1>LAPORAN HASIL KLASIFIKASI NAIVE BAYES</h1>
    <p>Tahun Ajaran 2025/2026 &nbsp;|&nbsp; Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Lengkap</th>
            <th>NISN</th>
            <th>IPA</th>
            <th>IPS</th>
            <th>B.Indo</th>
            <th>MTK</th>
            <th>Agama</th>
            <th>Total</th>
            <th>Predikat</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $i => $r)
        @php
            $n = $r->nilaiTes;
            $agama = $n ? ($n->doa_iftitah + $n->tahiyat_awal + $n->qunut + $n->membaca_al_quran + $n->fatihah_4 + $n->doa + $n->menulis) : 0;
            $total = $n ? ($n->ipa + $n->ips + $n->bhs_indonesia + $n->matematika + $agama) : 0;
        @endphp
        <tr>
            <td>{{ $i + 1 }}</td>
            <td><strong>{{ $r->nama }}</strong></td>
            <td>{{ $r->nisn }}</td>
            <td>{{ $n?->ipa ?? '-' }}</td>
            <td>{{ $n?->ips ?? '-' }}</td>
            <td>{{ $n?->bhs_indonesia ?? '-' }}</td>
            <td>{{ $n?->matematika ?? '-' }}</td>
            <td>{{ $agama ?: '-' }}</td>
            <td><strong>{{ $total ?: '-' }}</strong></td>
            <td><span class="badge {{ $r->predikat }}">{{ $r->predikat }}</span></td>
            <td><span class="badge {{ $r->status }}">{{ ucfirst($r->status) }}</span></td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">Total: {{ $data->count() }} siswa diklasifikasi</div>
</body>
</html>