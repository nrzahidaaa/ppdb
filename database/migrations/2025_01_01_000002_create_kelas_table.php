<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas', 30);
            $table->enum('jurusan', ['MIPA', 'IPS', 'Bahasa']);
            $table->string('wali_kelas', 100)->nullable();
            $table->unsignedInteger('kuota')->default(40);
            $table->string('tahun_ajaran', 20)->default('2025/2026');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
