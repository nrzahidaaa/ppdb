<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftaran;
    use App\Imports\PendaftaranImport;

class PendaftaranController extends Controller
{
    public function index(Request $request)
{
    $query = Pendaftaran::query();

    if ($request->filled('search')) {
        $query->where('nama', 'like', '%'.$request->search.'%')
            ->orWhere('nomor_pendaftaran', 'like', '%'.$request->search.'%');
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('jurusan')) {
        $query->where('pilihan_jurusan', $request->jurusan);
    }

    $pendaftaran = $query->latest()->paginate(15);

    return view('pendaftaran.index', compact('pendaftaran'));
}

    public function create()
    {
        return view('pendaftaran.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no'       => 'nullable|string|max:20',
            'nomor_pendaftaran'       => 'nullable|string|max:20',
            'nama'          => 'required|string|max:100',
            'tempat_lahir'  => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            // 'jenis_kelamin' => 'required|in:L,P',
            'nama_orang_tua'=> 'nullable|string|max:100',
            // 'nisn'          => 'nullable|string|max:20|unique:pendaftarans,nisn',
            'asal_sekolah'  => 'required|string|max:150',
            'alamat'        => 'nullable|string',
            'jalur'         => 'required|in:reguler,prestasi',
            // 'pilihan_jurusan'=> 'nullable|in:MIPA,IPS,Bahasa',
            // 'nilai_rata_rata'=> 'nullable|numeric',
            // 'no_telp'       => 'nullable|string|max:20',
            'status'        => 'nullable|in:pending,verifikasi,lulus,ditolak',
        ]);

        
    $validated['nomor_pendaftaran'] = 'PPDB-2025-' . str_pad(
        Pendaftaran::count() + 1, 4, '0', STR_PAD_LEFT
    );
    $validated['status'] = 'pending';
    $validated['berkas_lengkap'] = false;

    Pendaftaran::create($validated);

    return redirect()->route('pendaftaran.index')
                    ->with('success', 'Data pendaftaran berhasil ditambahkan!');
    }

    public function show($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
         return redirect()->route('pendaftaran.edit', $id);
    }

    public function edit($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        return view('pendaftaran.edit', compact('pendaftaran'));
    }

    public function update(Request $request, $id)
    {
       $pendaftaran = Pendaftaran::findOrFail($id);

    $validated = $request->validate([
        'nama'          => 'required|string|max:100',
        'tempat_lahir'  => 'nullable|string|max:100',
        'tanggal_lahir' => 'nullable|date',
        'jenis_kelamin' => 'required|in:L,P',
        'nama_orang_tua'=> 'nullable|string|max:100',
        // 'nisn'          => 'nullable|string|max:20|unique:pendaftarans,nisn',
        'asal_sekolah'  => 'required|string|max:150',
        'alamat'        => 'nullable|string',
        'jalur'         => 'required|in:reguler,prestasi',
        // 'pilihan_jurusan'=> 'nullable|in:MIPA,IPS,Bahasa',
        // 'nilai_rata_rata'=> 'nullable|numeric',
        // 'no_telp'       => 'nullable|string|max:20',
        'status'        => 'nullable|in:pending,verifikasi,lulus,ditolak',
    ]);

    $pendaftaran->update($validated);

    return redirect()->route('pendaftaran.index')
                     ->with('success', 'Data pendaftaran berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->delete();
        return redirect()->route('pendaftaran.index')
                         ->with('success', 'Data pendaftaran berhasil dihapus.');
    }



public function importExcel(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls',
    ]);

    \Maatwebsite\Excel\Facades\Excel::import(new PendaftaranImport, $request->file('file'));

    return redirect()->route('pendaftaran.index')->with('success', 'Data berhasil diimport!');
}
public function formPublik()
{
    return view('pendaftaran.publik');
}

public function storePublik(Request $request)
{
    $validated = $request->validate([
        'nama'            => 'required|string|max:100',
        'nisn'            => 'required|string|unique:pendaftarans,nisn',
        'tempat_lahir'    => 'required|string',
        'tanggal_lahir'   => 'required|date',
        'jenis_kelamin'   => 'required|in:L,P',
        'asal_sekolah'    => 'required|string|max:100',
        'pilihan_jurusan' => 'required|in:MIPA,IPS,Bahasa',
        'nilai_rata_rata' => 'required|numeric|min:0|max:100',
        'nama_orang_tua'  => 'required|string|max:100',
        'no_telp'         => 'required|string|max:20',
        'alamat'          => 'required|string',
    ]);

    $tahun = now()->format('Y');
    $urutan = Pendaftaran::whereYear('created_at', $tahun)->count() + 1;
    $nomor = 'PPDB-' . $tahun . '-' . str_pad($urutan, 4, '0', STR_PAD_LEFT);
    
    $validated['status'] = 'pending';
    $validated['berkas_lengkap'] = false;

    Pendaftaran::create($validated);

    return redirect()->route('pendaftaran.sukses')
                    ->with('nomor', $validated['nomor_pendaftaran'])
                    ->with('nama', $validated['nama']);
}

public function pengumuman()
{
    return view('pendaftaran.pengumuman');
}

public function cekPengumuman(Request $request)
{
    $request->validate([
        'nomor_pendaftaran' => 'required|string',
    ]);

    $data = Pendaftaran::where('nomor_pendaftaran', $request->nomor_pendaftaran)->first();

    return view('pendaftaran.pengumuman', compact('data'));
}
public function updateStatus(Request $request, $id)
{
    $pendaftaran = Pendaftaran::findOrFail($id);
    $pendaftaran->update(['status' => $request->status]);

    return redirect()->back()->with('success', 'Status berhasil diperbarui!');
}
}

