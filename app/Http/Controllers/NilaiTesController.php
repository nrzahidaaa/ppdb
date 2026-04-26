<?php

namespace App\Http\Controllers;

use App\Helpers\TahunAjaranHelper;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use App\Imports\NilaiTesImport;
use Maatwebsite\Excel\Facades\Excel;

class NilaiTesController extends Controller
{
    public function index()
    {
        $tahunAjaranId = TahunAjaranHelper::getSelectedId();

        $pendaftaran = Pendaftaran::with('nilaiTes')
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->orderBy('nama')
            ->get();

        $nilaiTes = Pendaftaran::with('nilaiTes')
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->whereHas('nilaiTes')
            ->latest()
            ->paginate(15);

        $totalNilaiTes = Pendaftaran::where('tahun_ajaran_id', $tahunAjaranId)
            ->whereHas('nilaiTes')
            ->count();

        $totalLulus = Pendaftaran::where('tahun_ajaran_id', $tahunAjaranId)
            ->whereHas('nilaiTes', function ($q) {
                $q->where('status_hasil', 'lulus');
            })
            ->count();

        $totalTidakLulus = Pendaftaran::where('tahun_ajaran_id', $tahunAjaranId)
            ->whereHas('nilaiTes', function ($q) {
                $q->whereIn('status_hasil', ['ditolak', 'tidak_lulus']);
            })
            ->count();

        $totalBelumDinilai = Pendaftaran::where('tahun_ajaran_id', $tahunAjaranId)
            ->whereHas('nilaiTes', function ($q) {
                $q->whereNull('status_hasil');
            })
            ->count();

        return view('nilai_tes.index', compact(
            'nilaiTes',
            'pendaftaran',
            'totalNilaiTes',
            'totalLulus',
            'totalTidakLulus',
            'totalBelumDinilai'
        ));
    }

        public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new NilaiTesImport, $request->file('file'));

        return redirect()->route('nilai-tes.index')->with('success', 'Data nilai tes berhasil diimport.');
    }
}