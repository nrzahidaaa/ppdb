<?php

namespace App\Http\Controllers;

use App\Helpers\TahunAjaranHelper;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
public function index()
{
    $tahunAjaranId = \App\Helpers\TahunAjaranHelper::getSelectedId();

    $kelas = \App\Models\Kelas::withCount(['siswa' => function ($q) use ($tahunAjaranId) {
            $q->where('tahun_ajaran_id', $tahunAjaranId);
        }])
        ->where('tahun_ajaran_id', $tahunAjaranId)
        ->latest()
        ->get();

    return view('kelas.index', compact('kelas'));
}

public function store(Request $request)
{
    $request->validate([
        'nama_kelas' => 'required|string|max:100',
        'jurusan' => 'nullable|string|max:100',
        'wali_kelas' => 'nullable|string|max:100',
        'kuota' => 'required|integer|min:1',
    ]);

    $tahunAjaranId = \App\Helpers\TahunAjaranHelper::getSelectedId();

    \App\Models\Kelas::create([
        'nama_kelas' => $request->nama_kelas,
        'jurusan' => $request->jurusan,
        'wali_kelas' => $request->wali_kelas,
        'kuota' => $request->kuota,
        'tahun_ajaran_id' => $tahunAjaranId,
    ]);

    return redirect()->back()->with('success', 'Data kelas berhasil ditambahkan.');
}

public function update(Request $request, $id)
{
    $kelas = \App\Models\Kelas::findOrFail($id);

    $request->validate([
        'nama_kelas' => 'required|string|max:100',
        'wali_kelas' => 'nullable|string|max:100',
        'kuota' => 'required|integer|min:1|max:50',
    ]);

    $kelas->update([
        'nama_kelas' => $request->nama_kelas,
        'wali_kelas' => $request->wali_kelas,
        'kuota' => $request->kuota,
    ]);

    return redirect()->back()->with('success', 'Data kelas berhasil diperbarui.');
}

}