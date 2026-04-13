<?php

namespace App\Exports;

use App\Models\Pendaftaran;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class NilaiTesTemplateExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    public function collection()
    {
        return Pendaftaran::select('nama', 'nisn')
            ->orderBy('nama', 'asc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Nama',
            'NISN',
            'IPA',
            'IPS',
            'Bhs Indonesia',
            'Matematika',
            'Agama',
            'Doa Iftitah',
            'Tahiyat Awal',
            'Qunut',
            'Membaca Al-Quran',
            'Fatihah 4',
            'Surah Pendek',
            'Doa',
            'Menulis',
        ];
    }

    public function map($row): array
    {
        return [
            $row->nama,
            $row->nisn,
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // Geser isi data mulai dari baris 4
                $sheet->insertNewRowBefore(1, 3);

                // Judul
                $sheet->mergeCells('A1:O1');
                $sheet->setCellValue('A1', 'TEMPLATE INPUT NILAI TES');

                $sheet->mergeCells('A2:O2');
                $sheet->setCellValue('A2', 'Kolom Nama dan NISN tidak diubah. Isi nilai dengan angka 0 sampai 100.');

                // Header sekarang ada di baris 4
                $headerRow = 4;
                $lastRow = $highestRow + 3;

                // Style judul
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                        'color' => ['argb' => 'FF1F4E78'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('A2')->applyFromArray([
                    'font' => [
                        'size' => 10,
                        'italic' => true,
                        'color' => ['argb' => 'FF666666'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Header style
                $sheet->getStyle("A4:O4")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'FFFFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF33528A'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FFD9D9D9'],
                        ],
                    ],
                ]);

                // Warna kolom identitas
                $sheet->getStyle("A5:B{$lastRow}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFF3F6FB'],
                    ],
                ]);

                // Border semua tabel
                $sheet->getStyle("A4:O{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FFD9D9D9'],
                        ],
                    ],
                ]);

                // Alignment
                $sheet->getStyle("B5:O{$lastRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Auto width
                foreach (range('A', 'O') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Tinggi row
                $sheet->getRowDimension(1)->setRowHeight(24);
                $sheet->getRowDimension(4)->setRowHeight(28);

                // Freeze pane
                $sheet->freezePane('A5');

                // Validasi nilai 0-100 untuk kolom C sampai O
                for ($row = 5; $row <= $lastRow; $row++) {
                    foreach (range('C', 'O') as $col) {
                        $validation = $sheet->getCell($col . $row)->getDataValidation();
                        $validation->setType(DataValidation::TYPE_DECIMAL);
                        $validation->setErrorStyle(DataValidation::STYLE_STOP);
                        $validation->setAllowBlank(true);
                        $validation->setShowInputMessage(true);
                        $validation->setShowErrorMessage(true);
                        $validation->setErrorTitle('Input tidak valid');
                        $validation->setError('Nilai harus berupa angka antara 0 sampai 100.');
                        $validation->setPromptTitle('Input nilai');
                        $validation->setPrompt('Masukkan angka 0 sampai 100.');
                        $validation->setOperator(DataValidation::OPERATOR_BETWEEN);
                        $validation->setFormula1('0');
                        $validation->setFormula2('100');
                    }
                }

                // Print setup
                $sheet->getPageSetup()
                    ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
                    ->setPaperSize(PageSetup::PAPERSIZE_A4);

                $sheet->getPageMargins()->setTop(0.3);
                $sheet->getPageMargins()->setRight(0.2);
                $sheet->getPageMargins()->setLeft(0.2);
                $sheet->getPageMargins()->setBottom(0.3);
            },
        ];
    }
}