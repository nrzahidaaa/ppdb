<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\NilaiTes;
use App\Models\Kelas;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Http\Request;

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
        $data = Pendaftaran::whereNotNull('predikat')->with('nilaiTes')->latest()->get();
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

        $headers = ['No', 'No. Pendaftaran', 'Nama', 'NISN', 'Tempat Lahir', 'Tgl Lahir',
                    'Jenis Kelamin', 'Asal Sekolah', 'Nilai Rata-rata', 'Nama Ortu', 'No. Telp', 'Status'];
        foreach ($headers as $i => $h) {
            $this->setCell($sheet, $i + 1, 1, $h);
        }

        foreach ($data as $i => $r) {
            $row = $i + 2;
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

        return $this->downloadExcel($spreadsheet, 'laporan-pendaftar.xlsx');
    }

    public function excelKlasifikasi()
    {
        $data        = Pendaftaran::whereNotNull('predikat')->with('nilaiTes')->latest()->get();
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Hasil Klasifikasi');

        $headers = ['No', 'Nama', 'NISN', 'Total Nilai', 'Predikat', 'Status'];
        foreach ($headers as $i => $h) {
            $this->setCell($sheet, $i + 1, 1, $h);
        }

        foreach ($data as $i => $r) {
            $n     = $r->nilaiTes;
            $total = $n ? ($n->ipa + $n->ips + $n->bhs_indonesia + $n->matematika +
                           $n->doa_iftitah + $n->tahiyat_awal + $n->qunut +
                           $n->membaca_al_quran + $n->fatihah_4 + $n->doa + $n->menulis) : 0;
            $row = $i + 2;
            $this->setCell($sheet, 1, $row, $i + 1);
            $this->setCell($sheet, 2, $row, $r->nama);
            $this->setCell($sheet, 3, $row, $r->nisn);
            $this->setCell($sheet, 4, $row, $total);
            $this->setCell($sheet, 5, $row, $r->predikat);
            $this->setCell($sheet, 6, $row, ucfirst($r->status));
        }

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
        $sheet->setTitle(substr($sheetTitle, 0, 31)); // max 31 karakter untuk nama sheet Excel

        $headers = ['No', 'Nama', 'NISN', 'Predikat', 'Kelas'];
        foreach ($headers as $i => $h) {
            $this->setCell($sheet, $i + 1, 1, $h);
        }

        foreach ($data as $i => $r) {
            $row = $i + 2;
            $this->setCell($sheet, 1, $row, $i + 1);
            $this->setCell($sheet, 2, $row, $r->nama);
            $this->setCell($sheet, 3, $row, $r->nisn);
            $this->setCell($sheet, 4, $row, $r->predikat);
            $this->setCell($sheet, 5, $row, $r->kelas?->nama_kelas);
        }

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

        $headers = ['No', 'Nama Siswa', 'Kelas', 'IPA', 'IPS', 'Bhs. Indonesia', 'Matematika',
                    'Doa Iftitah', 'Tahiyat Awal', 'Qunut', 'Baca Al-Quran',
                    'Fatihah 4', 'Doa', 'Menulis', 'Total'];
        foreach ($headers as $i => $h) {
            $this->setCell($sheet, $i + 1, 1, $h);
        }

        foreach ($data as $i => $r) {
            $total = $r->ipa + $r->ips + $r->bhs_indonesia + $r->matematika +
                     $r->doa_iftitah + $r->tahiyat_awal + $r->qunut +
                     $r->membaca_al_quran + $r->fatihah_4 + $r->doa + $r->menulis;
            $row = $i + 2;
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
}
