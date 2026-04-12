<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\NilaiTes;
use App\Models\Kelas;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class LaporanController extends Controller
{
    public function index()
    {
        $totalPendaftar = Pendaftaran::count();
        $totalLulus     = Pendaftaran::where('status', 'lulus')->count();
        $totalDitolak   = Pendaftaran::where('status', 'ditolak')->count();
        $totalPending   = Pendaftaran::where('status', 'pending')->count();
        $totalUnggul    = Pendaftaran::where('predikat', 'Unggul')->count();
        $totalBaik      = Pendaftaran::where('predikat', 'Baik')->count();
        $totalCukup     = Pendaftaran::where('predikat', 'Cukup')->count();

        return view('laporan.index', compact(
            'totalPendaftar', 'totalLulus', 'totalDitolak',
            'totalPending', 'totalUnggul', 'totalBaik', 'totalCukup'
        ));
    }

    // ===== PDF =====

    public function pdfPendaftar()
    {
        $data = Pendaftaran::latest()->get();
        $pdf  = Pdf::loadView('laporan.pdf.pendaftar', compact('data'))
                   ->setPaper('a4', 'landscape');
        return $pdf->download('laporan-pendaftar.pdf');
    }

public function pdfKlasifikasi()
{
    $data = Pendaftaran::whereNotNull('predikat')
        ->with('nilaiTes')
        ->leftJoin('nilai_tes', 'nilai_tes.id_siswa', '=', 'pendaftarans.id')
        ->select('pendaftarans.*')
        ->orderByRaw("
            CASE pendaftarans.predikat
                WHEN 'Unggul' THEN 1
                WHEN 'Baik' THEN 2
                WHEN 'Cukup' THEN 3
                ELSE 4
            END
        ")
        ->orderByDesc('nilai_tes.total_nilai')
        ->get();

    $pdf  = Pdf::loadView('laporan.pdf.klasifikasi', compact('data'))
               ->setPaper('a4', 'landscape');

    return $pdf->download('laporan-klasifikasi.pdf');
}

    public function pdfPembagian(Request $request)
    {
        // Ambil filter kelas dari request, misal ?kelas[]=7A&kelas[]=7B
        $filterKelas = $request->input('kelas', []);

        $query = Kelas::with(['siswa' => function ($q) {
            $q->where('status', 'lulus');
        }]);

        // Jika ada filter kelas tertentu, batasi hanya kelas itu
        if (!empty($filterKelas)) {
            $query->whereIn('nama_kelas', $filterKelas);
        }

        $kelas       = $query->get();
        $filterLabel = !empty($filterKelas) ? implode(', ', $filterKelas) : 'Semua Kelas';

        $pdf = Pdf::loadView('laporan.pdf.pembagian', compact('kelas', 'filterLabel'))
                  ->setPaper('a4', 'portrait');

        $filename = !empty($filterKelas)
            ? 'laporan-pembagian-kelas-' . implode('-', $filterKelas) . '.pdf'
            : 'laporan-pembagian-kelas.pdf';

        return $pdf->download($filename);
    }

    public function pdfNilai(Request $request)
    {
        // Ambil filter kelas dari request, misal ?kelas[]=7A&kelas[]=7B
        $filterKelas = $request->input('kelas', []);

        $query = NilaiTes::with(['siswa' => function ($q) {
            // Jika ada filter, join ke kelas
            if (!empty($GLOBALS['filterKelas'] ?? [])) {
                $q->whereHas('kelas', function ($kq) {
                    $kq->whereIn('nama_kelas', $GLOBALS['filterKelas']);
                });
            }
        }, 'siswa.kelas']);

        // Filter berdasarkan kelas siswa
        if (!empty($filterKelas)) {
            $query->whereHas('siswa.kelas', function ($q) use ($filterKelas) {
                $q->whereIn('nama_kelas', $filterKelas);
            });
        }

        $data        = $query->latest()->get();
        $filterLabel = !empty($filterKelas) ? implode(', ', $filterKelas) : 'Semua Kelas';

        $pdf = Pdf::loadView('laporan.pdf.nilai', compact('data', 'filterLabel'))
                  ->setPaper('a4', 'landscape');

        $filename = !empty($filterKelas)
            ? 'laporan-nilai-tes-' . implode('-', $filterKelas) . '.pdf'
            : 'laporan-nilai-tes.pdf';

        return $pdf->download($filename);
    }

    // ===== EXCEL =====

  public function excelPendaftar()
{
    $data        = Pendaftaran::latest()->get();
    $spreadsheet = new Spreadsheet();
    $sheet       = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Data Pendaftar');

    $this->applyExcelReportHeader(
        $sheet,
        'LAPORAN DATA PENDAFTAR',
        'Tahun Ajaran 2025/2026 | Dicetak: ' . now()->format('d/m/Y H:i'),
        'L'
    );

    $headers = [
        'No', 'No. Pendaftaran', 'Nama', 'NISN', 'Tempat Lahir', 'Tgl Lahir',
        'Jenis Kelamin', 'Asal Sekolah', 'Nilai Rata-rata', 'Nama Ortu', 'No. Telp', 'Status'
    ];

    foreach ($headers as $i => $h) {
        $this->setCell($sheet, $i + 1, 4, $h);
    }

    foreach ($data as $i => $r) {
        $row = $i + 5;
        $this->setCell($sheet, 1,  $row, $i + 1);
        $this->setCell($sheet, 2,  $row, $r->nomor_pendaftaran);
        $this->setCell($sheet, 3,  $row, $r->nama);
        $this->setCell($sheet, 4,  $row, $r->nisn);
        $this->setCell($sheet, 5,  $row, $r->tempat_lahir);
        $this->setCell($sheet, 6,  $row, $r->tanggal_lahir?->format('d/m/Y'));
        $this->setCell($sheet, 7,  $row, $r->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan');
        $this->setCell($sheet, 8,  $row, $r->asal_sekolah);
        $this->setCell($sheet, 9,  $row, $r->nilai_rata_rata);
        $this->setCell($sheet, 10, $row, $r->nama_orang_tua);
        $this->setCell($sheet, 11, $row, $r->no_telp);
        $this->setCell($sheet, 12, $row, ucfirst($r->status));
    }

    $lastRow = $data->count() + 4;

    $this->applyExcelTableHeader($sheet, "A4:L4");
    $this->applyExcelTableBorders($sheet, "A4:L{$lastRow}");
    $this->autoSizeColumns($sheet, 'A', 'L');
    $this->setupExcelPrint($sheet, 'L', $lastRow);

    $this->applyCenterAlignment($sheet, "A4:A{$lastRow}");
    $this->applyCenterAlignment($sheet, "D4:D{$lastRow}");
    $this->applyCenterAlignment($sheet, "F4:G{$lastRow}");
    $this->applyCenterAlignment($sheet, "I4:I{$lastRow}");
    $this->applyCenterAlignment($sheet, "L4:L{$lastRow}");

    $sheet->getStyle("C5:C{$lastRow}")->getFont()->setBold(true);
    $this->applyPredikatStatusColor($sheet, 5, $lastRow, 'Z', 'L');

    return $this->downloadExcel($spreadsheet, 'laporan-pendaftar.xlsx');
}

public function excelKlasifikasi()
{
    $data = Pendaftaran::whereNotNull('predikat')
        ->with('nilaiTes')
        ->leftJoin('nilai_tes', 'nilai_tes.id_siswa', '=', 'pendaftarans.id')
        ->select('pendaftarans.*')
        ->orderByRaw("
            CASE pendaftarans.predikat
                WHEN 'Unggul' THEN 1
                WHEN 'Baik' THEN 2
                WHEN 'Cukup' THEN 3
                ELSE 4
            END
        ")
        ->orderByRaw('COALESCE(nilai_tes.total_nilai, 0) DESC')
        ->get();

    $spreadsheet = new Spreadsheet();
    $sheet       = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Hasil Klasifikasi');

    $this->applyExcelReportHeader(
        $sheet,
        'LAPORAN HASIL KLASIFIKASI NAIVE BAYES',
        'Tahun Ajaran 2025/2026 | Dicetak: ' . now()->format('d/m/Y H:i'),
        'F'
    );

    $headers = ['No', 'Nama', 'NISN', 'Total Nilai', 'Predikat', 'Status'];
    foreach ($headers as $i => $h) {
        $this->setCell($sheet, $i + 1, 4, $h);
    }

    foreach ($data as $i => $r) {
        $n     = $r->nilaiTes;
        $total = $n ? $n->total_nilai : 0;
        $row   = $i + 5;

        $this->setCell($sheet, 1, $row, $i + 1);
        $this->setCell($sheet, 2, $row, $r->nama);
        $this->setCell($sheet, 3, $row, $r->nisn);
        $this->setCell($sheet, 4, $row, $total);
        $this->setCell($sheet, 5, $row, $r->predikat);
        $this->setCell($sheet, 6, $row, ucfirst($r->status));
    }

    $lastRow = $data->count() + 4;

    $this->applyExcelTableHeader($sheet, "A4:F4");
    $this->applyExcelTableBorders($sheet, "A4:F{$lastRow}");
    $this->autoSizeColumns($sheet, 'A', 'F');
    $this->setupExcelPrint($sheet, 'F', $lastRow);

    $this->applyCenterAlignment($sheet, "A4:A{$lastRow}");
    $this->applyCenterAlignment($sheet, "C4:F{$lastRow}");
    $sheet->getStyle("B5:B{$lastRow}")->getFont()->setBold(true);

    $this->applyPredikatStatusColor($sheet, 5, $lastRow, 'E', 'F');

    return $this->downloadExcel($spreadsheet, 'laporan-klasifikasi.xlsx');
}

public function excelPembagian(Request $request)
{
    $filterKelas = $request->input('kelas', []);

    $query = Pendaftaran::where('status', 'lulus')
                ->whereNotNull('id_kelas')
                ->with('kelas');

    if (!empty($filterKelas)) {
        $query->whereHas('kelas', function ($q) use ($filterKelas) {
            $q->whereIn('nama_kelas', $filterKelas);
        });
    }

    $data        = $query->latest()->get();
    $spreadsheet = new Spreadsheet();
    $sheet       = $spreadsheet->getActiveSheet();

    $sheetTitle = !empty($filterKelas) ? 'Kelas ' . implode(' & ', $filterKelas) : 'Pembagian Kelas';
    $sheet->setTitle(substr($sheetTitle, 0, 31));

    $filterLabel = !empty($filterKelas) ? implode(', ', $filterKelas) : 'Semua Kelas';

    $this->applyExcelReportHeader(
        $sheet,
        'LAPORAN PEMBAGIAN KELAS',
        'Filter Kelas: ' . $filterLabel . ' | Dicetak: ' . now()->format('d/m/Y H:i'),
        'E'
    );

    $headers = ['No', 'Nama', 'NISN', 'Predikat', 'Kelas'];
    foreach ($headers as $i => $h) {
        $this->setCell($sheet, $i + 1, 4, $h);
    }

    foreach ($data as $i => $r) {
        $row = $i + 5;
        $this->setCell($sheet, 1, $row, $i + 1);
        $this->setCell($sheet, 2, $row, $r->nama);
        $this->setCell($sheet, 3, $row, $r->nisn);
        $this->setCell($sheet, 4, $row, $r->predikat);
        $this->setCell($sheet, 5, $row, $r->kelas?->nama_kelas);
    }

    $lastRow = $data->count() + 4;

    $this->applyExcelTableHeader($sheet, "A4:E4");
    $this->applyExcelTableBorders($sheet, "A4:E{$lastRow}");
    $this->autoSizeColumns($sheet, 'A', 'E');
    $this->setupExcelPrint($sheet, 'E', $lastRow);

    $this->applyCenterAlignment($sheet, "A4:A{$lastRow}");
    $this->applyCenterAlignment($sheet, "C4:E{$lastRow}");
    $sheet->getStyle("B5:B{$lastRow}")->getFont()->setBold(true);

    $this->applyPredikatStatusColor($sheet, 5, $lastRow, 'D', 'Z');

    $filename = !empty($filterKelas)
        ? 'laporan-pembagian-kelas-' . implode('-', $filterKelas) . '.xlsx'
        : 'laporan-pembagian-kelas.xlsx';

    return $this->downloadExcel($spreadsheet, $filename);
}

public function excelNilai(Request $request)
{
    $filterKelas = $request->input('kelas', []);

    $query = NilaiTes::with('siswa.kelas');

    if (!empty($filterKelas)) {
        $query->whereHas('siswa.kelas', function ($q) use ($filterKelas) {
            $q->whereIn('nama_kelas', $filterKelas);
        });
    }

    $data        = $query->latest()->get();
    $spreadsheet = new Spreadsheet();
    $sheet       = $spreadsheet->getActiveSheet();

    $sheetTitle = !empty($filterKelas) ? 'Nilai Kelas ' . implode(' & ', $filterKelas) : 'Rekap Nilai Tes';
    $sheet->setTitle(substr($sheetTitle, 0, 31));

    $filterLabel = !empty($filterKelas) ? implode(', ', $filterKelas) : 'Semua Kelas';

    $this->applyExcelReportHeader(
        $sheet,
        'LAPORAN REKAP NILAI TES',
        'Filter Kelas: ' . $filterLabel . ' | Dicetak: ' . now()->format('d/m/Y H:i'),
        'O'
    );

    $headers = [
        'No', 'Nama Siswa', 'Kelas', 'IPA', 'IPS', 'Bhs. Indonesia', 'Matematika',
        'Doa Iftitah', 'Tahiyat Awal', 'Qunut', 'Baca Al-Quran',
        'Fatihah 4', 'Doa', 'Menulis', 'Total'
    ];

    foreach ($headers as $i => $h) {
        $this->setCell($sheet, $i + 1, 4, $h);
    }

    foreach ($data as $i => $r) {
        $total = $r->ipa + $r->ips + $r->bhs_indonesia + $r->matematika +
                 $r->doa_iftitah + $r->tahiyat_awal + $r->qunut +
                 $r->membaca_al_quran + $r->fatihah_4 + $r->doa + $r->menulis;

        $row = $i + 5;
        $this->setCell($sheet, 1,  $row, $i + 1);
        $this->setCell($sheet, 2,  $row, $r->siswa?->nama ?? '-');
        $this->setCell($sheet, 3,  $row, $r->siswa?->kelas?->nama_kelas ?? '-');
        $this->setCell($sheet, 4,  $row, $r->ipa);
        $this->setCell($sheet, 5,  $row, $r->ips);
        $this->setCell($sheet, 6,  $row, $r->bhs_indonesia);
        $this->setCell($sheet, 7,  $row, $r->matematika);
        $this->setCell($sheet, 8,  $row, $r->doa_iftitah);
        $this->setCell($sheet, 9,  $row, $r->tahiyat_awal);
        $this->setCell($sheet, 10, $row, $r->qunut);
        $this->setCell($sheet, 11, $row, $r->membaca_al_quran);
        $this->setCell($sheet, 12, $row, $r->fatihah_4);
        $this->setCell($sheet, 13, $row, $r->doa);
        $this->setCell($sheet, 14, $row, $r->menulis);
        $this->setCell($sheet, 15, $row, $total);
    }

    $lastRow = $data->count() + 4;

    $this->applyExcelTableHeader($sheet, "A4:O4");
    $this->applyExcelTableBorders($sheet, "A4:O{$lastRow}");
    $this->autoSizeColumns($sheet, 'A', 'O');
    $this->setupExcelPrint($sheet, 'O', $lastRow);

    $this->applyCenterAlignment($sheet, "A4:A{$lastRow}");
    $this->applyCenterAlignment($sheet, "C4:O{$lastRow}");
    $sheet->getStyle("B5:B{$lastRow}")->getFont()->setBold(true);

    $filename = !empty($filterKelas)
        ? 'laporan-nilai-tes-' . implode('-', $filterKelas) . '.xlsx'
        : 'laporan-nilai-tes.xlsx';

    return $this->downloadExcel($spreadsheet, $filename);
}

    // ===== HELPERS =====

    private function setCell($sheet, int $col, int $row, $value): void
    {
        $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
        $sheet->setCellValue($colLetter . $row, $value);
    }

    private function downloadExcel(Spreadsheet $spreadsheet, string $filename)
    {
        $writer = new Xlsx($spreadsheet);
        $path   = storage_path('app/public/' . $filename);
        $writer->save($path);
        return response()->download($path, $filename)->deleteFileAfterSend(true);
    }

    private function applyExcelReportHeader($sheet, string $title, string $subtitle, string $lastColumn): void
{
    $sheet->mergeCells("A1:{$lastColumn}1");
    $sheet->setCellValue('A1', $title);

    $sheet->mergeCells("A2:{$lastColumn}2");
    $sheet->setCellValue('A2', $subtitle);

    $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
        'font' => [
            'bold' => true,
            'size' => 16,
            'color' => ['argb' => 'FF1F4E78'],
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical'   => Alignment::VERTICAL_CENTER,
        ],
    ]);

    $sheet->getStyle("A2:{$lastColumn}2")->applyFromArray([
        'font' => [
            'size' => 10,
            'color' => ['argb' => 'FF666666'],
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical'   => Alignment::VERTICAL_CENTER,
        ],
    ]);

    $sheet->getRowDimension(1)->setRowHeight(26);
    $sheet->getRowDimension(2)->setRowHeight(20);
}

private function applyExcelTableHeader($sheet, string $range): void
{
    $sheet->getStyle($range)->applyFromArray([
        'font' => [
            'bold' => true,
            'color' => ['argb' => 'FFFFFFFF'],
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['argb' => 'FF2F5597'],
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical'   => Alignment::VERTICAL_CENTER,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => 'FFBFBFBF'],
            ],
        ],
    ]);
}

private function applyExcelTableBorders($sheet, string $range): void
{
    $sheet->getStyle($range)->applyFromArray([
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => 'FFD9D9D9'],
            ],
        ],
    ]);
}

