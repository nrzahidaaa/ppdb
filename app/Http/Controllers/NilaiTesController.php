<?php

namespace App\Http\Controllers;

use App\Models\NilaiTes;
use App\Models\Pendaftaran;
use App\Imports\NilaiTesImport;
use Illuminate\Http\Request;

class NilaiTesController extends Controller
{
    public function index()
    {
        $nilaiTes    = NilaiTes::with('siswa')->paginate(15);
        $pendaftaran = Pendaftaran::orderBy('nama')->get();
        return view('nilai_tes.index', compact('nilaiTes', 'pendaftaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_siswa'        => 'required|exists:pendaftarans,id',
            'bhs_indonesia'   => 'required|integer|min:0|max:100',
            'matematika'      => 'required|integer|min:0|max:100',
            'ipa'             => 'required|integer|min:0|max:100',
            'ips'             => 'required|integer|min:0|max:100',
            'agama'           => 'required|integer|min:0|max:100',
            'doa_iftitah'     => 'required|integer|min:0|max:100',
            'tahiyat_awal'    => 'required|integer|min:0|max:100',
            'qunut'           => 'required|integer|min:0|max:100',
            'membaca_al_quran'=> 'required|integer|min:0|max:100',
            'fatihah_4'       => 'required|integer|min:0|max:100',
            'surah_pendek'    => 'required|integer|min:0|max:100',
            'doa'             => 'required|integer|min:0|max:100',
            'menulis'         => 'required|integer|min:0|max:100',
            'total_nilai'=> 'required|integer|min:0|max:100',
            'tanggal_input'   => 'nullable|date',
        ]);

        $totalNilai =
    (int) $request->bhs_indonesia +
    (int) $request->matematika +
    (int) $request->ipa +
    (int) $request->ips +
    (int) $request->agama +
    (int) $request->doa_iftitah +
    (int) $request->tahiyat_awal +
    (int) $request->qunut +
    (int) $request->membaca_al_quran +
    (int) $request->fatihah_4 +
    (int) $request->surah_pendek +
    (int) $request->doa +
    (int) $request->menulis;

        NilaiTes::create([
            'id_siswa'         => $request->id_siswa,
            'bhs_indonesia'    => $request->bhs_indonesia,
            'matematika'       => $request->matematika,
            'ipa'              => $request->ipa,
            'ips'              => $request->ips,
            'agama'            => $request->agama,
            'doa_iftitah'      => $request->doa_iftitah,
            'tahiyat_awal'     => $request->tahiyat_awal,
            'qunut'            => $request->qunut,
            'membaca_al_quran' => $request->membaca_al_quran,
            'fatihah_4'        => $request->fatihah_4,
            'surah_pendek'     => $request->surah_pendek,
            'doa'              => $request->doa,
            'menulis'          => $request->menulis,
            'total_nilai'          => $request->total_nilai,
            'tanggal_input'    => $request->tanggal_input ?? now(),
        ]);

        return redirect()->route('nilai-tes.index')
                         ->with('success', 'Data nilai berhasil ditambahkan!');
    }

    public function importExcel(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv'
    ]);

    $file = $request->file('file')->getPathname();

    $import = new NilaiTesImport();
    $hasil = $import->import($file);

    if (($hasil['berhasil'] ?? 0) === 0) {
        return redirect()->route('nilai-tes.index')
            ->with('error', 'Import selesai, tetapi tidak ada data yang masuk. Cek kecocokan NISN/nama pada file Excel.');
    }

    return redirect()->route('nilai-tes.index')
        ->with('success', 'Import berhasil: ' . $hasil['berhasil'] . ' data masuk, ' . $hasil['gagal'] . ' data gagal.');
}
    public function destroy($id)
    {
        NilaiTes::findOrFail($id)->delete();
        return redirect()->route('nilai-tes.index')
                         ->with('success', 'Data nilai berhasil dihapus.');
    }
}
