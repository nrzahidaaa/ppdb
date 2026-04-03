<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nilai_tes', function (Blueprint $table) {
            $table->enum('status_hasil', ['lulus', 'ditolak'])
                  ->default('ditolak')
                  ->after('tanggal_input');

            $table->string('keterangan_hasil')->nullable()->after('status_hasil');
        });
    }

    public function down(): void
    {
        Schema::table('nilai_tes', function (Blueprint $table) {
            $table->dropColumn(['status_hasil', 'keterangan_hasil']);
        });
    }
};