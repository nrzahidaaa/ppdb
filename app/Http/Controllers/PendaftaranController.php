<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Imports\PendaftaranImport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\TahunAjaran;
use App\Helpers\TahunAjaranHelper;
use App\Exports\TemplatePendaftaranExport;
use Maatwebsite\Excel\Facades\Excel;

class PendaftaranController extends Controller
{

public function index(Request $request)
{
    $tahunAjaranId = TahunAjaranHelper::getSelectedId();

    $query = Pendaftaran::query();

    if ($tahunAjaranId) {
        $query->where('tahun_ajaran_id', $tahunAjaranId);
    }

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('nama', 'like', '%' . $search . '%')
              ->orWhere('nomor_pendaftaran', 'like', '%' . $search . '%')
              ->orWhere('nisn', 'like', '%' . $search . '%');
        });
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

      if ($request->filled('status_berkas')) {
        $query->where('status_berkas', $request->status_berkas);
    }

    $pendaftaran = $query->latest()->paginate(10)->withQueryString();

    $notifRevisi = Pendaftaran::query()
    ->when($tahunAjaranId, function ($q) use ($tahunAjaranId) {
        $q->where('tahun_ajaran_id', $tahunAjaranId);
    })
    ->where('status_berkas', 'sudah_diperbaiki')
    ->count();

    return view('pendaftaran.index', compact('pendaftaran', 'notifRevisi'));
}

public function create()
{
    $tahunAjaranAktif = \App\Models\TahunAjaran::where('is_active', true)->first();

    return view('pendaftaran.create', compact('tahunAjaranAktif'));
}


