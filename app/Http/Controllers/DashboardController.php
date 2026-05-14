<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\NilaiTes;
use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Helpers\TahunAjaranHelper;

class DashboardController extends Controller
{
public function index()
{
   $tahunAjaranId = TahunAjaranHelper::getSelectedId();

$queryPendaftaran = Pendaftaran::when($tahunAjaranId, function ($q) use ($tahunAjaranId) {
    $q->where('tahun_ajaran_id', $tahunAjaranId);
});

$queryKelas = Kelas::when($tahunAjaranId, function ($q) use ($tahunAjaranId) {
    $q->where('tahun_ajaran_id', $tahunAjaranId);
});

$queryNilaiTes = NilaiTes::whereHas('pendaftaran', function ($q) use ($tahunAjaranId) {
    $q->where('tahun_ajaran_id', $tahunAjaranId);
});

    // Statistik utama
    $totalPendaftar  = (clone $queryPendaftaran)->count();
    $totalLulus      = (clone $queryPendaftaran)->where('status', 'lulus')->count();
    $totalDitolak    = (clone $queryPendaftaran)->where('status', 'ditolak')->count();
    $totalPending    = (clone $queryPendaftaran)->where('status', 'pending')->count();
    $totalVerifikasi = (clone $queryPendaftaran)->where('status', 'verifikasi')->count();

    $totalKelas    = (clone $queryKelas)->count();
    $totalKuota    = (clone $queryKelas)->sum('kuota');
    $totalNilaiTes = (clone $queryNilaiTes)->count();

    // Predikat
    $totalUnggul = (clone $queryPendaftaran)->where('predikat', 'Unggul')->count();
    $totalBaik   = (clone $queryPendaftaran)->where('predikat', 'Baik')->count();
    $totalCukup  = (clone $queryPendaftaran)->where('predikat', 'Cukup')->count();

    // Sudah/belum dapat kelas
    $sudahKelas = (clone $queryPendaftaran)
        ->where('status', 'lulus')
        ->whereNotNull('id_kelas')
        ->count();

    $belumKelas = (clone $queryPendaftaran)
        ->where('status', 'lulus')
        ->whereNull('id_kelas')
        ->count();

    // Pendaftar terbaru
    $pendaftarTerbaru = (clone $queryPendaftaran)
        ->latest()
        ->take(5)
        ->get();

    // Tren pendaftaran per minggu
    $tren = [];
    for ($i = 4; $i >= 0; $i--) {
        $start = now()->subWeeks($i)->startOfWeek();
        $end   = now()->subWeeks($i)->endOfWeek();

        $tren[] = [
            'minggu' => 'M' . (5 - $i),
            'total'  => (clone $queryPendaftaran)
                ->whereBetween('created_at', [$start, $end])
                ->count(),
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