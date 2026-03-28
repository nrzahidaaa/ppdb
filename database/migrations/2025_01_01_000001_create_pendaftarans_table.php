<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pendaftaran')->unique();
            $table->string('nama', 100);
            $table->string('nisn', 20)->unique();
            $table->string('tempat_lahir', 100);
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('asal_sekolah', 150);
            $table->enum('pilihan_jurusan', ['MIPA', 'IPS', 'Bahasa']);
            $table->decimal('nilai_rata_rata', 5, 2)->default(0);
            $table->string('nama_orang_tua', 100);
            $table->string('no_telp', 20);
            $table->text('alamat');
            $table->enum('status', ['pending', 'verifikasi', 'lulus', 'ditolak'])->default('pending');
            $table->boolean('berkas_lengkap')->default(false);
            $table->text('catatan')->nullable();
$table->unsignedBigInteger('kelas_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftarans');
    }
};
