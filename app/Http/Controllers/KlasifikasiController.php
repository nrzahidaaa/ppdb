<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Kelas;
use App\Models\NilaiTes;
use App\Services\NaiveBayes;
use Illuminate\Http\Request;
use App\Helpers\TahunAjaranHelper;

class KlasifikasiController extends Controller
{
public function index()
{
    $tahunAjaranId = \App\Helpers\TahunAjaranHelper::getSelectedId();

    $pending = \App\Models\Pendaftaran::where('tahun_ajaran_id', $tahunAjaranId)
        ->whereHas('nilaiTes', function ($q) {
            $q->whereNull('status_hasil')
              ->orWhere('status_hasil', 'pending');
        })
        ->count();

    $lulus = \App\Models\Pendaftaran::where('tahun_ajaran_id', $tahunAjaranId)
        ->whereHas('nilaiTes', function ($q) {
            $q->where('status_hasil', 'lulus');
        })
        ->count();

    $tidakLulus = \App\Models\Pendaftaran::where('tahun_ajaran_id', $tahunAjaranId)
        ->whereHas('nilaiTes', function ($q) {
            $q->whereIn('status_hasil', ['ditolak', 'tidak_lulus']);
        })
        ->count();

    $kelas = \App\Models\Kelas::withCount(['siswa' => function ($q) use ($tahunAjaranId) {
        $q->where('tahun_ajaran_id', $tahunAjaranId);
    }])->get();

    $unggul = \App\Models\Pendaftaran::where('tahun_ajaran_id', $tahunAjaranId)
        ->whereHas('nilaiTes', function ($q) {
            $q->where('status_hasil', 'lulus');
        })
        ->where('predikat', 'Unggul')
        ->count();

    $baik = \App\Models\Pendaftaran::where('tahun_ajaran_id', $tahunAjaranId)
        ->whereHas('nilaiTes', function ($q) {
            $q->where('status_hasil', 'lulus');
        })
        ->where('predikat', 'Baik')
        ->count();

    $cukup = \App\Models\Pendaftaran::where('tahun_ajaran_id', $tahunAjaranId)
        ->whereHas('nilaiTes', function ($q) {
            $q->where('status_hasil', 'lulus');
        })
        ->where('predikat', 'Cukup')
        ->count();

    $hasilSession = session('hasil', []);
    $hasilMap = collect($hasilSession)->keyBy('nama');

    $hasilKlasifikasi = \App\Models\Pendaftaran::with('nilaiTes')
        ->where('tahun_ajaran_id', $tahunAjaranId)
        ->whereHas('nilaiTes', function ($q) {
            $q->where('status_hasil', 'lulus');
        })
        ->whereNotNull('predikat')
        ->orderByDesc('updated_at')
        ->get();

    $trainingData = \App\Models\Pendaftaran::with('nilaiTes')
        ->where('tahun_ajaran_id', $tahunAjaranId)
        ->whereHas('nilaiTes')
        ->get()
        ->map(function ($p) {
            $n = $p->nilaiTes;

            return [
                'ipa'              => (int) ($n->ipa ?? 0),
                'ips'              => (int) ($n->ips ?? 0),
                'bhs_indonesia'    => (int) ($n->bhs_indonesia ?? 0),
                'matematika'       => (int) ($n->matematika ?? 0),
                'doa_iftitah'      => (int) ($n->doa_iftitah ?? 0),
                'tahiyat_awal'     => (int) ($n->tahiyat_awal ?? 0),
                'qunut'            => (int) ($n->qunut ?? 0),
                'membaca_al_quran' => (int) ($n->membaca_al_quran ?? 0),
                'fatihah_4'        => (int) ($n->fatihah_4 ?? 0),
                'doa'              => (int) ($n->doa ?? 0),
                'menulis'          => (int) ($n->menulis ?? 0),
            ];
        })
        ->toArray();

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
    $tahunAjaranId = \App\Helpers\TahunAjaranHelper::getSelectedId();

    $allNilai = \App\Models\Pendaftaran::with('nilaiTes')
        ->where('tahun_ajaran_id', $tahunAjaranId)
        ->whereHas('nilaiTes')
        ->get()
        ->map(function ($p) {
            $n = $p->nilaiTes;

            return [
                'bhs_indonesia'    => (int) ($n->bhs_indonesia ?? 0),
                'matematika'       => (int) ($n->matematika ?? 0),
                'ipa'              => (int) ($n->ipa ?? 0),
                'ips'              => (int) ($n->ips ?? 0),
                'agama'            => (int) ($n->agama ?? 0),
                'doa_iftitah'      => (int) ($n->doa_iftitah ?? 0),
                'tahiyat_awal'     => (int) ($n->tahiyat_awal ?? 0),
                'qunut'            => (int) ($n->qunut ?? 0),
                'membaca_al_quran' => (int) ($n->membaca_al_quran ?? 0),
                'fatihah_4'        => (int) ($n->fatihah_4 ?? 0),
                'surah_pendek'     => (int) ($n->surah_pendek ?? 0),
                'doa'              => (int) ($n->doa ?? 0),
                'menulis'          => (int) ($n->menulis ?? 0),
            ];
        })
        ->toArray();

    if (count($allNilai) < 3) {
        return redirect()->route('klasifikasi.index')
            ->with('error', 'Data nilai tes minimal 3 siswa untuk training Naive Bayes!');
    }

    $nb = new \App\Services\NaiveBayes();
    $nb->train($allNilai);

    $pendaftar = \App\Models\Pendaftaran::where('tahun_ajaran_id', $tahunAjaranId)
        ->whereHas('nilaiTes', function ($q) {
            $q->where('status_hasil', 'lulus');
        })
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
            'bhs_indonesia'    => (int) ($n->bhs_indonesia ?? 0),
            'matematika'       => (int) ($n->matematika ?? 0),
            'ipa'              => (int) ($n->ipa ?? 0),
            'ips'              => (int) ($n->ips ?? 0),
            'agama'            => (int) ($n->agama ?? 0),
            'doa_iftitah'      => (int) ($n->doa_iftitah ?? 0),
            'tahiyat_awal'     => (int) ($n->tahiyat_awal ?? 0),
            'qunut'            => (int) ($n->qunut ?? 0),
            'membaca_al_quran' => (int) ($n->membaca_al_quran ?? 0),
            'fatihah_4'        => (int) ($n->fatihah_4 ?? 0),
            'surah_pendek'     => (int) ($n->surah_pendek ?? 0),
            'doa'              => (int) ($n->doa ?? 0),
            'menulis'          => (int) ($n->menulis ?? 0),
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
    $tahunAjaranId = \App\Helpers\TahunAjaranHelper::getSelectedId();

    $kelas = \App\Models\Kelas::where('tahun_ajaran_id', $tahunAjaranId)
        ->with([
            'siswa' => function ($q) use ($tahunAjaranId) {
                $q->where('tahun_ajaran_id', $tahunAjaranId)
                    ->where('status', 'lulus')
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
        ])
        ->withCount([
            'siswa as total_siswa' => function ($q) use ($tahunAjaranId) {
                $q->where('tahun_ajaran_id', $tahunAjaranId)
                    ->where('status', 'lulus');
            },
            'siswa as unggul_count' => function ($q) use ($tahunAjaranId) {
                $q->where('tahun_ajaran_id', $tahunAjaranId)
                    ->where('status', 'lulus')
                    ->where('predikat', 'Unggul');
            },
            'siswa as baik_count' => function ($q) use ($tahunAjaranId) {
                $q->where('tahun_ajaran_id', $tahunAjaranId)
                    ->where('status', 'lulus')
                    ->where('predikat', 'Baik');
            },
            'siswa as cukup_count' => function ($q) use ($tahunAjaranId) {
                $q->where('tahun_ajaran_id', $tahunAjaranId)
                    ->where('status', 'lulus')
                    ->where('predikat', 'Cukup');
            },
        ])
        ->get();

    $totalLulus = \App\Models\Pendaftaran::where('tahun_ajaran_id', $tahunAjaranId)
        ->where('status', 'lulus')
        ->count();

    return view('klasifikasi.pembagian', compact('kelas', 'totalLulus'));
}

public function prosesKelas()
{
    $tahunAjaranId = \App\Helpers\TahunAjaranHelper::getSelectedId();

    $kelas = \App\Models\Kelas::where('tahun_ajaran_id', $tahunAjaranId)
        ->whereIn('nama_kelas', ['7A', '7B', '7C'])
        ->orderBy('nama_kelas')
        ->get();

    if ($kelas->isEmpty()) {
        return back()->with('error', 'Kelas 7A, 7B, 7C belum dibuat pada tahun ajaran ini!');
    }

    \App\Models\Pendaftaran::where('tahun_ajaran_id', $tahunAjaranId)
        ->where('status', 'lulus')
        ->update(['id_kelas' => null]);

    $unggul = \App\Models\Pendaftaran::with('nilaiTes')
        ->where('tahun_ajaran_id', $tahunAjaranId)
        ->where('status', 'lulus')
        ->where('predikat', 'Unggul')
        ->get()
        ->sortByDesc(fn($s) => optional($s->nilaiTes)->total_nilai ?? 0)
        ->values();

    $baik = \App\Models\Pendaftaran::with('nilaiTes')
        ->where('tahun_ajaran_id', $tahunAjaranId)
        ->where('status', 'lulus')
        ->where('predikat', 'Baik')
        ->get()
        ->sortByDesc(fn($s) => optional($s->nilaiTes)->total_nilai ?? 0)
        ->values();

    $cukup = \App\Models\Pendaftaran::with('nilaiTes')
        ->where('tahun_ajaran_id', $tahunAjaranId)
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

            $terisi = \App\Models\Pendaftaran::where('tahun_ajaran_id', $tahunAjaranId)
                ->where('id_kelas', $k->id)
                ->where('status', 'lulus')
                ->count();

            if ($terisi >= $k->kuota) {
                $found = false;

                for ($i = 1; $i < $jumlahKelas; $i++) {
                    $kAlt = $kelasList[($idx + $i) % $jumlahKelas];

                    $terisiAlt = \App\Models\Pendaftaran::where('tahun_ajaran_id', $tahunAjaranId)
                        ->where('id_kelas', $kAlt->id)
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