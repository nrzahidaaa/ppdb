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
        // Statistik status
        $pending    = Pendaftaran::where('status', 'pending')->count();
        $lulus      = Pendaftaran::where('status', 'lulus')->count();
        $tidakLulus = Pendaftaran::whereIn('status', ['ditolak', 'tidak_lulus'])->count();

        $kelas = Kelas::withCount('siswa')->get();

        // Statistik predikat hanya untuk siswa lulus
        $unggul = Pendaftaran::where('status', 'lulus')
            ->where('predikat', 'Unggul')
            ->count();

        $baik = Pendaftaran::where('status', 'lulus')
            ->where('predikat', 'Baik')
            ->count();

        $cukup = Pendaftaran::where('status', 'lulus')
            ->where('predikat', 'Cukup')
            ->count();

        $hasilSession = session('hasil', []);
        $hasilMap = collect($hasilSession)->keyBy('nama');

        // HASIL KLASIFIKASI hanya tampilkan siswa LULUS
        $hasilKlasifikasi = Pendaftaran::with('nilaiTes')
            ->where('status', 'lulus')
            ->whereNotNull('predikat')
            ->orderByDesc('updated_at')
            ->get();

        // Data training
        $trainingData = NilaiTes::all()->map(fn($n) => [
            'ipa'              => (int) $n->ipa,
            'ips'              => (int) $n->ips,
            'bhs_indonesia'    => (int) $n->bhs_indonesia,
            'matematika'       => (int) $n->matematika,
            'doa_iftitah'      => (int) $n->doa_iftitah,
            'tahiyat_awal'     => (int) $n->tahiyat_awal,
            'qunut'            => (int) $n->qunut,
            'membaca_al_quran' => (int) $n->membaca_al_quran,
            'fatihah_4'        => (int) $n->fatihah_4,
            'doa'              => (int) $n->doa,
            'menulis'          => (int) $n->menulis,
        ])->toArray();

        $nb = new NaiveBayes();
        $modelInfo = [];

        if (count($trainingData) > 0) {
            $nb->train($trainingData);
            $modelInfo = $nb->getModel();
        }

        return view('klasifikasi.index', compact(
            'pending',
            'lulus',
            'tidakLulus',
            'kelas',
            'unggul',
            'baik',
            'cukup',
            'modelInfo',
            'hasilKlasifikasi',
            'hasilMap'
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

        // Hanya siswa lulus yang diproses klasifikasi
        $pendaftar = Pendaftaran::where('status', 'lulus')
            ->whereHas('nilaiTes')
            ->with('nilaiTes')
            ->get();

        $diproses = 0;
        $hasil = [];

        foreach ($pendaftar as $p) {
            if (!$p->nilaiTes) {
                continue;
            }

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

            if ($total >= 920) {
                $predikat = 'Unggul';
            } elseif ($total >= 730) {
                $predikat = 'Baik';
            } else {
                $predikat = 'Cukup';
            }

            $p->update([
                'status'   => 'lulus',
                'predikat' => $predikat,
            ]);

            $n->update([
                'total_nilai' => $total,
            ]);

            $hasil[] = [
                'nama'         => $p->nama,
                'nisn'         => $p->nisn,
                'total_nilai'  => $total,
                'predikat'     => $predikat,
                'status'       => 'lulus',
                'probabilitas' => $probabilitas,
            ];

            $diproses++;
        }

        return redirect()->route('klasifikasi.index')
            ->with('success', $diproses . ' siswa lulus berhasil diklasifikasi dengan Naive Bayes!')
            ->with('hasil', $hasil);
    }

        public function pembagianKelas()
        {
            $kelas = Kelas::with([
                'siswa' => function ($q) {
                    $q->where('status', 'lulus')
                        ->orderByRaw("
                            CASE predikat
                                WHEN 'Unggul' THEN 1
                                WHEN 'Baik' THEN 2
                                WHEN 'Cukup' THEN 3
                                ELSE 4
                            END
                        ")
                        ->orderBy('nama');
                }
            ])->withCount([
                'siswa as total_siswa' => function ($q) {
                    $q->where('status', 'lulus');
                },
                'siswa as unggul_count' => function ($q) {
                    $q->where('status', 'lulus')->where('predikat', 'Unggul');
                },
                'siswa as baik_count' => function ($q) {
                    $q->where('status', 'lulus')->where('predikat', 'Baik');
                },
                'siswa as cukup_count' => function ($q) {
                    $q->where('status', 'lulus')->where('predikat', 'Cukup');
                },
            ])->get();

            $totalLulus = Pendaftaran::where('status', 'lulus')->count();

            return view('klasifikasi.pembagian', compact('kelas', 'totalLulus'));
        }

    public function prosesKelas()
    {
        $kelas = Kelas::whereIn('nama_kelas', ['7A', '7B', '7C'])
            ->orderBy('nama_kelas')
            ->get();

        if ($kelas->isEmpty()) {
            return back()->with('error', 'Kelas 7A, 7B, 7C belum dibuat!');
        }

        // Reset pembagian kelas hanya untuk siswa lulus
        Pendaftaran::where('status', 'lulus')->update(['id_kelas' => null]);

        $unggul = Pendaftaran::with('nilaiTes')
            ->where('status', 'lulus')
            ->where('predikat', 'Unggul')
            ->get()
            ->sortByDesc(fn($s) => optional($s->nilaiTes)->total_nilai ?? 0)
            ->values();

        $baik = Pendaftaran::with('nilaiTes')
            ->where('status', 'lulus')
            ->where('predikat', 'Baik')
            ->get()
            ->sortByDesc(fn($s) => optional($s->nilaiTes)->total_nilai ?? 0)
            ->values();

        $cukup = Pendaftaran::with('nilaiTes')
            ->where('status', 'lulus')
            ->where('predikat', 'Cukup')
            ->get()
            ->sortByDesc(fn($s) => optional($s->nilaiTes)->total_nilai ?? 0)
            ->values();

        $kelasList = $kelas->values();
        $jumlahKelas = $kelasList->count();
        $diproses = 0;

        foreach ([$unggul, $baik, $cukup] as $kelompok) {
            $idx = 0;

            foreach ($kelompok as $siswa) {
                $k = $kelasList[$idx % $jumlahKelas];

                $terisi = Pendaftaran::where('id_kelas', $k->id)
                    ->where('status', 'lulus')
                    ->count();

                if ($terisi >= $k->kuota) {
                    $found = false;

                    for ($i = 1; $i < $jumlahKelas; $i++) {
                        $kAlt = $kelasList[($idx + $i) % $jumlahKelas];
                        $terisiAlt = Pendaftaran::where('id_kelas', $kAlt->id)
                            ->where('status', 'lulus')
                            ->count();

                        if ($terisiAlt < $kAlt->kuota) {
                            $k = $kAlt;
                            $found = true;
                            break;
                        }
                    }

                    if (!$found) {
                        continue;
                    }
                }

                $siswa->update([
                    'id_kelas' => $k->id
                ]);

                $diproses++;
                $idx++;
            }
        }

        return redirect()
            ->route('klasifikasi.pembagian')
            ->with('success', $diproses . ' siswa lulus berhasil dibagi ke kelas!');
    }
}