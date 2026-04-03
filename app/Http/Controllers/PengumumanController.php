<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Models\NilaiTes;

class PengumumanController extends Controller
{
    public function index()
    {
        return view('pengumuman.index');
    }

    public function cek(Request $request)
    {
        $request->validate([
            'nisn' => 'required'
        ]);

        $pendaftaran = Pendaftaran::where('nisn', $request->nisn)->first();

        if (!$pendaftaran) {
            return back()->with('error', 'NISN tidak ditemukan');
        }

        $nilaiTes = NilaiTes::where('id_siswa', $pendaftaran->id)->first();

        if (!$nilaiTes) {
            return back()->with('error', 'Data nilai tes belum tersedia');
        }

        $predikat = null;

        if ($nilaiTes->status_hasil === 'lulus') {
            $predikat = $pendaftaran->predikat;
        }

        return view('pengumuman.hasil', compact('pendaftaran', 'nilaiTes', 'predikat'));
    }

    public function pengumuman()
{
    return view('pendaftaran.pengumuman');
}

public function cekPengumuman(Request $request)
{
    $request->validate([
        'nisn' => 'required'
    ]);

    $pendaftaran = Pendaftaran::where('nisn', $request->nisn)->first();

    if (!$pendaftaran) {
        return back()->with('error', 'NISN tidak ditemukan');
    }

    $nilaiTes = NilaiTes::where('id_siswa', $pendaftaran->id)->first();

    if (!$nilaiTes) {
        return back()->with('error', 'Data nilai tes belum tersedia');
    }

    $predikat = null;

    if ($nilaiTes->status_hasil === 'lulus') {
        $predikat = $pendaftaran->predikat;
    }

    return view('pendaftaran.pengumuman', compact('pendaftaran', 'nilaiTes', 'predikat'));
}
}