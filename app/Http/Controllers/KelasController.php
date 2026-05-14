<?php

namespace App\Http\Controllers;

use App\Helpers\TahunAjaranHelper;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $tahunAjaranId = TahunAjaranHelper::getSelectedId();

        $kelas = Kelas::withCount('siswa')
            ->when($tahunAjaranId, function ($q) use ($tahunAjaranId) {
                $q->where('tahun_ajaran_id', $tahunAjaranId);
            })
            ->latest()
            ->get();

        return view('kelas.index', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:100',
            'wali_kelas' => 'nullable|string|max:100',
            'kuota' => 'nullable|integer|min:1|max:50',
            'kapasitas' => 'nullable|integer|min:1|max:50',
        ]);

        $tahunAjaranId = TahunAjaranHelper::getSelectedId();
        $kuota = $request->kuota ?? $request->kapasitas;

        if (!$tahunAjaranId) {
            return back()->withErrors([
                'tahun_ajaran_id' => 'Tahun ajaran belum dipilih.'
            ])->withInput();
        }

        if (!$kuota) {
            return back()->withErrors([
                'kuota' => 'Kuota/kapasitas wajib diisi.'
            ])->withInput();
        }

        Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'wali_kelas' => $request->wali_kelas,
            'kuota' => $kuota,
            'tahun_ajaran_id' => $tahunAjaranId,
        ]);

        return redirect()->route('kelas.index')
            ->with('success', 'Data kelas berhasil ditambahkan.');
    }

    public function update(Request $request, int $id)
    {
        $kelas = Kelas::findOrFail($id);

        $request->validate([
            'nama_kelas' => 'required|string|max:100',
            'wali_kelas' => 'nullable|string|max:100',
            'kuota' => 'nullable|integer|min:1|max:50',
            'kapasitas' => 'nullable|integer|min:1|max:50',
        ]);

        $kuota = $request->kuota ?? $request->kapasitas;

        if (!$kuota) {
            return back()->withErrors([
                'kuota' => 'Kuota/kapasitas wajib diisi.'
            ])->withInput();
        }

        $kelas->update([
            'nama_kelas' => $request->nama_kelas,
            'wali_kelas' => $request->wali_kelas,
            'kuota' => $kuota,
        ]);

        return redirect()->route('kelas.index')
            ->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        Kelas::findOrFail($id)->delete();

        return redirect()->route('kelas.index')
            ->with('success', 'Data kelas berhasil dihapus.');
    }
}