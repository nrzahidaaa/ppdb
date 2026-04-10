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
            'nisn'          => 'nullable|string|max:20|unique:pendaftarans,nisn',
            'tempat_lahir'  => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'nama_orang_tua'=> 'nullable|string|max:100',
            'asal_sekolah'  => 'required|string|max:150',
            'alamat'        => 'nullable|string',
            'jalur'         => 'required|in:reguler,prestasi',
            'no_telp'       => 'nullable|string|max:20',
            'status'        => 'nullable|in:pending,verifikasi,lulus,ditolak',

                'berkas_lengkap'    => false,

            'nisn_file'         => $berkas['nisn_file'] ?? null,
            'kartu_keluarga'    => $berkas['kartu_keluarga'] ?? null,
            'akta_kelahiran'    => $berkas['akta_kelahiran'] ?? null,
            'foto'              => $berkas['foto'] ?? null,
            'ijazah'            => $berkas['ijazah'] ?? null,
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
        'nisn'          => 'nullable|string|max:20|unique:pendaftarans,nisn',
        'asal_sekolah'  => 'required|string|max:150',
        'alamat'        => 'nullable|string',
        'jalur'         => 'required|in:reguler,prestasi',
        'no_telp'       => 'nullable|string|max:20',
        'status'        => 'nullable|in:pending,verifikasi,lulus,ditolak',

            'berkas_lengkap'    => false,

        'nisn_file'         => $berkas['nisn_file'] ?? null,
        'kartu_keluarga'    => $berkas['kartu_keluarga'] ?? null,
        'akta_kelahiran'    => $berkas['akta_kelahiran'] ?? null,
        'foto'              => $berkas['foto'] ?? null,
        'ijazah'            => $berkas['ijazah'] ?? null,
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
    $request->validate([
        'nama'            => 'required|string|max:100',
        'nisn'            => 'required|string|unique:pendaftarans,nisn',
        'tempat_lahir'    => 'required|string',
        'tanggal_lahir'   => 'required|date',
        'jenis_kelamin'   => 'required|in:L,P',
        'asal_sekolah'    => 'required|string|max:100',
        'jalur'           => 'required|in:reguler,prestasi',
        'nama_orang_tua'  => 'required|string|max:100',
        'no_telp'         => 'required|string|max:20',
        'alamat'          => 'required|string',
        'nisn_file'       => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'kartu_keluarga'  => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'akta_kelahiran'  => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'foto'            => 'required|file|mimes:jpg,jpeg,png|max:2048',
        'ijazah'          => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    $tahun  = now()->format('Y');
    $urutan = Pendaftaran::whereYear('created_at', $tahun)->count() + 1;
    $nomor  = 'PPDB-' . $tahun . '-' . str_pad($urutan, 4, '0', STR_PAD_LEFT);

    $berkas = [];
    foreach (['nisn_file', 'kartu_keluarga', 'akta_kelahiran', 'foto', 'ijazah'] as $field) {
        if ($request->hasFile($field)) {
            $berkas[$field] = $request->file($field)->store('berkas-pendaftaran', 'public');
        }
    }

    Pendaftaran::create([
        'nomor_pendaftaran' => $nomor,
        'nama'              => $request->nama,
        'nisn'              => $request->nisn,
        'tempat_lahir'      => $request->tempat_lahir,
        'tanggal_lahir'     => $request->tanggal_lahir,
        'jenis_kelamin'     => $request->jenis_kelamin,
        'asal_sekolah'      => $request->asal_sekolah,
        'nama_orang_tua'    => $request->nama_orang_tua,
        'jalur'             => $request->jalur,
        'no_telp'           => $request->no_telp,
        'alamat'            => $request->alamat,
        'status'            => 'pending',
        'berkas_lengkap'    =>
            !empty($berkas['nisn_file']) &&
            !empty($berkas['kartu_keluarga']) &&
            !empty($berkas['akta_kelahiran']) &&
            !empty($berkas['foto']) &&
            !empty($berkas['ijazah']),
        'nisn_file'         => $berkas['nisn_file'] ?? null,
        'kartu_keluarga'    => $berkas['kartu_keluarga'] ?? null,
        'akta_kelahiran'    => $berkas['akta_kelahiran'] ?? null,
        'foto'              => $berkas['foto'] ?? null,
        'ijazah'            => $berkas['ijazah'] ?? null,
    ]);

    return redirect()->route('pendaftaran.sukses')
        ->with('nisn', $request->nisn)
        ->with('nama', $request->nama);
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

    $data = Pendaftaran::with(['kelas', 'nilaiTes'])->where('nisn', $request->nisn)->first();

    if (!$data) {
        return back()->with('error', 'NISN tidak ditemukan')->withInput();
    }

    if ($data->nilaiTes && $data->nilaiTes->status_hasil) {
        $data->status = $data->nilaiTes->status_hasil;
    }

    return view('pendaftaran.pengumuman', compact('data'));
}

public function updateStatus(Request $request, $id)
{
    $pendaftaran = Pendaftaran::findOrFail($id);
    $pendaftaran->update(['status' => $request->status]);

    return redirect()->back()->with('success', 'Status berhasil diperbarui!');
}

public function revisi(Request $request, $id)
{
    $pendaftaran = Pendaftaran::findOrFail($id);  

    $files = ['ijazah', 'kartu_keluarga', 'akta_kelahiran', 'foto'];
    foreach ($files as $file) {
        if ($request->hasFile($file)) {
            $path = $request->file($file)->store('berkas', 'public');
            $pendaftaran->$file = $path;
        }
    }

    $pendaftaran->status = 'verifikasi'; // balik ke verifikasi setelah revisi
    $pendaftaran->save();

    return redirect()->route('pengumuman')->with('success', 'Berkas revisi berhasil dikirim. Silakan tunggu verifikasi panitia.');
}
public function berkas($id)
{
    $pendaftaran = Pendaftaran::findOrFail($id);
    return view('pendaftaran.berkas', compact('pendaftaran'));
}

public function formEdit()
{
    return view('pendaftaran.edit_publik');
}

public function cariEdit(Request $request)
{
    $request->validate(['nisn' => 'required|string']);
    
    $data = Pendaftaran::where('nisn', $request->nisn)->first();
    
    if (!$data) {
        return back()->with('error', 'NISN tidak ditemukan!');
    }

    if ($data->status !== 'pending') {

        return back()->with('error', 'Data tidak dapat diedit karena sudah diproses!');
    }

return redirect()->route('pendaftaran.editPublik', $data->nisn);}

public function editPublik($nisn)
{
    $data = Pendaftaran::where('nisn', $nisn)->firstOrFail();

    if ($data->status !== 'pending') {
        return redirect()->route('beranda')->with('error', 'Data tidak dapat diedit karena sudah diproses!');
    }

    return view('pendaftaran.edit_publik_form', compact('data'));
}

public function updatePublik(Request $request, $nomor)
{
    $data = Pendaftaran::where('nisn', $nisn)->firstOrFail();

    if ($data->status !== 'pending') {
        return redirect()->route('beranda')->with('error', 'Data tidak dapat diedit!');
    }

    $request->validate([
        'nama'           => 'required|string|max:100',
        'tempat_lahir'   => 'nullable|string|max:100',
        'tanggal_lahir'  => 'nullable|date',
        'jenis_kelamin'  => 'required|in:L,P',
        'nama_orang_tua' => 'nullable|string|max:100',
        'asal_sekolah'   => 'required|string|max:150',
        'alamat'         => 'nullable|string',
        'jalur'          => 'required|in:reguler,prestasi',
        'no_telp'        => 'nullable|string|max:20',
    ]);

    $data->update([
        'nama'           => $request->nama,
        'tempat_lahir'   => $request->tempat_lahir,
        'tanggal_lahir'  => $request->tanggal_lahir,
        'jenis_kelamin'  => $request->jenis_kelamin,
        'nama_orang_tua' => $request->nama_orang_tua,
        'asal_sekolah'   => $request->asal_sekolah,
        'alamat'         => $request->alamat,
        'jalur'          => $request->jalur,
        'no_telp'        => $request->no_telp,
    ]);

    return redirect()->route('pendaftaran.sukses')
        ->with('nomor', $nomor)
        ->with('nama', $data->nama)
        ->with('pesan', 'Data berhasil diperbarui!');
}

}

