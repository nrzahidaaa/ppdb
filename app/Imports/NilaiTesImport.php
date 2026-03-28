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

    foreach ($rows as $index => $row) {
        // Skip 2 baris header
        if ($index < 2) continue;
        if (empty($row[0]) && empty($row[1])) continue;

        $namaSiswa = trim($row[1] ?? '');
        if (empty($namaSiswa)) continue;

        $siswa = Pendaftaran::whereRaw('LOWER(nama) = ?', [strtolower($namaSiswa)])->first();

        NilaiTes::create([
            'id_siswa'         => $siswa ? $siswa->id : null,
            'bhs_indonesia'    => is_numeric($row[2]) ? $row[2] : 0,
            'matematika'       => is_numeric($row[3]) ? $row[3] : 0,
            'ipa'              => is_numeric($row[4]) ? $row[4] : 0,
            'ips'              => is_numeric($row[5]) ? $row[5] : 0,
            'agama'             => is_numeric($row[6]) ? $row[5] : 0,
            'doa_iftitah'      => is_numeric($row[7]) ? $row[7] : 0,
            'tahiyat_awal'     => is_numeric($row[8]) ? $row[8] : 0,
            'qunut'            => is_numeric($row[9]) ? $row[9] : 0,
            'membaca_al_quran' => is_numeric($row[10]) ? $row[10] : 0,
            'fatihah_4'        => is_numeric($row[11]) ? $row[11] : 0,
            'surah_pendek'     => is_numeric($row[12]) ? $row[5] : 0,
            'doa'              => is_numeric($row[13]) ? $row[13] : 0,
            'menulis'          => is_numeric($row[14]) ? $row[14] : 0,
            'tanggal_input'    => now()->format('Y-m-d'),
        ]);
    }

    }
}