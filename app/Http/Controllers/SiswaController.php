<?php

namespace App\Http\Controllers;

use App\Helpers\TahunAjaranHelper;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $tahunAjaranId = TahunAjaranHelper::getSelectedId();

        $query = Pendaftaran::where('status', 'lulus')
            ->where('tahun_ajaran_id', $tahunAjaranId);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nisn', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('jurusan')) {
            $query->where('pilihan_jurusan', $request->jurusan);
        }

        $siswa = $query->latest()->paginate(15)->withQueryString();

        return view('siswa.index', compact('siswa'));
    }
}