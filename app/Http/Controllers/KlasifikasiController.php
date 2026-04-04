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

        $hasilSession = session('hasil', []);
        $hasilMap = collect($hasilSession)->keyBy('nama');

       $hasilKlasifikasi = Pendaftaran::with('nilaiTes')
    ->whereIn('status', ['pending', 'lulus', 'ditolak'])
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
            'kelas', 'unggul', 'baik', 'cukup', 'modelInfo', 
            'hasilKlasifikasi', 'hasilMap'
        ));
    }

    public function proses(Request $request)
    {
        
        $allNilai = NilaiTes::all()->map(fn($n) => [
            'bhs_indonesia'    => (int) $n->bhs_indonesia,
            'matematika'       => (int) $n->matematika,
            'ipa'              => (int) $n->ipa,
            'ips'              => (int) $n->ips,
            'agama'            => (int) $n->agama,
            'doa_iftitah'      => (int) $n->doa_iftitah,
            'tahiyat_awal'     => (int) $n->tahiyat_awal,
            'qunut'            => (int) $n->qunut,
            'membaca_al_quran' => (int) $n->membaca_al_quran,
            'fatihah_4'        => (int) $n->fatihah_4,
            'surah_pendek'     => (int) $n->surah_pendek,
            'doa'              => (int) $n->doa,
            'menulis'          => (int) $n->menulis,
            
        ])->toArray();

        if (count($allNilai) < 3) {
            return redirect()->route('klasifikasi.index')
                ->with('error', 'Data nilai tes minimal 3 siswa untuk training Naive Bayes!');
        }

        $nb = new NaiveBayes();
        $nb->train($allNilai);

        $pendaftar = Pendaftaran::where('status', 'pending')
            ->whereHas('nilaiTes', function ($q) {
                $q->where('status_hasil', 'lulus');
            })
            ->with(['nilaiTes' => function ($q) {
                $q->where('status_hasil', 'lulus');
            }])
            ->get();
        $diproses  = 0;
        $hasil     = [];

foreach ($pendaftar as $p) {
    if (!$p->nilaiTes) continue;
    if ($p->nilaiTes->status_hasil !== 'lulus') continue;

    $n = $p->nilaiTes;

    $inputData = [
        'bhs_indonesia'    => (int) $n->bhs_indonesia,
        'matematika'       => (int) $n->matematika,
        'ipa'              => (int) $n->ipa,
        'ips'              => (int) $n->ips,
        'agama'            => (int) $n->agama,
        'doa_iftitah'      => (int) $n->doa_iftitah,
        'tahiyat_awal'     => (int) $n->tahiyat_awal,
        'qunut'            => (int) $n->qunut,
        'membaca_al_quran' => (int) $n->membaca_al_quran,
        'fatihah_4'        => (int) $n->fatihah_4,
        'surah_pendek'     => (int) $n->surah_pendek,
        'doa'              => (int) $n->doa,
        'menulis'          => (int) $n->menulis,
    ];

  $result = $nb->predict($inputData);
$probabilitas = $result['probabilities'] ?? [];

    $total = array_sum($inputData);
    $status = 'lulus';

if ($total >= 900) {
    $predikat = 'Unggul';
} elseif ($total >= 710) {
    $predikat = 'Baik';
} else {
    $predikat = 'Cukup';
}
    $p->update([
        'status' => $status,
        'predikat' => $predikat,
    ]);

    $n->update([
        'total_nilai' => $total,
    ]);

    $hasil[] = [
    'nama' => $p->nama,
    'nisn' => $p->nisn,
    'total_nilai' => $total,
    'predikat' => $predikat,
    'status' => $status,
    'probabilitas' => $probabilitas,
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
    $kelas = \App\Models\Kelas::whereIn('nama_kelas', ['7A','7B','7C'])->get();

    if ($kelas->isEmpty()) {
        return back()->with('error', 'Kelas 7A, 7B, 7C belum dibuat!');
    }

    $unggul = \App\Models\Pendaftaran::where('status', 'lulus')
        ->where('predikat', 'Unggul')
        ->whereNull('id_kelas')
        ->get();

    $baik = \App\Models\Pendaftaran::where('status', 'lulus')
        ->where('predikat', 'Baik')
        ->whereNull('id_kelas')
        ->get();

    $cukup = \App\Models\Pendaftaran::where('status', 'lulus')
        ->where('predikat', 'Cukup')
        ->whereNull('id_kelas')
        ->get();

    // Stratified: bergantian Unggul-Baik-Cukup per kelas
    $kelasList = $kelas->values();
    $jumlahKelas = $kelasList->count();
    $diproses = 0;

    // Distribusi per predikat ke semua kelas secara round-robin
    foreach ([$unggul, $baik, $cukup] as $kelompok) {
        $idx = 0;
        foreach ($kelompok as $siswa) {
            $k = $kelasList[$idx % $jumlahKelas];
            
            // Cek kapasitas
            $terisi = \App\Models\Pendaftaran::where('id_kelas', $k->id)->count();
            if ($terisi >= $k->kuota) {
                // Cari kelas lain yang masih ada sisa
                $found = false;
                for ($i = 1; $i < $jumlahKelas; $i++) {
                    $kAlt = $kelasList[($idx + $i) % $jumlahKelas];
                    $terisiAlt = \App\Models\Pendaftaran::where('id_kelas', $kAlt->id)->count();
                    if ($terisiAlt < $kAlt->kuota) {
                        $k = $kAlt;
                        $found = true;
                        break;
                    }
                }
                if (!$found) continue;
            }

            $siswa->update(['id_kelas' => $k->id]);
            $idx++;
            $diproses++;
        }
    }

    return back()->with('success', "$diproses siswa berhasil dibagi ke kelas 7A, 7B, 7C secara stratified!");
}
}