<?php

namespace App\Imports;

use App\Models\NilaiTes;
use App\Models\Pendaftaran;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;

class NilaiTesImport
{
    public function import($file)
    {
        ini_set('sys_temp_dir', 'D:\\temp');

        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $berhasil = 0;
        $gagal = 0;

foreach ($rows as $index => $row) {
    if ($index < 1) continue;

    if ($index <= 5) {
        Log::info('Preview row import nilai tes', [
            'index' => $index,
            'row' => $row,
        ]);
    }

    $nisn = preg_replace('/\D/', '', (string) ($row[1] ?? ''));
    $namaSiswa = trim((string) ($row[2] ?? ''));

    if ($nisn === '' && $namaSiswa === '') {
        continue;
    }

    $siswa = Pendaftaran::whereRaw('REPLACE(TRIM(nisn), " ", "") = ?', [$nisn])->first();

    if (!$siswa && $namaSiswa !== '') {
        $siswa = Pendaftaran::whereRaw('LOWER(TRIM(nama)) LIKE ?', ['%' . strtolower($namaSiswa) . '%'])->first();
    }

    if (!$siswa) {
        Log::warning('Siswa tidak ditemukan saat import nilai tes', [
            'baris' => $index + 1,
            // 'nisn' => $nisn,
            'nama' => $namaSiswa,
            'row' => $row,
        ]);
        continue;
    }

    $nilai = [
        'bhs_indonesia'    => is_numeric($row[3] ?? null) ? $row[3] : 0,
        'matematika'       => is_numeric($row[4] ?? null) ? $row[4] : 0,
        'ipa'              => is_numeric($row[5] ?? null) ? $row[5] : 0,
        'ips'              => is_numeric($row[6] ?? null) ? $row[6] : 0,
        'agama'            => is_numeric($row[7] ?? null) ? $row[7] : 0,
        'doa_iftitah'      => is_numeric($row[8] ?? null) ? $row[8] : 0,
        'tahiyat_awal'     => is_numeric($row[9] ?? null) ? $row[9] : 0,
        'qunut'            => is_numeric($row[10] ?? null) ? $row[10] : 0,
        'membaca_al_quran' => is_numeric($row[11] ?? null) ? $row[11] : 0,
        'fatihah_4'        => is_numeric($row[12] ?? null) ? $row[12] : 0,
        'surah_pendek'     => is_numeric($row[13] ?? null) ? $row[13] : 0,
        'doa'              => is_numeric($row[14] ?? null) ? $row[14] : 0,
        'menulis'          => is_numeric($row[15] ?? null) ? $row[15] : 0,
    ];

    $totalNilai = array_sum($nilai);
    $statusHasil = $totalNilai >= 300 ? 'lulus' : 'ditolak';
    
$totalNilai =
    (int) ($nilai['bhs_indonesia'] ?? 0) +
    (int) ($nilai['matematika'] ?? 0) +
    (int) ($nilai['ipa'] ?? 0) +
    (int) ($nilai['ips'] ?? 0) +
    (int) ($nilai['agama'] ?? 0) +
    (int) ($nilai['doa_iftitah'] ?? 0) +
    (int) ($nilai['tahiyat_awal'] ?? 0) +
    (int) ($nilai['qunut'] ?? 0) +
    (int) ($nilai['membaca_al_quran'] ?? 0) +
    (int) ($nilai['fatihah_4'] ?? 0) +
    (int) ($nilai['surah_pendek'] ?? 0) +
    (int) ($nilai['doa'] ?? 0) +
    (int) ($nilai['menulis'] ?? 0);

    NilaiTes::create([
        'id_siswa'         => $siswa->id,
        // 'nisn'             => $nisn,
        'bhs_indonesia'    => $nilai['bhs_indonesia'],
        'matematika'       => $nilai['matematika'],
        'ipa'              => $nilai['ipa'],
        'ips'              => $nilai['ips'],
        'agama'            => $nilai['agama'],
        'doa_iftitah'      => $nilai['doa_iftitah'],
        'tahiyat_awal'     => $nilai['tahiyat_awal'],
        'qunut'            => $nilai['qunut'],
        'membaca_al_quran' => $nilai['membaca_al_quran'],
        'fatihah_4'        => $nilai['fatihah_4'],
        'surah_pendek'     => $nilai['surah_pendek'],
        'doa'              => $nilai['doa'],
        'menulis'          => $nilai['menulis'],
        'total_nilai'      => $totalNilai,
        'tanggal_input'    => now()->format('Y-m-d'),
        'status_hasil'     => $statusHasil,
        'keterangan_hasil' => $statusHasil === 'lulus' ? 'Memenuhi ambang batas' : 'Tidak memenuhi ambang batas',
    ]);

    $berhasil++;
}

        return [
            'berhasil' => $berhasil,
            'gagal' => $gagal,
        ];
    }
}