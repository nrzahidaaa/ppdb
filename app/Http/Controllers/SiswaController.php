<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Request $request)
{
    $query = Pendaftaran::where('status', 'lulus');

    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('nama', 'like', '%'.$request->search.'%')
              ->orWhere('nisn', 'like', '%'.$request->search.'%');
        });
    }

    if ($request->filled('jurusan')) {
        $query->where('pilihan_jurusan', $request->jurusan);
    }

    $siswa = $query->latest()->paginate(15);
    return view('siswa.index', compact('siswa'));
}
}