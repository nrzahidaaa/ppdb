<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\NilaiTes;
use App\Models\Kelas;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik utama
        $totalPendaftar  = Pendaftaran::count();
        $totalLulus      = Pendaftaran::where('status', 'lulus')->count();
        $totalDitolak    = Pendaftaran::where('status', 'ditolak')->count();
        $totalPending    = Pendaftaran::where('status', 'pending')->count();
        $totalVerifikasi = Pendaftaran::where('status', 'verifikasi')->count();
        $totalKelas      = Kelas::count();
        $totalKuota = Kelas::sum('kuota');
        $totalNilaiTes   = NilaiTes::count();

        // Predikat
        $totalUnggul = Pendaftaran::where('predikat', 'Unggul')->count();
        $totalBaik   = Pendaftaran::where('predikat', 'Baik')->count();
        $totalCukup  = Pendaftaran::where('predikat', 'Cukup')->count();

        // Sudah dapat kelas
        $sudahKelas  = Pendaftaran::where('status', 'lulus')->whereNotNull('id_kelas')->count();
        $belumKelas  = Pendaftaran::where('status', 'lulus')->whereNull('id_kelas')->count();

        // Pendaftar terbaru
        $pendaftarTerbaru = Pendaftaran::latest()->take(5)->get();

        // Tren pendaftaran per minggu (5 minggu terakhir)
        $tren = [];
        for ($i = 4; $i >= 0; $i--) {
            $start = now()->subWeeks($i)->startOfWeek();
            $end   = now()->subWeeks($i)->endOfWeek();
            $tren[] = [
                'minggu' => 'M' . (5 - $i),
                'total'  => Pendaftaran::whereBetween('created_at', [$start, $end])->count(),
            ];
        }

        return view('dashboard.index', compact(
            'totalPendaftar', 'totalLulus', 'totalDitolak', 'totalPending',
            'totalVerifikasi', 'totalKelas', 'totalKuota', 'totalNilaiTes',
            'totalUnggul', 'totalBaik', 'totalCukup',
            'sudahKelas', 'belumKelas',
            'pendaftarTerbaru', 'tren'
        ));
    }
}