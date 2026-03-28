<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Kelas;
use App\Models\NilaiTes;
use App\Services\NaiveBayes;
use Illuminate\Http\Request;

class KlasifikasiController extends Controller
{
    public function index()
    {
        $pending = Pendaftaran::where('status', 'pending')->count();
        $lulus   = Pendaftaran::where('status', 'lulus')->count();
        $ditolak = Pendaftaran::where('status', 'ditolak')->count();
        $kelas   = Kelas::withCount('siswa')->get();
        $unggul  = Pendaftaran::where('predikat', 'Unggul')->count();
        $baik    = Pendaftaran::where('predikat', 'Baik')->count();
        $cukup   = Pendaftaran::where('predikat', 'Cukup')->count();

        $hasilKlasifikasi = Pendaftaran::whereIn('status', ['lulus','ditolak'])
        ->orderByDesc('updated_at')
        ->get();

        // Data training = semua siswa yang sudah punya nilai tes
        $trainingData = NilaiTes::all()->map(fn($n) => [
            'ipa'              => $n->ipa,
            'ips'              => $n->ips,
            'bhs_indonesia'    => $n->bhs_indonesia,
            'matematika'       => $n->matematika,
            'doa_iftitah'      => $n->doa_iftitah,
            'tahiyat_awal'     => $n->tahiyat_awal,
            'qunut'            => $n->qunut,
            'membaca_al_quran' => $n->membaca_al_quran,
            'fatihah_4'        => $n->fatihah_4,
            'doa'              => $n->doa,
            'menulis'          => $n->menulis,
        ])->toArray();

        $nb = new NaiveBayes();
        $modelInfo = [];

        if (count($trainingData) > 0) {
            $nb->train($trainingData);
            $modelInfo = $nb->getModel();
        }

        return view('klasifikasi.index', compact(
            'pending', 'lulus', 'ditolak',
            'kelas', 'unggul', 'baik', 'cukup', 'modelInfo', 'hasilKlasifikasi'
        ));
    }

    public function proses(Request $request)
    {
        // Ambil semua data nilai tes sebagai training data
        $allNilai = NilaiTes::all()->map(fn($n) => [
            'ipa'              => $n->ipa,
            'ips'              => $n->ips,
            'bhs_indonesia'    => $n->bhs_indonesia,
            'matematika'       => $n->matematika,
            'doa_iftitah'      => $n->doa_iftitah,
            'tahiyat_awal'     => $n->tahiyat_awal,
            'qunut'            => $n->qunut,
            'membaca_al_quran' => $n->membaca_al_quran,
            'fatihah_4'        => $n->fatihah_4,
            'doa'              => $n->doa,
            'menulis'          => $n->menulis,
        ])->toArray();

        if (count($allNilai) < 3) {
            return redirect()->route('klasifikasi.index')
                ->with('error', 'Data nilai tes minimal 3 siswa untuk training Naive Bayes!');
        }

        $nb = new NaiveBayes();
        $nb->train($allNilai);

        $pendaftar = Pendaftaran::where('status', 'pending')->with('nilaiTes')->get();
        $diproses  = 0;
        $hasil     = [];

        foreach ($pendaftar as $p) {
            if (!$p->nilaiTes) continue;

            $n = $p->nilaiTes;
            $inputData = [
                'ipa'              => $n->ipa,
                'ips'              => $n->ips,
                'bhs_indonesia'    => $n->bhs_indonesia,
                'matematika'       => $n->matematika,
                'doa_iftitah'      => $n->doa_iftitah,
                'tahiyat_awal'     => $n->tahiyat_awal,
                'qunut'            => $n->qunut,
                'membaca_al_quran' => $n->membaca_al_quran,
                'fatihah_4'        => $n->fatihah_4,
                'doa'              => $n->doa,
                'menulis'          => $n->menulis,
            ];

$result = $nb->predict($inputData);

if (!$result || !isset($result['predicted'])) {
    continue;
}

$predikat = ucfirst(strtolower($result['predicted']));
$status   = in_array($predikat, ['Unggul', 'Baik']) ? 'lulus' : 'ditolak';
$total    = array_sum(array_filter($inputData));

$p->update([
    'status' => $status,
    'predikat' => $predikat,
    'total_nilai' => $total
]);

$hasil[] = [
    'nama' => $p->nama,
    'total' => $total,
    'predikat' => $predikat,
    'status' => $status,
    'probabilitas' => $result['probabilities'] ?? [],
];

$diproses++;
            
        }

        return redirect()->route('klasifikasi.index')
            ->with('success', $diproses.' siswa berhasil diklasifikasi dengan Naive Bayes!')
            ->with('hasil', $hasil);
    }

public function pembagianKelas()
{
    $kelas = Kelas::with(['siswa'])->get(); // relasi siswa

    return view('klasifikasi.pembagian', compact('kelas'));
}

public function prosesKelas()
{
    
    // 🔥 hanya kelas 7A 7B 7C
    $kelas = \App\Models\Kelas::whereIn('nama_kelas', ['7A','7B','7C'])->get();

    if ($kelas->isEmpty()) {
        return back()->with('error', 'Kelas 7A, 7B, 7C belum dibuat!');
    }

$unggul = Pendaftaran::where('status', 'lulus')
    ->where('predikat', 'Unggul')
    ->whereNull('id_kelas')
    ->get()
    ->toArray();

$baik = Pendaftaran::where('status', 'lulus')
    ->where('predikat', 'Baik')
    ->whereNull('id_kelas')
    ->get()
    ->toArray();

$cukup = Pendaftaran::where('status', 'lulus')
    ->where('predikat', 'Cukup')
    ->whereNull('id_kelas')
    ->get()
    ->toArray();

    // stratified
    $siswaStratified = [];
    $max = max(count($unggul), count($baik), count($cukup));

    for ($i = 0; $i < $max; $i++) {
        if (isset($unggul[$i])) $siswaStratified[] = $unggul[$i];
        if (isset($baik[$i]))   $siswaStratified[] = $baik[$i];
        if (isset($cukup[$i]))  $siswaStratified[] = $cukup[$i];
    }

    // distribusi ke 7A 7B 7C
    $kelasList = $kelas->values();
    $index = 0;
    $diproses = 0;

    foreach ($siswaStratified as $s) {

        $k = $kelasList[$index % $kelasList->count()];

        \App\Models\Pendaftaran::where('id', $s['id'])
            ->update(['id_kelas' => $k->id]);

        $index++;
        $diproses++;
    }

    return back()->with('success', "$diproses siswa berhasil dibagi ke kelas 7A, 7B, 7C!");
}
}