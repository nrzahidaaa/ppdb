<?php

namespace App\Imports;

use App\Models\Pendaftaran;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PendaftaranImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }

public function model(array $row)
{
    if (empty($row[1]) && empty($row[3])) return null;

    $nomorPendaftaran = trim($row[1] ?? '');
    $rawNama = trim($row[3] ?? '');
$nisn    = trim($row[2] ?? '');

// Kalau nama ternyata ada angka di belakang → pisahkan
$namaParts = explode(' ', $rawNama);
$lastPart  = end($namaParts);

$nisn = preg_replace('/\D/', '', $nisn); // ambil angka saja

$nama = implode(' ', $namaParts);

    // =========================
    // DETEKSI TTL (bisa 1 atau 2 kolom)
    // =========================
    $ttlCol = trim($row[4] ?? '');
    $nextCol = trim($row[5] ?? '');

    // kalau kolom berikutnya mengandung tahun → bagian dari TTL
    if (preg_match('/\d{4}/', $nextCol)) {
        $tempatTanggal = $ttlCol . ', ' . $nextCol;

        $binBintiRaw = trim($row[6] ?? '');
        $asalSekolah = trim($row[7] ?? '');
        $alamat      = trim($row[8] ?? '');
        $jalurRaw    = trim($row[9] ?? '');
    } else {
        $tempatTanggal = $ttlCol;

        $binBintiRaw = trim($row[5] ?? '');
        $asalSekolah = trim($row[6] ?? '');
        $alamat      = trim($row[7] ?? '');
        $jalurRaw    = trim($row[8] ?? '');
    }

    // =========================
    // NOMOR PENDAFTARAN
    // =========================
    if (empty($nomorPendaftaran)) {
        $tahun  = now()->format('Y');
        $urutan = Pendaftaran::whereYear('created_at', $tahun)->count() + 1;
        $nomorPendaftaran = 'PPDB-' . $tahun . '-' . str_pad($urutan, 3, '0', STR_PAD_LEFT);
    }

    // =========================
    // PARSING TTL
    // =========================
    $tempat = '';
    $tanggal = null;

    if (str_contains($tempatTanggal, ',')) {
        [$tempatPart, $tanggalPart] = explode(',', $tempatTanggal, 2);

        $tempat = trim($tempatPart);
        $tanggalStr = strtolower(trim($tanggalPart));

        $bulan = [
            'januari'=>'01','februari'=>'02','maret'=>'03',
            'april'=>'04','mei'=>'05','juni'=>'06','juli'=>'07',
            'agustus'=>'08','september'=>'09','oktober'=>'10',
            'november'=>'11','desember'=>'12'
        ];

        $parts = explode(' ', $tanggalStr);

        if (count($parts) >= 3) {
            $tgl = str_pad($parts[0], 2, '0', STR_PAD_LEFT);
            $bln = $bulan[$parts[1]] ?? '01';
            $thn = $parts[2];

            try {
                $tanggal = \Carbon\Carbon::createFromFormat('d/m/Y', "$tgl/$bln/$thn");
            } catch (\Exception $e) {
                $tanggal = null;
            }
        }
    }

    // =========================
    // ORANG TUA
    // =========================
    $binBinti     = strtolower($binBintiRaw);
    $jenisKelamin = str_contains($binBinti, 'binti') ? 'P' : 'L';
    $namaOrtu     = trim(str_ireplace(['binti', 'bin'], '', $binBintiRaw));

    // =========================
    // JALUR
    // =========================
    $jalur = str_contains(strtolower($jalurRaw), 'prestasi') ? 'prestasi' : 'reguler';

    return Pendaftaran::updateOrCreate(
        ['nomor_pendaftaran' => $nomorPendaftaran],
        [
            'nomor_pendaftaran' => $nomorPendaftaran,
            'nisn'              => $nisn,
            'nama'              => $nama,
            'tempat_lahir'      => $tempat,
            'tanggal_lahir'     => $tanggal,
            'jenis_kelamin'     => $jenisKelamin,
            'nama_orang_tua'    => $namaOrtu,
            'asal_sekolah'      => $asalSekolah,
            'alamat'            => $alamat,
            'jalur'             => $jalur,
            'status'            => 'pending',
        ]
    );
}
}