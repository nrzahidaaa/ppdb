<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilai_tes', function (Blueprint $table) {
            $table->increments('id_nilai');
            $table->unsignedBigInteger('id_siswa')->nullable();
            $table->integer('bhs_indonesia')->nullable();
            $table->integer('matematika')->nullable();
            $table->integer('ipa')->nullable();
            $table->integer('ips')->nullable();
            $table->integer('agama')->nullable();
            $table->integer('doa_iftitah')->nullable();
            $table->integer('tahiyat_awal')->nullable();
            $table->integer('qunut')->nullable();
            $table->integer('membaca_al_quran')->nullable();
            $table->integer('fatihah_4')->nullable();
            $table->integer('surah_pendek')->nullable();
            $table->integer('doa')->nullable();
            $table->integer('menulis')->nullable();
            $table->date('tanggal_input')->nullable();
            $table->timestamps();

           // $table->foreign('id_siswa')->references('id')->on('pendaftarans')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_tes');
    }
};