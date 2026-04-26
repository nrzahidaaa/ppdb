<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE pendaftarans
            MODIFY status ENUM('waiting_proses', 'pending', 'verifikasi', 'lulus', 'ditolak')
            NOT NULL DEFAULT 'waiting_proses'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE pendaftarans
            MODIFY status ENUM('pending', 'verifikasi', 'lulus', 'ditolak')
            NOT NULL DEFAULT 'pending'
        ");
    }
};