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
        if (empty($row[1]) && empty($row[2])) return null;

        // Pisah tempat & tanggal lahir
        $tempatTanggal = trim($row[3] ?? '');
        $tempat        = '';
        $tanggal       = null;

        if (str_contains($tempatTanggal, ',')) {
            $bagian  = explode(',', $tempatTanggal, 2);
            $tempat  = trim($bagian[0]);
            $tanggalStr = mb_strtolower(trim($bagian[1]));

            $bulan = [
                'januari'=>'01','februari'=>'02','maret'=>'03',
                'april'=>'04','mei'=>'05','juni'=>'06','juli'=>'07',
                'agustus'=>'08','september'=>'09','oktober'=>'10',
                'november'=>'11','desember'=>'12'
            ];

            $parts2 = explode(' ', trim($tanggalStr));
            if (count($parts2) >= 3) {
                $tgl  = str_pad($parts2[0], 2, '0', STR_PAD_LEFT);
                $bln  = $bulan[$parts2[1]] ?? '01';
                $thn  = $parts2[2];
                try {
                    $tanggal = Carbon::createFromFormat('d/m/Y', "$tgl/$bln/$thn");
                } catch (\Exception $e) {
                    $tanggal = null;
                }
            }
        }

        // Jenis kelamin dari Bin/Binti
        $binBinti     = mb_strtolower(trim($row[4] ?? ''));
        $jenisKelamin = str_contains($binBinti, 'binti') ? 'P' : 'L';
        $namaOrtu     = trim(str_ireplace(['binti', 'bin'], '', $row[4] ?? ''));

        // Jalur
        $jalur = mb_strtolower(trim($row[7] ?? ''));
        $jalur = str_contains($jalur, 'prestasi') ? 'prestasi' : 'reguler';

        // Nomor pendaftaran
        $nomorPendaftaran = trim($row[1] ?? '');
        if (empty($nomorPendaftaran)) {
            $nomorPendaftaran = 'PPDB-' . now()->format('Y') . '-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
        }

        return Pendaftaran::updateOrCreate(
            ['nomor_pendaftaran' => $nomorPendaftaran],
            [
                'nama'            => trim($row[2] ?? ''),
                'tempat_lahir'    => $tempat,
                'tanggal_lahir'   => $tanggal,
                'jenis_kelamin'   => $jenisKelamin,
                'nama_orang_tua'  => $namaOrtu,
                'asal_sekolah'    => trim($row[5] ?? ''),
                'alamat'          => trim($row[6] ?? ''),
                'jalur' => $jalur,
                'status'          => 'pending',
            ]
        );
    }
}