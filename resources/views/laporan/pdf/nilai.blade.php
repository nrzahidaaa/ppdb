<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekap Nilai Tes</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; color: #1a2340; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #33528A; padding-bottom: 10px; }
        .header h1 { font-size: 16px; color: #33528A; margin: 0 0 4px; }
        .header p { margin: 0; font-size: 10px; color: #666; }
        .filter-info { display: inline-block; background: #eef2f7; color: #33528A; border-radius: 4px; padding: 3px 10px; font-size: 10px; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #33528A; color: white; padding: 6px 4px; text-align: center; font-size: 9px; }
        td { padding: 5px 4px; border-bottom: 1px solid #e5e7eb; font-size: 9px; text-align: center; }
        td:nth-child(2) { text-align: left; }
        td:nth-child(3) { text-align: left; }
        tr:nth-child(even) td { background: #f8f9fc; }
        .total { font-weight: bold; color: #33528A; }
        .footer { margin-top: 20px; font-size: 9px; color: #999; text-align: right; }
    </style>
</head>
<body>
<div class="header">
    <h1>LAPORAN REKAP NILAI TES SELEKSI</h1>
    <p>Tahun Ajaran 2025/2026 &nbsp;|&nbsp; Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
    <span class="filter-info">Kelas: {{ $filterLabel ?? 'Semua Kelas' }}</span>
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>IPA</th>
            <th>IPS</th>
            <th>B.Indo</th>
            <th>MTK</th>
            <th>Iftitah</th>
            <th>Tahiyat</th>
            <th>Qunut</th>
            <th>Al-Quran</th>
            <th>Fatihah</th>
            <th>Doa</th>
            <th>Menulis</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $i => $r)
        @php
            $total = $r->ipa + $r->ips + $r->bhs_indonesia + $r->matematika +
                     $r->doa_iftitah + $r->tahiyat_awal + $r->qunut +
                     $r->membaca_al_quran + $r->fatihah_4 + $r->doa + $r->menulis;
        @endphp
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $r->siswa?->nama ?? '-' }}</td>
            <td>{{ $r->siswa?->kelas?->nama_kelas ?? '-' }}</td>
            <td>{{ $r->ipa }}</td>
            <td>{{ $r->ips }}</td>
            <td>{{ $r->bhs_indonesia }}</td>
            <td>{{ $r->matematika }}</td>
            <td>{{ $r->doa_iftitah }}</td>
            <td>{{ $r->tahiyat_awal }}</td>
            <td>{{ $r->qunut }}</td>
            <td>{{ $r->membaca_al_quran }}</td>
            <td>{{ $r->fatihah_4 }}</td>
            <td>{{ $r->doa }}</td>
            <td>{{ $r->menulis }}</td>
            <td class="total">{{ $total }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">Total: {{ $data->count() }} siswa &nbsp;|&nbsp; Dicetak oleh sistem PPDB Online</div>
</body>
</html>