public function store(Request $request)
{

$tahunAjaranAktif = TahunAjaran::where('is_active', true)->first();

    if (!$tahunAjaranAktif) {
        return redirect()->back()->with('error', 'Belum ada tahun ajaran aktif.');
    }


    $validated = $request->validate([
        'nama' => 'required|string|max:100',
        'nisn' => 'nullable|string|max:20|unique:pendaftarans,nisn',
        'nik' => 'nullable|string|max:30',
        'tempat_lahir' => 'required|string|max:100',
        'tanggal_lahir' => 'nullable|date',
        'jenis_kelamin' => 'required|in:L,P',

        'hobi' => 'nullable|string|max:100',
        'cita_cita' => 'nullable|string|max:100',
        'anak_ke' => 'nullable|integer|min:1',
        'jumlah_saudara' => 'nullable|integer|min:0',
        'status_tinggal' => 'nullable|string|max:100',
        'no_telp' => 'nullable|string|max:20',

        'alamat' => 'required|string',
        'desa_kelurahan' => 'nullable|string|max:100',
        'kecamatan' => 'nullable|string|max:100',
        'kabupaten_kota' => 'nullable|string|max:100',
        'kode_pos' => 'nullable|string|max:10',

        'asal_sekolah' => 'required|string|max:150',
        'jenis_sekolah' => 'nullable|string|max:20',
        'status_sekolah' => 'nullable|string|max:20',
        'npsn_sekolah' => 'nullable|string|max:20',

        'no_kk' => 'nullable|string|max:30',
        'nama_kepala_keluarga' => 'nullable|string|max:100',
        'status_kepemilikan_rumah' => 'nullable|string|max:100',

        'nama_ayah' => 'nullable|string|max:100',
        'nik_ayah' => 'nullable|string|max:30',
        'status_ayah' => 'nullable|string|max:50',
        'pendidikan_ayah' => 'nullable|string|max:100',
        'pekerjaan_ayah' => 'nullable|string|max:100',
        'penghasilan_ayah' => 'nullable|string|max:100',
        'no_hp_ayah' => 'nullable|string|max:20',

        'nama_ibu' => 'nullable|string|max:100',
        'nik_ibu' => 'nullable|string|max:30',
        'status_ibu' => 'nullable|string|max:50',
        'pendidikan_ibu' => 'nullable|string|max:100',
        'pekerjaan_ibu' => 'nullable|string|max:100',
        'penghasilan_ibu' => 'nullable|string|max:100',
        'no_hp_ibu' => 'nullable|string|max:20',

        'nama_wali' => 'nullable|string|max:100',
        'nik_wali' => 'nullable|string|max:30',
        'status_wali' => 'nullable|string|max:50',
        'pendidikan_wali' => 'nullable|string|max:100',
        'pekerjaan_wali' => 'nullable|string|max:100',
        'penghasilan_wali' => 'nullable|string|max:100',
        'no_hp_wali' => 'nullable|string|max:20',

        'jalur' => 'required|in:reguler,prestasi',
        'nama_orang_tua' => 'required|string|max:100',
        'no_kks' => 'nullable|string|max:50',
        'no_pkh' => 'nullable|string|max:50',
        'no_kip' => 'nullable|string|max:50',

        'nisn_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'kartu_keluarga' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'akta_kelahiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'foto' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        'ijazah' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    $tahun = now()->format('Y');
    $urutan = Pendaftaran::where('tahun_ajaran_id', $tahunAjaranAktif->id)->count() + 1;
    $nomor = 'PPDB-' . $tahun . '-' . str_pad($urutan, 4, '0', STR_PAD_LEFT);

    $berkas = [];
    foreach (['nisn_file', 'kartu_keluarga', 'akta_kelahiran', 'foto', 'ijazah'] as $field) {
        if ($request->hasFile($field)) {
            $berkas[$field] = $request->file($field)->store('berkas/' . $nomor, 'public');
        }
    }

    $data = [
        'nomor_pendaftaran' => $nomor,
        'nama' => $validated['nama'] ?? null,
        'nisn' => $validated['nisn'] ?? null,
        'nik' => $validated['nik'] ?? null,
        'tempat_lahir' => $validated['tempat_lahir'] ?? null,
        'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
        'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,

        'hobi' => $validated['hobi'] ?? null,
        'cita_cita' => $validated['cita_cita'] ?? null,
        'anak_ke' => $validated['anak_ke'] ?? null,
        'jumlah_saudara' => $validated['jumlah_saudara'] ?? null,
        'status_tinggal' => $validated['status_tinggal'] ?? null,
        'no_telp' => $validated['no_telp'] ?? null,

        'alamat' => $validated['alamat'] ?? null,
        'desa_kelurahan' => $validated['desa_kelurahan'] ?? null,
        'kecamatan' => $validated['kecamatan'] ?? null,
        'kabupaten_kota' => $validated['kabupaten_kota'] ?? null,
        'kode_pos' => $validated['kode_pos'] ?? null,

        'asal_sekolah' => $validated['asal_sekolah'] ?? null,
        'jenis_sekolah' => $validated['jenis_sekolah'] ?? null,
        'status_sekolah' => $validated['status_sekolah'] ?? null,
        'npsn_sekolah' => $validated['npsn_sekolah'] ?? null,

        'no_kk' => $validated['no_kk'] ?? null,
        'nama_kepala_keluarga' => $validated['nama_kepala_keluarga'] ?? null,
        'status_kepemilikan_rumah' => $validated['status_kepemilikan_rumah'] ?? null,

        'nama_ayah' => $validated['nama_ayah'] ?? null,
        'nik_ayah' => $validated['nik_ayah'] ?? null,
        'status_ayah' => $validated['status_ayah'] ?? null,
        'pendidikan_ayah' => $validated['pendidikan_ayah'] ?? null,
        'pekerjaan_ayah' => $validated['pekerjaan_ayah'] ?? null,
        'penghasilan_ayah' => $validated['penghasilan_ayah'] ?? null,
        'no_hp_ayah' => $validated['no_hp_ayah'] ?? null,

        'nama_ibu' => $validated['nama_ibu'] ?? null,
        'nik_ibu' => $validated['nik_ibu'] ?? null,
        'status_ibu' => $validated['status_ibu'] ?? null,
        'pendidikan_ibu' => $validated['pendidikan_ibu'] ?? null,
        'pekerjaan_ibu' => $validated['pekerjaan_ibu'] ?? null,
        'penghasilan_ibu' => $validated['penghasilan_ibu'] ?? null,
        'no_hp_ibu' => $validated['no_hp_ibu'] ?? null,

        'nama_wali' => $validated['nama_wali'] ?? null,
        'nik_wali' => $validated['nik_wali'] ?? null,
        'status_wali' => $validated['status_wali'] ?? null,
        'pendidikan_wali' => $validated['pendidikan_wali'] ?? null,
        'pekerjaan_wali' => $validated['pekerjaan_wali'] ?? null,
        'penghasilan_wali' => $validated['penghasilan_wali'] ?? null,
        'no_hp_wali' => $validated['no_hp_wali'] ?? null,

        'jalur' => $validated['jalur'] ?? null,
        'nama_orang_tua' => $validated['nama_orang_tua'] ?? null,
        'no_kks' => $validated['no_kks'] ?? null,
        'no_pkh' => $validated['no_pkh'] ?? null,
        'no_kip' => $validated['no_kip'] ?? null,

        'nisn_file' => $berkas['nisn_file'] ?? null,
        'kartu_keluarga' => $berkas['kartu_keluarga'] ?? null,
        'akta_kelahiran' => $berkas['akta_kelahiran'] ?? null,
        'foto' => $berkas['foto'] ?? null,
        'ijazah' => $berkas['ijazah'] ?? null,

        'status' => 'waiting_proses',
        'berkas_lengkap' => false,
    ];

    Pendaftaran::create($data);

    return redirect()
        ->route('pendaftaran.index')
        ->with('success', 'Data pendaftaran berhasil ditambahkan.');
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
        'nama' => 'required|string|max:100',
        'nisn' => [
            'nullable',
            'string',
            'max:20',
            Rule::unique('pendaftarans', 'nisn')->ignore($pendaftaran->id),
        ],
        'nik' => 'nullable|string|max:30',
        'tempat_lahir' => 'required|string|max:100',
        'tanggal_lahir' => 'nullable|date',
        'jenis_kelamin' => 'required|in:L,P',

        'hobi' => 'nullable|string|max:100',
        'cita_cita' => 'nullable|string|max:100',
        'anak_ke' => 'nullable|integer|min:1',
        'jumlah_saudara' => 'nullable|integer|min:0',
        'status_tinggal' => 'nullable|string|max:100',
        'no_telp' => 'nullable|string|max:20',

        'alamat' => 'required|string',
        'desa_kelurahan' => 'nullable|string|max:100',
        'kecamatan' => 'nullable|string|max:100',
        'kabupaten_kota' => 'nullable|string|max:100',
        'kode_pos' => 'nullable|string|max:10',

        'asal_sekolah' => 'required|string|max:150',
        'jenis_sekolah' => 'nullable|string|max:20',
        'status_sekolah' => 'nullable|string|max:20',
        'npsn_sekolah' => 'nullable|string|max:20',

        'no_kk' => 'nullable|string|max:30',
        'nama_kepala_keluarga' => 'nullable|string|max:100',
        'status_kepemilikan_rumah' => 'nullable|string|max:100',

        'nama_ayah' => 'nullable|string|max:100',
        'nik_ayah' => 'nullable|string|max:30',
        'status_ayah' => 'nullable|string|max:50',
        'pendidikan_ayah' => 'nullable|string|max:100',
        'pekerjaan_ayah' => 'nullable|string|max:100',
        'penghasilan_ayah' => 'nullable|string|max:100',
        'no_hp_ayah' => 'nullable|string|max:20',

        'nama_ibu' => 'nullable|string|max:100',
        'nik_ibu' => 'nullable|string|max:30',
        'status_ibu' => 'nullable|string|max:50',
        'pendidikan_ibu' => 'nullable|string|max:100',
        'pekerjaan_ibu' => 'nullable|string|max:100',
        'penghasilan_ibu' => 'nullable|string|max:100',
        'no_hp_ibu' => 'nullable|string|max:20',

        'nama_wali' => 'nullable|string|max:100',
        'nik_wali' => 'nullable|string|max:30',
        'status_wali' => 'nullable|string|max:50',
        'pendidikan_wali' => 'nullable|string|max:100',
        'pekerjaan_wali' => 'nullable|string|max:100',
        'penghasilan_wali' => 'nullable|string|max:100',
        'no_hp_wali' => 'nullable|string|max:20',

        'jalur' => 'required|in:reguler,prestasi',
        'nama_orang_tua' => 'required|string|max:100',
        'no_kks' => 'nullable|string|max:50',
        'no_pkh' => 'nullable|string|max:50',
        'no_kip' => 'nullable|string|max:50',

        'nisn_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'kartu_keluarga' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'akta_kelahiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'foto' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        'ijazah' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    $berkas = [];
    foreach (['nisn_file', 'kartu_keluarga', 'akta_kelahiran', 'foto', 'ijazah'] as $field) {
        if ($request->hasFile($field)) {
            if (!empty($pendaftaran->$field) && Storage::disk('public')->exists($pendaftaran->$field)) {
                Storage::disk('public')->delete($pendaftaran->$field);
            }

            $berkas[$field] = $request->file($field)->store('berkas/' . $pendaftaran->nomor_pendaftaran, 'public');
        }
    }

    $data = [
        'nama' => $validated['nama'] ?? null,
        'nisn' => $validated['nisn'] ?? null,
        'nik' => $validated['nik'] ?? null,
        'tempat_lahir' => $validated['tempat_lahir'] ?? null,
        'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
        'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,

        'hobi' => $validated['hobi'] ?? null,
        'cita_cita' => $validated['cita_cita'] ?? null,
        'anak_ke' => $validated['anak_ke'] ?? null,
        'jumlah_saudara' => $validated['jumlah_saudara'] ?? null,
        'status_tinggal' => $validated['status_tinggal'] ?? null,
        'no_telp' => $validated['no_telp'] ?? null,

        'alamat' => $validated['alamat'] ?? null,
        'desa_kelurahan' => $validated['desa_kelurahan'] ?? null,
        'kecamatan' => $validated['kecamatan'] ?? null,
        'kabupaten_kota' => $validated['kabupaten_kota'] ?? null,
        'kode_pos' => $validated['kode_pos'] ?? null,

        'asal_sekolah' => $validated['asal_sekolah'] ?? null,
        'jenis_sekolah' => $validated['jenis_sekolah'] ?? null,
        'status_sekolah' => $validated['status_sekolah'] ?? null,
        'npsn_sekolah' => $validated['npsn_sekolah'] ?? null,

        'no_kk' => $validated['no_kk'] ?? null,
        'nama_kepala_keluarga' => $validated['nama_kepala_keluarga'] ?? null,
        'status_kepemilikan_rumah' => $validated['status_kepemilikan_rumah'] ?? null,

        'nama_ayah' => $validated['nama_ayah'] ?? null,
        'nik_ayah' => $validated['nik_ayah'] ?? null,
        'status_ayah' => $validated['status_ayah'] ?? null,
        'pendidikan_ayah' => $validated['pendidikan_ayah'] ?? null,
        'pekerjaan_ayah' => $validated['pekerjaan_ayah'] ?? null,
        'penghasilan_ayah' => $validated['penghasilan_ayah'] ?? null,
        'no_hp_ayah' => $validated['no_hp_ayah'] ?? null,

        'nama_ibu' => $validated['nama_ibu'] ?? null,
        'nik_ibu' => $validated['nik_ibu'] ?? null,
        'status_ibu' => $validated['status_ibu'] ?? null,
        'pendidikan_ibu' => $validated['pendidikan_ibu'] ?? null,
        'pekerjaan_ibu' => $validated['pekerjaan_ibu'] ?? null,
        'penghasilan_ibu' => $validated['penghasilan_ibu'] ?? null,
        'no_hp_ibu' => $validated['no_hp_ibu'] ?? null,

        'nama_wali' => $validated['nama_wali'] ?? null,
        'nik_wali' => $validated['nik_wali'] ?? null,
        'status_wali' => $validated['status_wali'] ?? null,
        'pendidikan_wali' => $validated['pendidikan_wali'] ?? null,
        'pekerjaan_wali' => $validated['pekerjaan_wali'] ?? null,
        'penghasilan_wali' => $validated['penghasilan_wali'] ?? null,
        'no_hp_wali' => $validated['no_hp_wali'] ?? null,

        'jalur' => $validated['jalur'] ?? null,
        'nama_orang_tua' => $validated['nama_orang_tua'] ?? null,
        'no_kks' => $validated['no_kks'] ?? null,
        'no_pkh' => $validated['no_pkh'] ?? null,
        'no_kip' => $validated['no_kip'] ?? null,

        'nisn_file' => $berkas['nisn_file'] ?? $pendaftaran->nisn_file,
        'kartu_keluarga' => $berkas['kartu_keluarga'] ?? $pendaftaran->kartu_keluarga,
        'akta_kelahiran' => $berkas['akta_kelahiran'] ?? $pendaftaran->akta_kelahiran,
        'foto' => $berkas['foto'] ?? $pendaftaran->foto,
        'ijazah' => $berkas['ijazah'] ?? $pendaftaran->ijazah,
    ];

    foreach (['nisn_file', 'kartu_keluarga', 'akta_kelahiran', 'foto', 'ijazah'] as $field) {
        if ($request->hasFile($field)) {
            if ($pendaftaran->$field && Storage::disk('public')->exists($pendaftaran->$field)) {
                Storage::disk('public')->delete($pendaftaran->$field);
            }

            $data[$field] = $request->file($field)->store('berkas/' . $pendaftaran->nomor_pendaftaran, 'public');
        }
    }

    if ($pendaftaran->status_berkas === 'perlu_perbaikan') {
    $data['status_berkas'] = 'sudah_diperbaiki';
    $data['revisi_at'] = now();
}

    $pendaftaran->update($data);

    return redirect()
        ->route('pendaftaran.index')
        ->with('success', 'Data pendaftaran berhasil diperbarui.');
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
    $tahunAjaranAktif = TahunAjaran::where('is_active', true)->first();

    return view('pendaftaran.publik', compact('tahunAjaranAktif'));
}

public function storePublik(Request $request)
{
    $tahunAjaranAktif = TahunAjaran::where('is_active', true)->first();

    if (!$tahunAjaranAktif) {
        return redirect()->back()->with('error', 'Belum ada tahun ajaran aktif.');
    }

    $validated = $request->validate([
        'nama' => 'required|string|max:100',
        'nisn' => 'nullable|string|max:20|unique:pendaftarans,nisn',
        'nik' => 'nullable|string|max:30',
        'tempat_lahir' => 'required|string|max:100',
        'tanggal_lahir' => 'nullable|date',
        'jenis_kelamin' => 'required|in:L,P',
        'alamat' => 'required|string',
        'asal_sekolah' => 'required|string|max:150',
        'jalur' => 'required|in:reguler,prestasi',
        'nama_orang_tua' => 'required|string|max:100',

        'hobi' => 'nullable|string|max:100',
        'cita_cita' => 'nullable|string|max:100',
        'anak_ke' => 'nullable|integer|min:1',
        'jumlah_saudara' => 'nullable|integer|min:0',
        'status_tinggal' => 'nullable|string|max:100',
        'no_telp' => 'nullable|string|max:20',
        'desa_kelurahan' => 'nullable|string|max:100',
        'kecamatan' => 'nullable|string|max:100',
        'kabupaten_kota' => 'nullable|string|max:100',
        'kode_pos' => 'nullable|string|max:10',

        'jenis_sekolah' => 'nullable|string|max:20',
        'status_sekolah' => 'nullable|string|max:20',
        'npsn_sekolah' => 'nullable|string|max:20',

        'no_kk' => 'nullable|string|max:30',
        'nama_kepala_keluarga' => 'nullable|string|max:100',
        'status_kepemilikan_rumah' => 'nullable|string|max:100',

        'nama_ayah' => 'nullable|string|max:100',
        'nik_ayah' => 'nullable|string|max:30',
        'status_ayah' => 'nullable|string|max:50',
        'pendidikan_ayah' => 'nullable|string|max:100',
        'pekerjaan_ayah' => 'nullable|string|max:100',
        'penghasilan_ayah' => 'nullable|string|max:100',
        'no_hp_ayah' => 'nullable|string|max:20',

        'nama_ibu' => 'nullable|string|max:100',
        'nik_ibu' => 'nullable|string|max:30',
        'status_ibu' => 'nullable|string|max:50',
        'pendidikan_ibu' => 'nullable|string|max:100',
        'pekerjaan_ibu' => 'nullable|string|max:100',
        'penghasilan_ibu' => 'nullable|string|max:100',
        'no_hp_ibu' => 'nullable|string|max:20',

        'nama_wali' => 'nullable|string|max:100',
        'nik_wali' => 'nullable|string|max:30',
        'status_wali' => 'nullable|string|max:50',
        'pendidikan_wali' => 'nullable|string|max:100',
        'pekerjaan_wali' => 'nullable|string|max:100',
        'penghasilan_wali' => 'nullable|string|max:100',
        'no_hp_wali' => 'nullable|string|max:20',

        'no_kks' => 'nullable|string|max:50',
        'no_pkh' => 'nullable|string|max:50',
        'no_kip' => 'nullable|string|max:50',

        'nisn_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'kartu_keluarga' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'akta_kelahiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'foto' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        'ijazah' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    $urutan = \App\Models\Pendaftaran::where('tahun_ajaran_id', $tahunAjaranAktif->id)->count() + 1;
    $nomor = 'PPDB-' . str_replace('/', '', $tahunAjaranAktif->nama_tahun_ajaran) . '-' . str_pad($urutan, 4, '0', STR_PAD_LEFT);

    $berkas = [];
    foreach (['nisn_file', 'kartu_keluarga', 'akta_kelahiran', 'foto', 'ijazah'] as $field) {
        if ($request->hasFile($field)) {
            $berkas[$field] = $request->file($field)->store('berkas/' . $nomor, 'public');
        }
    }

    $data = $validated;
    $data['tahun_ajaran_id'] = $tahunAjaranAktif->id;
    $data['nomor_pendaftaran'] = $nomor;
    $data['status'] = 'waiting_proses';
    $data['berkas_lengkap'] = false;
    $data['nisn_file'] = $berkas['nisn_file'] ?? null;
    $data['kartu_keluarga'] = $berkas['kartu_keluarga'] ?? null;
    $data['akta_kelahiran'] = $berkas['akta_kelahiran'] ?? null;
    $data['foto'] = $berkas['foto'] ?? null;
    $data['ijazah'] = $berkas['ijazah'] ?? null;

    \App\Models\Pendaftaran::create($data);

    return redirect()->route('pengumuman')
        ->with('success', 'Pendaftaran berhasil dikirim.');
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
        'status' => 'required|string',
        'catatan_admin' => 'nullable|string',
    ]);

    $p = Pendaftaran::findOrFail($id);

    $p->status = $request->status;
    $p->catatan_admin = $request->catatan_admin;

    if ($request->status === 'pending' && !empty($request->catatan_admin)) {
        $p->status_berkas = 'perlu_perbaikan';
    }

    $p->save();

    return redirect()->back()->with('success', 'Status dan catatan berhasil disimpan.');
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

public function updatePublik(Request $request, $id)
{
    $pendaftaran = Pendaftaran::findOrFail($id);

    $validated = $request->validate([
        'nama' => 'required|string|max:100',
        'nisn' => [
            'nullable',
            'string',
            'max:20',
            Rule::unique('pendaftarans', 'nisn')->ignore($pendaftaran->id),
        ],
        'nik' => 'nullable|string|max:30',
        'tempat_lahir' => 'required|string|max:100',
        'tanggal_lahir' => 'nullable|date',
        'jenis_kelamin' => 'required|in:L,P',

        'hobi' => 'nullable|string|max:100',
        'cita_cita' => 'nullable|string|max:100',
        'anak_ke' => 'nullable|integer|min:1',
        'jumlah_saudara' => 'nullable|integer|min:0',
        'status_tinggal' => 'nullable|string|max:100',
        'no_telp' => 'nullable|string|max:20',

        'alamat' => 'required|string',
        'desa_kelurahan' => 'nullable|string|max:100',
        'kecamatan' => 'nullable|string|max:100',
        'kabupaten_kota' => 'nullable|string|max:100',
        'kode_pos' => 'nullable|string|max:10',

        'asal_sekolah' => 'required|string|max:150',
        'jenis_sekolah' => 'nullable|string|max:20',
        'status_sekolah' => 'nullable|string|max:20',
        'npsn_sekolah' => 'nullable|string|max:20',

        'no_kk' => 'nullable|string|max:30',
        'nama_kepala_keluarga' => 'nullable|string|max:100',
        'status_kepemilikan_rumah' => 'nullable|string|max:100',

        'nama_ayah' => 'nullable|string|max:100',
        'nik_ayah' => 'nullable|string|max:30',
        'status_ayah' => 'nullable|string|max:50',
        'pendidikan_ayah' => 'nullable|string|max:100',
        'pekerjaan_ayah' => 'nullable|string|max:100',
        'penghasilan_ayah' => 'nullable|string|max:100',
        'no_hp_ayah' => 'nullable|string|max:20',

        'nama_ibu' => 'nullable|string|max:100',
        'nik_ibu' => 'nullable|string|max:30',
        'status_ibu' => 'nullable|string|max:50',
        'pendidikan_ibu' => 'nullable|string|max:100',
        'pekerjaan_ibu' => 'nullable|string|max:100',
        'penghasilan_ibu' => 'nullable|string|max:100',
        'no_hp_ibu' => 'nullable|string|max:20',

        'nama_wali' => 'nullable|string|max:100',
        'nik_wali' => 'nullable|string|max:30',
        'status_wali' => 'nullable|string|max:50',
        'pendidikan_wali' => 'nullable|string|max:100',
        'pekerjaan_wali' => 'nullable|string|max:100',
        'penghasilan_wali' => 'nullable|string|max:100',
        'no_hp_wali' => 'nullable|string|max:20',

        'jalur' => 'required|in:reguler,prestasi',
        'nama_orang_tua' => 'required|string|max:100',
        'no_kks' => 'nullable|string|max:50',
        'no_pkh' => 'nullable|string|max:50',
        'no_kip' => 'nullable|string|max:50',

        'nisn_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'kartu_keluarga' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'akta_kelahiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'foto' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        'ijazah' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    $data = [
        'nama' => $validated['nama'] ?? null,
        'nisn' => $validated['nisn'] ?? null,
        'nik' => $validated['nik'] ?? null,
        'tempat_lahir' => $validated['tempat_lahir'] ?? null,
        'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
        'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
        'hobi' => $validated['hobi'] ?? null,
        'cita_cita' => $validated['cita_cita'] ?? null,
        'anak_ke' => $validated['anak_ke'] ?? null,
        'jumlah_saudara' => $validated['jumlah_saudara'] ?? null,
        'status_tinggal' => $validated['status_tinggal'] ?? null,
        'no_telp' => $validated['no_telp'] ?? null,
        'alamat' => $validated['alamat'] ?? null,
        'desa_kelurahan' => $validated['desa_kelurahan'] ?? null,
        'kecamatan' => $validated['kecamatan'] ?? null,
        'kabupaten_kota' => $validated['kabupaten_kota'] ?? null,
        'kode_pos' => $validated['kode_pos'] ?? null,
        'asal_sekolah' => $validated['asal_sekolah'] ?? null,
        'jenis_sekolah' => $validated['jenis_sekolah'] ?? null,
        'status_sekolah' => $validated['status_sekolah'] ?? null,
        'npsn_sekolah' => $validated['npsn_sekolah'] ?? null,
        'no_kk' => $validated['no_kk'] ?? null,
        'nama_kepala_keluarga' => $validated['nama_kepala_keluarga'] ?? null,
        'status_kepemilikan_rumah' => $validated['status_kepemilikan_rumah'] ?? null,
        'nama_ayah' => $validated['nama_ayah'] ?? null,
        'nik_ayah' => $validated['nik_ayah'] ?? null,
        'status_ayah' => $validated['status_ayah'] ?? null,
        'pendidikan_ayah' => $validated['pendidikan_ayah'] ?? null,
        'pekerjaan_ayah' => $validated['pekerjaan_ayah'] ?? null,
        'penghasilan_ayah' => $validated['penghasilan_ayah'] ?? null,
        'no_hp_ayah' => $validated['no_hp_ayah'] ?? null,
        'nama_ibu' => $validated['nama_ibu'] ?? null,
        'nik_ibu' => $validated['nik_ibu'] ?? null,
        'status_ibu' => $validated['status_ibu'] ?? null,
        'pendidikan_ibu' => $validated['pendidikan_ibu'] ?? null,
        'pekerjaan_ibu' => $validated['pekerjaan_ibu'] ?? null,
        'penghasilan_ibu' => $validated['penghasilan_ibu'] ?? null,
        'no_hp_ibu' => $validated['no_hp_ibu'] ?? null,
        'nama_wali' => $validated['nama_wali'] ?? null,
        'nik_wali' => $validated['nik_wali'] ?? null,
        'status_wali' => $validated['status_wali'] ?? null,
        'pendidikan_wali' => $validated['pendidikan_wali'] ?? null,
        'pekerjaan_wali' => $validated['pekerjaan_wali'] ?? null,
        'penghasilan_wali' => $validated['penghasilan_wali'] ?? null,
        'no_hp_wali' => $validated['no_hp_wali'] ?? null,
        'jalur' => $validated['jalur'] ?? null,
        'nama_orang_tua' => $validated['nama_orang_tua'] ?? null,
        'no_kks' => $validated['no_kks'] ?? null,
        'no_pkh' => $validated['no_pkh'] ?? null,
        'no_kip' => $validated['no_kip'] ?? null,

        'nisn_file' => $pendaftaran->nisn_file,
        'kartu_keluarga' => $pendaftaran->kartu_keluarga,
        'akta_kelahiran' => $pendaftaran->akta_kelahiran,
        'foto' => $pendaftaran->foto,
        'ijazah' => $pendaftaran->ijazah,

        
    ];

    

    foreach (['nisn_file', 'kartu_keluarga', 'akta_kelahiran', 'foto', 'ijazah'] as $field) {
        if ($request->hasFile($field)) {
            if ($pendaftaran->$field && Storage::disk('public')->exists($pendaftaran->$field)) {
                Storage::disk('public')->delete($pendaftaran->$field);
            }

            $data[$field] = $request->file($field)->store('berkas/' . $pendaftaran->nomor_pendaftaran, 'public');
        }
    }

 $pendaftaran->update($data);

$pendaftaran->status_berkas = 'sudah_diperbaiki';
$pendaftaran->revisi_at = now();
$pendaftaran->save();

    return redirect()
        ->route('pengumuman')
        ->with('success', 'Data pendaftaran berhasil diperbarui.');
}

public function template()
{
    $headers = [
        'nama',
        'nisn',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'hobi',
        'cita_cita',
        'anak_ke',
        'jumlah_saudara',
        'status_tinggal',
        'no_telp',
        'alamat',
        'desa_kelurahan',
        'kecamatan',
        'kabupaten_kota',
        'kode_pos',
        'asal_sekolah',
        'jenis_sekolah',
        'status_sekolah',
        'npsn_sekolah',
        'no_kk',
        'nama_kepala_keluarga',
        'status_kepemilikan_rumah',
        'nama_ayah',
        'nik_ayah',
        'status_ayah',
        'pendidikan_ayah',
        'pekerjaan_ayah',
        'penghasilan_ayah',
        'no_hp_ayah',
        'nama_ibu',
        'nik_ibu',
        'status_ibu',
        'pendidikan_ibu',
        'pekerjaan_ibu',
        'penghasilan_ibu',
        'no_hp_ibu',
        'nama_wali',
        'nik_wali',
        'status_wali',
        'pendidikan_wali',
        'pekerjaan_wali',
        'penghasilan_wali',
        'no_hp_wali',
        'jalur',
        'nama_orang_tua',
        'no_kks',
        'no_pkh',
        'no_kip',
    ];

    $filename = 'template_pendaftaran.csv';
    $path = storage_path('app/' . $filename);

    $handle = fopen($path, 'w');

    // Tambahan biar Excel lebih aman baca UTF-8
    fwrite($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

    // Pakai delimiter titik koma
    fputcsv($handle, $headers, ';');

    fclose($handle);

    return response()->download($path, $filename, [
        'Content-Type' => 'text/csv; charset=UTF-8',
    ])->deleteFileAfterSend(true);

    return Excel::download(new TemplatePendaftaranExport, 'template_pendaftaran.xlsx');
}

public function mintaPerbaikan(Request $request, $id)
{
    $request->validate([
        'catatan_admin' => 'required|string',
    ]);

    $data = \App\Models\Pendaftaran::findOrFail($id);

    $data->status_berkas = 'perlu_perbaikan';
    $data->catatan_admin = $request->catatan_admin;
    $data->save();

    return redirect()->back()->with('success', 'Catatan perbaikan berhasil dikirim ke siswa.');
}

}

