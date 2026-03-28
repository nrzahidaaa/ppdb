<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pembagian Kelas</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #1a2340; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #33528A; padding-bottom: 10px; }
        .header h1 { font-size: 16px; color: #33528A; margin: 0 0 4px; }
        .header p { margin: 0; font-size: 10px; color: #666; }
        .kelas-header { background: #33528A; color: white; padding: 8px 12px; border-radius: 4px; margin: 16px 0 8px; font-weight: bold; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th { background: #eef2f7; color: #33528A; padding: 6px; text-align: left; font-size: 10px; }
        td { padding: 5px 6px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        .badge { padding: 2px 8px; border-radius: 4px; font-size: 9px; font-weight: bold; }
        .Unggul { background: #C4E81D; color: #597001; }
        .Baik   { background: #d1fae5; color: #065f46; }
        .Cukup  { background: #dbeafe; color: #1e40af; }
        .footer { margin-top: 20px; font-size: 9px; color: #999; text-align: right; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
<div class="header">
    <h1>LAPORAN PEMBAGIAN KELAS STRATIFIED</h1>
    <p>Tahun Ajaran 2025/2026 &nbsp;|&nbsp; Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
</div>

@foreach($kelas as $k)
<div class="kelas-header">🏫 {{ $k->nama_kelas }} — {{ $k->siswa->count() }} Siswa</div>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Lengkap</th>
            <th>NISN</th>
            <th>Predikat</th>
        </tr>
    </thead>
    <tbody>
        @forelse($k->siswa as $i => $s)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td><strong>{{ $s->nama }}</strong></td>
            <td>{{ $s->nisn }}</td>
            <td><span class="badge {{ $s->predikat }}">{{ $s->predikat }}</span></td>
        </tr>
        @empty
        <tr><td colspan="4" style="text-align:center;color:#999;">Belum ada siswa</td></tr>
        @endforelse
    </tbody>
</table>
@endforeach

<div class="footer">Dicetak oleh sistem PPDB Online</div>
</body>
</html>