<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Pendaftar</title>
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
        .lulus    { background: #d1fae5; color: #065f46; }
        .ditolak  { background: #fee2e2; color: #991b1b; }
        .pending  { background: #fef3c7; color: #92400e; }
        .footer { margin-top: 20px; font-size: 9px; color: #999; text-align: right; }
    </style>
</head>
<body>
<div class="header">
    <h1>LAPORAN DATA PENDAFTAR PPDB</h1>
    <p>Tahun Ajaran 2025/2026 &nbsp;|&nbsp; Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>No. Pendaftaran</th>
            <th>Nama Lengkap</th>
            <th>NISN</th>
            <th>Tempat, Tgl Lahir</th>
            <th>JK</th>
            <th>Asal Sekolah</th>
            <th>Nilai</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $i => $r)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $r->nomor_pendaftaran }}</td>
            <td><strong>{{ $r->nama }}</strong></td>
            <td>{{ $r->nisn }}</td>
            <td>{{ $r->tempat_lahir }}, {{ $r->tanggal_lahir?->format('d/m/Y') }}</td>
            <td>{{ $r->jenis_kelamin }}</td>
            <td>{{ $r->asal_sekolah }}</td>
            <td>{{ $r->nilai_rata_rata }}</td>
            <td><span class="badge {{ $r->status }}">{{ ucfirst($r->status) }}</span></td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">Total: {{ $data->count() }} pendaftar</div>
</body>
</html>