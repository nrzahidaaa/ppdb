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
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                ->orWhere('nomor_pendaftaran', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pendaftaran = $query->latest()->paginate(10)->withQueryString();

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
    $validated['status'] = 'waiting_proses';
    $validated['berkas_lengkap'] = false;

    Pendaftaran::create($validated);

    return redirect()->route('pendaftaran.index')
                    ->with('success', 'Data pendaftaran berhasil ditambahkan!');
    }

    public function show($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        return view('pendaftaran.show', compact('pendaftaran'));
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
        'nama'          => 'required|string|max:100',
        'tempat_lahir'  => 'required|string|max:100',
        'jenis_kelamin' => 'required|in:L,P',
        'asal_sekolah'  => 'required|string|max:150',
        'alamat'        => 'required|string',
        'jalur'         => 'required|in:reguler,prestasi',
        'nama_orang_tua'=> 'required|string|max:100',
        'nisn_file'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'kartu_keluarga'=> 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'akta_kelahiran'=> 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'foto'          => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        'ijazah'        => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    $tahun  = now()->format('Y');
    $urutan = Pendaftaran::whereYear('created_at', $tahun)->count() + 1;
    $nomor  = 'PPDB-' . $tahun . '-' . str_pad($urutan, 4, '0', STR_PAD_LEFT);

    // Upload berkas
    $berkas = [];
    foreach (['nisn_file','kartu_keluarga','akta_kelahiran','foto','ijazah'] as $field) {
        if ($request->hasFile($field)) {
            $berkas[$field] = $request->file($field)->store('berkas/' . $nomor, 'public');
        }
    }

    Pendaftaran::create([
        'nomor_pendaftaran'       => $nomor,
        'nama'                    => $request->nama,
        'nisn'                    => $request->nisn,
        'nik'                     => $request->nik,
        'tempat_lahir'            => $request->tempat_lahir,
        'tanggal_lahir'           => $request->tanggal_lahir,
        'jenis_kelamin'           => $request->jenis_kelamin,
        'hobi'                    => $request->hobi,
        'cita_cita'               => $request->cita_cita,
        'anak_ke'                 => $request->anak_ke,
        'jumlah_saudara'          => $request->jumlah_saudara,
        'status_tinggal'          => $request->status_tinggal,
        'no_telp'                 => $request->no_telp,
        'alamat'                  => $request->alamat,
        'desa_kelurahan'          => $request->desa_kelurahan,
        'kecamatan'               => $request->kecamatan,
        'kabupaten_kota'          => $request->kabupaten_kota,
        'kode_pos'                => $request->kode_pos,
        'asal_sekolah'            => $request->asal_sekolah,
        'jenis_sekolah'           => $request->jenis_sekolah,
        'status_sekolah'          => $request->status_sekolah,
        'npsn_sekolah'            => $request->npsn_sekolah,
        'no_kk'                   => $request->no_kk,
        'nama_kepala_keluarga'    => $request->nama_kepala_keluarga,
        'status_kepemilikan_rumah'=> $request->status_kepemilikan_rumah,
        'nama_ayah'               => $request->nama_ayah,
        'nik_ayah'                => $request->nik_ayah,
        'status_ayah'             => $request->status_ayah,
        'pendidikan_ayah'         => $request->pendidikan_ayah,
        'pekerjaan_ayah'          => $request->pekerjaan_ayah,
        'penghasilan_ayah'        => $request->penghasilan_ayah,
        'no_hp_ayah'              => $request->no_hp_ayah,
        'nama_ibu'                => $request->nama_ibu,
        'nik_ibu'                 => $request->nik_ibu,
        'status_ibu'              => $request->status_ibu,
        'pendidikan_ibu'          => $request->pendidikan_ibu,
        'pekerjaan_ibu'           => $request->pekerjaan_ibu,
        'penghasilan_ibu'         => $request->penghasilan_ibu,
        'no_hp_ibu'               => $request->no_hp_ibu,
        'nama_wali'               => $request->nama_wali,
        'nik_wali'                => $request->nik_wali,
        'status_wali'             => $request->status_wali,
        'pendidikan_wali'         => $request->pendidikan_wali,
        'pekerjaan_wali'          => $request->pekerjaan_wali,
        'penghasilan_wali'        => $request->penghasilan_wali,
        'no_hp_wali'              => $request->no_hp_wali,
        'jalur'                   => $request->jalur,
        'nama_orang_tua'          => $request->nama_orang_tua,
        'no_kks'                  => $request->no_kks,
        'no_pkh'                  => $request->no_pkh,
        'no_kip'                  => $request->no_kip,
        'nisn_file'               => $berkas['nisn_file'] ?? null,
        'kartu_keluarga'          => $berkas['kartu_keluarga'] ?? null,
        'akta_kelahiran'          => $berkas['akta_kelahiran'] ?? null,
        'foto'                    => $berkas['foto'] ?? null,
        'ijazah'                  => $berkas['ijazah'] ?? null,
        'status'                  => 'pending',
        'berkas_lengkap'          => false,
        'pilihan_jurusan'         => 'MIPA',
    ]);

    return redirect()->route('pendaftaran.sukses')
        ->with('nomor', $nomor)
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
    $request->validate([
        'status' => 'required|in:waiting_proses,pending,verifikasi,lulus,ditolak',
        'catatan' => 'nullable|string',
    ]);

    $pendaftaran = Pendaftaran::findOrFail($id);

    $pendaftaran->status = $request->status;
    $pendaftaran->catatan = $request->status === 'pending'
        ? $request->catatan
        : null;

    $pendaftaran->save();

if ($request->status === 'pending' && empty($request->catatan)) {
    return back()->with('error', 'Catatan wajib diisi saat status Pending.');
}
return redirect()->route('pendaftaran.index')
        ->with('success', 'Status pendaftar berhasil diperbarui.');
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
    return view('pendaftaran.form_edit');
}