private function autoSizeColumns($sheet, string $startColumn, string $endColumn): void
{
    foreach (range($startColumn, $endColumn) as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }
}

private function setupExcelPrint($sheet, string $lastColumn, int $lastRow): void
{
    $sheet->freezePane('A5');

    $sheet->getPageSetup()
        ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
        ->setPaperSize(PageSetup::PAPERSIZE_A4)
        ->setFitToWidth(1)
        ->setFitToHeight(0);

    $sheet->getPageMargins()->setTop(0.3);
    $sheet->getPageMargins()->setRight(0.2);
    $sheet->getPageMargins()->setLeft(0.2);
    $sheet->getPageMargins()->setBottom(0.3);

    $sheet->getPageSetup()->setPrintArea("A1:{$lastColumn}{$lastRow}");
}

private function applyCenterAlignment($sheet, string $range): void
{
    $sheet->getStyle($range)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle($range)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
}

private function applyPredikatStatusColor($sheet, int $startRow, int $lastRow, string $predikatCol, string $statusCol): void
{
    for ($row = $startRow; $row <= $lastRow; $row++) {
        $predikat = $sheet->getCell("{$predikatCol}{$row}")->getValue();
        $status   = $sheet->getCell("{$statusCol}{$row}")->getValue();

        if ($predikat === 'Unggul') {
            $sheet->getStyle("{$predikatCol}{$row}")->applyFromArray([
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFD9EAD3'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ]);
        } elseif ($predikat === 'Baik') {
            $sheet->getStyle("{$predikatCol}{$row}")->applyFromArray([
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFCFE2F3'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ]);
        } elseif ($predikat === 'Cukup') {
            $sheet->getStyle("{$predikatCol}{$row}")->applyFromArray([
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFFCE5CD'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ]);
        }

        if ($status === 'Lulus') {
            $sheet->getStyle("{$statusCol}{$row}")->applyFromArray([
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFD9EAD3'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ]);
        } elseif ($status === 'Pending') {
            $sheet->getStyle("{$statusCol}{$row}")->applyFromArray([
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFFFF2CC'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ]);
        } elseif ($status === 'Ditolak') {
            $sheet->getStyle("{$statusCol}{$row}")->applyFromArray([
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFF4CCCC'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ]);
        }
    }
}
}
