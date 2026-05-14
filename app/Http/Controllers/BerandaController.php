<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Helpers\TahunAjaranHelper;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index()
{
    $tahunAjaranId = TahunAjaranHelper::getSelectedId();

    $tahunAjaranAktif = TahunAjaran::find($tahunAjaranId);

    $totalPendaftar = Pendaftaran::where('tahun_ajaran_id', $tahunAjaranId)->count();

    $kuotaTersedia = Kelas::where('tahun_ajaran_id', $tahunAjaranId)
        ->sum('kuota');

    $jumlahKelas = Kelas::where('tahun_ajaran_id', $tahunAjaranId)
        ->count();

    $terverifikasi = Pendaftaran::where('tahun_ajaran_id', $tahunAjaranId)
        ->where('status', 'lulus')
        ->count();

    $belumLengkap = Pendaftaran::where('tahun_ajaran_id', $tahunAjaranId)
        ->where('status_berkas', 'belum_lengkap')
        ->count();

    return view('beranda.index', compact(
        'tahunAjaranAktif',
        'totalPendaftar',
        'kuotaTersedia',
        'jumlahKelas',
        'terverifikasi',
        'belumLengkap'
    ));
}
}
