<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->dropUnique('pendaftarans_nomor_pendaftaran_unique');
            $table->unique(['tahun_ajaran_id', 'nomor_pendaftaran'], 'pendaftarans_tahun_ajaran_nomor_unique');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->dropUnique('pendaftarans_tahun_ajaran_nomor_unique');
            $table->unique('nomor_pendaftaran', 'pendaftarans_nomor_pendaftaran_unique');
        });
    }
};