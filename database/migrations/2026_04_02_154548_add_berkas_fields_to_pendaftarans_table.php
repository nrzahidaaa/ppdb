<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->string('nisn_file')->nullable()->after('berkas_lengkap');
            $table->string('kartu_keluarga')->nullable()->after('nisn_file');
            $table->string('akta_kelahiran')->nullable()->after('kartu_keluarga');
            $table->string('foto')->nullable()->after('akta_kelahiran');
            $table->string('ijazah')->nullable()->after('foto');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->dropColumn([
                'nisn_file',
                'kartu_keluarga',
                'akta_kelahiran',
                'foto',
                'ijazah',
            ]);
        });
    }
};