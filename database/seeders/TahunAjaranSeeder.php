<?php

namespace Database\Seeders;

use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class TahunAjaranSeeder extends Seeder
{
    public function run(): void
    {
        TahunAjaran::create([
            'nama_tahun_ajaran' => '2025/2026',
            'is_active' => true,
        ]);
    }
}