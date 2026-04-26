<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahunAjarans = TahunAjaran::latest()->get();
        return view('tahun_ajaran.index', compact('tahunAjarans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_tahun_ajaran' => 'required|string|unique:tahun_ajarans,nama_tahun_ajaran',
        ]);

        TahunAjaran::create([
            'nama_tahun_ajaran' => $request->nama_tahun_ajaran,
            'is_active' => false,
        ]);

        return redirect()->back()->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function toggle($id)
{
    $tahun = TahunAjaran::findOrFail($id);

    if ($tahun->is_active) {
        // kalau sedang aktif → jadi nonaktif
        $tahun->update(['is_active' => false]);

        return back()->with('success', 'Tahun ajaran dinonaktifkan.');
    } else {
        // kalau mau aktifkan → nonaktifkan semua dulu
        TahunAjaran::query()->update(['is_active' => false]);

        $tahun->update(['is_active' => true]);

        session(['selected_tahun_ajaran_id' => $tahun->id]);

        return back()->with('success', 'Tahun ajaran berhasil diaktifkan.');
    }
}

    public function pilih(Request $request)
    {
        $request->validate([
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
        ]);

        session([
            'selected_tahun_ajaran_id' => $request->tahun_ajaran_id
        ]);

        return redirect()->back()->with('success', 'Tahun ajaran berhasil dipilih.');
    }
}