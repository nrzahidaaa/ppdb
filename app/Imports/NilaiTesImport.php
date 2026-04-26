<?php

namespace App\Imports;

use App\Models\NilaiTes;
use App\Models\Pendaftaran;
use App\Helpers\TahunAjaranHelper;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class NilaiTesImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 3;
    }

    private function normalizeNumber($value)
    {
        $value = preg_replace('/\D/', '', (string) $value);
        return ltrim($value, '0') ?: '0';
    }

    private function parseNilai($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

        $value = trim((string) $value);

        if (in_array($value, ['?', '-', ''], true)) {
            return null;
        }

        if (is_numeric($value)) {
            return (int) $value;
        }

        return match (strtoupper($value)) {
            'A' => 100,
            'B' => 90,
            'C' => 80,
            'D' => 70,
            default => null,
        };
    }

    public function model(array $row)
    {
        $tahunAjaranId = TahunAjaranHelper::getSelectedId();

        if (!$tahunAjaranId) {
            return null;
        }

        // Excel kamu:
        // A=0 NO
        // B=1 NISN
        // C=2 NAMA
        // D=3 B.INDO
        // E=4 MTK
        // F=5 IPA
        // G=6 IPS
        // H=7 AGAMA
        // I=8 DOA IFTITAH
        // J=9 TAHIYAT
        // K=10 QUNUT
        // L=11 MEMBACA
        // M=12 FATIHAH 4
        // N=13 SURAH
        // O=14 DOA
        // P=15 MENULIS

        $nisnRaw = trim((string) ($row[1] ?? ''));
        $nama    = trim((string) ($row[2] ?? ''));

        if ($nisnRaw === '' && $nama === '') {
            return null;
        }

        $nisn = $this->normalizeNumber($nisnRaw);

        $siswa = null;

        if (!empty($nisnRaw)) {
            $siswa = Pendaftaran::where('tahun_ajaran_id', $tahunAjaranId)
                ->get()
                ->first(function ($item) use ($nisn) {
                    return $this->normalizeNumber($item->nisn) === $nisn;
                });
        }

        if (!$siswa && !empty($nama)) {
            $siswa = Pendaftaran::where('tahun_ajaran_id', $tahunAjaranId)
                ->where('nama', 'like', $nama)
                ->first();
        }

        if (!$siswa) {
            return null;
        }

        $bhsIndonesia = $this->parseNilai($row[3] ?? null);
        $matematika   = $this->parseNilai($row[4] ?? null);
        $ipa          = $this->parseNilai($row[5] ?? null);
        $ips          = $this->parseNilai($row[6] ?? null);
        $agama        = $this->parseNilai($row[7] ?? null);
        $doaIftitah   = $this->parseNilai($row[8] ?? null);
        $tahiyatAwal  = $this->parseNilai($row[9] ?? null);
        $qunut        = $this->parseNilai($row[10] ?? null);
        $membacaQuran = $this->parseNilai($row[11] ?? null);
        $fatihah4     = $this->parseNilai($row[12] ?? null);
        $surahPendek  = $this->parseNilai($row[13] ?? null);
        $doa          = $this->parseNilai($row[14] ?? null);
        $menulis      = $this->parseNilai($row[15] ?? null);

        $total = ($bhsIndonesia ?? 0)
            + ($matematika ?? 0)
            + ($ipa ?? 0)
            + ($ips ?? 0)
            + ($agama ?? 0)
            + ($doaIftitah ?? 0)
            + ($tahiyatAwal ?? 0)
            + ($qunut ?? 0)
            + ($membacaQuran ?? 0)
            + ($fatihah4 ?? 0)
            + ($surahPendek ?? 0)
            + ($doa ?? 0)
            + ($menulis ?? 0);

        $statusHasil = $total >= 600 ? 'lulus' : 'ditolak';

        return NilaiTes::updateOrCreate(
            [
                'id_siswa' => $siswa->id,
            ],
            [
                'id_siswa'         => $siswa->id,
                'bhs_indonesia'    => $bhsIndonesia,
                'matematika'       => $matematika,
                'ipa'              => $ipa,
                'ips'              => $ips,
                'agama'            => $agama,
                'doa_iftitah'      => $doaIftitah,
                'tahiyat_awal'     => $tahiyatAwal,
                'qunut'            => $qunut,
                'membaca_al_quran' => $membacaQuran,
                'fatihah_4'        => $fatihah4,
                'surah_pendek'     => $surahPendek,
                'doa'              => $doa,
                'menulis'          => $menulis,
                'tanggal_input'    => now()->toDateString(),
                'status_hasil'     => $statusHasil,
            ]
        );
        if ($statusHasil === 'lulus') {
    $siswa->update([
        'status' => 'lulus'
    ]);
} else {
    $siswa->update([
        'status' => 'ditolak'
    ]);
}
    }
}