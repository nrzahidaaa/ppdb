<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TemplatePendaftaranExport implements WithHeadings, WithStyles, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'Nama',
            'NISN',
            'NIK',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'Hobi',
            'Cita-cita',
            'Anak Ke',
            'Jumlah Saudara',
            'Status Tinggal',
            'No Telp',
            'Alamat',
            'Desa/Kelurahan',
            'Kecamatan',
            'Kabupaten/Kota',
            'Kode Pos',
            'Asal Sekolah',
            'Jenis Sekolah',
            'Status Sekolah',
            'NPSN Sekolah',
            'No KK',
            'Nama Kepala Keluarga',
            'Status Kepemilikan Rumah',
            'Nama Ayah',
            'NIK Ayah',
            'Status Ayah',
            'Pendidikan Ayah',
            'Pekerjaan Ayah',
            'Penghasilan Ayah',
            'No HP Ayah',
            'Nama Ibu',
            'NIK Ibu',
            'Status Ibu',
            'Pendidikan Ibu',
            'Pekerjaan Ibu',
            'Penghasilan Ibu',
            'No HP Ibu',
            'Nama Wali',
            'NIK Wali',
            'Status Wali',
            'Pendidikan Wali',
            'Pekerjaan Wali',
            'Penghasilan Wali',
            'No HP Wali',
            'Jalur',
            'Nama Orang Tua',
            'No KKS',
            'No PKH',
            'No KIP',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Bold header
        $sheet->getStyle('A1:AY1')->getFont()->setBold(true);

        // Background warna header
        $sheet->getStyle('A1:AY1')->getFill()->applyFromArray([
            'fillType' => 'solid',
            'startColor' => [
                'rgb' => 'D9E1F2'
            ]
        ]);

        return [];
    }
}