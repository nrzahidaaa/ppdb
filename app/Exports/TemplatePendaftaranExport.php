<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class TemplatePendaftaranExport implements WithHeadings, WithStyles, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'Nama', 'NISN', 'NIK', 'Tempat Lahir', 'Tanggal Lahir',
            'Jenis Kelamin', 'Hobi', 'Cita-cita', 'Anak Ke',
            'Jumlah Saudara', 'Status Tinggal', 'No Telp', 'Alamat',
            'Desa/Kelurahan', 'Kecamatan', 'Kabupaten/Kota', 'Kode Pos',
            'Asal Sekolah', 'Jenis Sekolah', 'Status Sekolah', 'NPSN Sekolah',
            'No KK', 'Nama Kepala Keluarga', 'Status Kepemilikan Rumah',
            'Nama Ayah', 'NIK Ayah', 'Status Ayah', 'Pendidikan Ayah',
            'Pekerjaan Ayah', 'Penghasilan Ayah', 'No HP Ayah',
            'Nama Ibu', 'NIK Ibu', 'Status Ibu', 'Pendidikan Ibu',
            'Pekerjaan Ibu', 'Penghasilan Ibu', 'No HP Ibu',
            'Nama Wali', 'NIK Wali', 'Status Wali', 'Pendidikan Wali',
            'Pekerjaan Wali', 'Penghasilan Wali', 'No HP Wali',
            'Jalur', 'Nama Orang Tua', 'No KKS', 'No PKH', 'No KIP',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $range = 'A1:AX1';

        $sheet->getStyle($range)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1F4E78'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(35);
        $sheet->freezePane('A2');

        return [];
    }
}