public function cariEdit(Request $request)
{
    $request->validate([
        'nisn' => 'required|string'
    ]);

    $pendaftaran = Pendaftaran::where('nisn', $request->nisn)->first();

    if (!$pendaftaran) {
        return back()->with('error', 'Data dengan NISN tersebut tidak ditemukan.');
    }

    if (!in_array($pendaftaran->status, ['waiting_proses', 'pending'])) {
        return back()->with('error', 'Data tidak dapat diedit pada status ini.');
    }

    return redirect()->route('pendaftaran.editPublik', $pendaftaran->nisn);
}

public function editPublik($nisn)
{
    $pendaftaran = Pendaftaran::where('nisn', $nisn)->first();

    if (!$pendaftaran) {
        return redirect()->route('pendaftaran.formEdit')
            ->with('error', 'Data dengan NISN tersebut tidak ditemukan.');
    }

    if (!in_array($pendaftaran->status, ['waiting_proses', 'pending'])) {
        return redirect()->route('pendaftaran.formEdit')
            ->with('error', 'Data tidak dapat diedit pada status ini.');
    }

    return view('pendaftaran.edit_publik', compact('pendaftaran'));
}

public function updatePublik(Request $request, $nisn)
{
    $pendaftaran = Pendaftaran::where('nisn', $nisn)->firstOrFail();

    if (!in_array($pendaftaran->status, ['waiting_proses', 'pending'])) {
        return redirect()->route('pengumuman')
            ->with('error', 'Data tidak dapat diperbarui pada status ini.');
    }

    $validated = $request->validate([
        'nama' => 'required|string|max:255',
        'nisn' => 'required|string|max:50',
        'tempat_lahir' => 'nullable|string|max:255',
        'tanggal_lahir' => 'nullable|date',
        'asal_sekolah' => 'nullable|string|max:255',
        'nama_orang_tua' => 'nullable|string|max:255',
        'alamat' => 'nullable|string',
        'no_telp' => 'nullable|string|max:30',

        'nisn_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'kartu_keluarga' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'akta_kelahiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'foto' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        'ijazah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ]);

    if ($request->hasFile('nisn_file')) {
        $validated['nisn_file'] = $request->file('nisn_file')->store('berkas', 'public');
    }

    if ($request->hasFile('kartu_keluarga')) {
        $validated['kartu_keluarga'] = $request->file('kartu_keluarga')->store('berkas', 'public');
    }

    if ($request->hasFile('akta_kelahiran')) {
        $validated['akta_kelahiran'] = $request->file('akta_kelahiran')->store('berkas', 'public');
    }

    if ($request->hasFile('foto')) {
        $validated['foto'] = $request->file('foto')->store('berkas', 'public');
    }

    if ($request->hasFile('ijazah')) {
        $validated['ijazah'] = $request->file('ijazah')->store('berkas', 'public');
    }

    $validated['status'] = 'waiting_proses';
    $validated['catatan'] = null;

    $pendaftaran->update($validated);

    return redirect()->route('pengumuman')
        ->with('success', 'Data berhasil diperbarui dan dikirim ulang untuk diperiksa admin.');
}

}

