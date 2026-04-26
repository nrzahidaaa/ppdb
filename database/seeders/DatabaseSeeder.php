<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\TahunAjaran;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
         User::updateOrCreate(
            ['email' => 'admin@ppdb.sch.id'],
            [
                'name' => 'Admin PPDB',
                'password' => Hash::make('password'),
            ]
        );

        TahunAjaran::updateOrCreate(
            ['nama_tahun_ajaran' => '2025/2026'],
            [
                'is_active' => true,
            ]
        );

        // ===== KELAS =====
        $kelas = [
            ['X MIPA 1', 'MIPA',   'Ibu Siti Aminah, S.Pd',    40],
            ['X MIPA 2', 'MIPA',   'Bpk. Ahmad Fauzi, M.Pd',   40],
            ['X MIPA 3', 'MIPA',   'Ibu Rina Kusuma, S.Pd',    40],
            ['X IPS 1',  'IPS',    'Ibu Dewi Kartika, S.Pd',   40],
            ['X IPS 2',  'IPS',    'Bpk. Rizal Hamid, S.Pd',   40],
            ['X IPS 3',  'IPS',    'Ibu Laila Sari, M.Pd',     40],
            ['X Bahasa', 'Bahasa', 'Ibu Nur Hasanah, M.Pd',    40],
            ['X MIPA 4', 'MIPA',   'Bpk. Hendra Putra, S.Pd', 40],
            ['X IPS 4',  'IPS',    'Ibu Sri Wahyuni, S.Pd',    40],
        ];

        foreach ($kelas as $k) {
            DB::table('kelas')->insert([
                'nama_kelas'   => $k[0],
                'jurusan'      => $k[1],
                'wali_kelas'   => $k[2],
                'kuota'        => $k[3],
                'tahun_ajaran' => '2025/2026',
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }

        // ===== SAMPLE PENDAFTARAN =====
        $pendaftar = [
            ['Andi Nugroho',   '3120001001', 'SMP N 1 Contoh',   'MIPA',   88.5, 'verifikasi'],
            ['Sari Rahayu',    '3120001002', 'SMP N 2 Contoh',   'IPS',    91.2, 'lulus'],
            ['Budi Pratama',   '3120001003', 'MTs Al-Hikmah',    'MIPA',   79.8, 'pending'],
            ['Dewi Lestari',   '3120001004', 'SMP Swasta Maju',  'Bahasa', 85.0, 'lulus'],
            ['Rizki Putra',    '3120001005', 'SMP N 3 Contoh',   'IPS',    62.3, 'ditolak'],
            ['Maya Sari',      '3120001006', 'SMP N 1 Contoh',   'MIPA',   93.7, 'lulus'],
            ['Dani Firmansyah','3120001007', 'SMP Islam Terpadu','IPS',    76.4, 'verifikasi'],
            ['Fitri Handayani','3120001008', 'MTs Darul Ulum',   'Bahasa', 82.1, 'pending'],
        ];

        foreach ($pendaftar as $i => $p) {
            DB::table('pendaftarans')->insert([
                'nomor_pendaftaran' => 'PPDB-2025-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'nama'              => $p[0],
                'nisn'              => $p[1],
                'tempat_lahir'      => 'Kota Contoh',
                'tanggal_lahir'     => '2009-01-' . str_pad($i + 1, 2, '0', STR_PAD_LEFT),
                'jenis_kelamin'     => $i % 2 === 0 ? 'L' : 'P',
                'asal_sekolah'      => $p[2],
                'pilihan_jurusan'   => $p[3],
                'nilai_rata_rata'   => $p[4],
                'nama_orang_tua'    => 'Orang Tua ' . $p[0],
                'no_telp'           => '0812' . str_pad($i * 11111, 8, '0'),
                'alamat'            => 'Jl. Contoh No. ' . ($i + 1) . ', Kota Contoh',
                'status'            => $p[5],
                'berkas_lengkap'    => $p[5] !== 'pending',
                'created_at'        => now()->subDays(rand(1, 30)),
                'updated_at'        => now(),
            ]);
        }
    }
}
