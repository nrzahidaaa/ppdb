<?php

namespace App\Imports;

use App\Models\Pendaftaran;
use App\Helpers\TahunAjaranHelper;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Carbon\Carbon;

class PendaftaranImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        if (empty($row[1]) && empty($row[3])) {
            return null;
        }

        $tahunAjaranId = TahunAjaranHelper::getSelectedId();

        if (!$tahunAjaranId) {
            return null;
        }

        $nomorPendaftaran = trim($row[1] ?? '');
        $rawNama          = trim($row[3] ?? '');
        $nisn             = preg_replace('/\D/', '', trim($row[2] ?? ''));
        $nama             = $rawNama;

        if (empty($nomorPendaftaran)) {
            $lastNumber = Pendaftaran::where('tahun_ajaran_id', $tahunAjaranId)->count() + 1;
            $nomorPendaftaran = str_pad($lastNumber, 3, '0', STR_PAD_LEFT);
        }

        $ttlCol  = trim($row[4] ?? '');
        $nextCol = trim($row[5] ?? '');

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

        $tempat  = '';
        $tanggal = null;

        if (str_contains($tempatTanggal, ',')) {
            [$tempatPart, $tanggalPart] = explode(',', $tempatTanggal, 2);

            $tempat     = trim($tempatPart);
            $tanggalStr = strtolower(trim($tanggalPart));

            $bulan = [
                'januari'   => '01',
                'februari'  => '02',
                'maret'     => '03',
                'april'     => '04',
                'mei'       => '05',
                'juni'      => '06',
                'juli'      => '07',
                'agustus'   => '08',
                'september' => '09',
                'oktober'   => '10',
                'november'  => '11',
                'desember'  => '12',
            ];

            $parts = explode(' ', $tanggalStr);

            if (count($parts) >= 3) {
                $tgl = str_pad($parts[0], 2, '0', STR_PAD_LEFT);
                $bln = $bulan[$parts[1]] ?? '01';
                $thn = $parts[2];

                try {
                    $tanggal = Carbon::createFromFormat('d/m/Y', "$tgl/$bln/$thn")->format('Y-m-d');
                } catch (\Exception $e) {
                    $tanggal = null;
                }
            }
        }

        $binBinti     = strtolower($binBintiRaw ?? '');
        $jenisKelamin = str_contains($binBinti, 'binti') ? 'P' : 'L';
        $namaOrtu     = trim(str_ireplace(['binti', 'bin'], '', $binBintiRaw ?? ''));
        $jalur        = str_contains(strtolower($jalurRaw ?? ''), 'prestasi') ? 'prestasi' : 'reguler';

        $data = [
            'tahun_ajaran_id'   => $tahunAjaranId,
            'nomor_pendaftaran' => $nomorPendaftaran,
            'nisn'              => $nisn ?: null,
            'nama'              => $nama,
            'tempat_lahir'      => $tempat,
            'tanggal_lahir'     => $tanggal,
            'jenis_kelamin'     => $jenisKelamin,
            'nama_orang_tua'    => $namaOrtu,
            'asal_sekolah'      => $asalSekolah ?? null,
            'alamat'            => $alamat ?? null,
            'jalur'             => $jalur,
            'status'            => 'pending',
        ];

        // PRIORITAS 1: kalau NISN ada, update berdasarkan NISN
        if (!empty($nisn)) {
            $existing = Pendaftaran::where('nisn', $nisn)->first();

            if ($existing) {
                $existing->update($data);
                return $existing;
            }
        }

        // PRIORITAS 2: kalau tidak ketemu NISN, cek tahun ajaran + nomor pendaftaran
        return Pendaftaran::updateOrCreate(
            [
                'tahun_ajaran_id'   => $tahunAjaranId,
                'nomor_pendaftaran' => $nomorPendaftaran,
            ],
            $data
        );
    }
}