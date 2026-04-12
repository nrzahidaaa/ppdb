<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
{
    Schema::table('pendaftarans', function (Blueprint $table) {
        // Data Pribadi
        $table->string('nik', 20)->nullable()->after('nisn');
        $table->string('hobi')->nullable();
        $table->string('cita_cita')->nullable();
        $table->integer('anak_ke')->nullable();
        $table->integer('jumlah_saudara')->nullable();
        $table->string('status_tinggal')->nullable(); // tinggal dengan siapa

        // Sekolah
        $table->string('nama_sekolah')->nullable();
        $table->string('jenis_sekolah')->nullable(); // SD/MI
        $table->string('status_sekolah')->nullable(); // Negeri/Swasta
        $table->string('npsn_sekolah')->nullable();

        // Alamat
        $table->string('desa_kelurahan')->nullable();
        $table->string('kecamatan')->nullable();
        $table->string('kabupaten_kota')->nullable();
        $table->string('kode_pos', 10)->nullable();

        // Orang Tua
        $table->string('no_kk', 20)->nullable();
        $table->string('nama_kepala_keluarga')->nullable();
        $table->string('status_kepemilikan_rumah')->nullable();

        // Ayah
        $table->string('nama_ayah')->nullable();
        $table->string('nik_ayah', 20)->nullable();
        $table->string('status_ayah')->nullable();
        $table->string('pendidikan_ayah')->nullable();
        $table->string('pekerjaan_ayah')->nullable();
        $table->string('penghasilan_ayah')->nullable();
        $table->string('no_hp_ayah', 20)->nullable();

        // Ibu
        $table->string('nama_ibu')->nullable();
        $table->string('nik_ibu', 20)->nullable();
        $table->string('status_ibu')->nullable();
        $table->string('pendidikan_ibu')->nullable();
        $table->string('pekerjaan_ibu')->nullable();
        $table->string('penghasilan_ibu')->nullable();
        $table->string('no_hp_ibu', 20)->nullable();

        // Wali (opsional)
        $table->string('nama_wali')->nullable();
        $table->string('nik_wali', 20)->nullable();
        $table->string('status_wali')->nullable(); // hubungan: kakak/nenek/paman/bibi
        $table->string('pendidikan_wali')->nullable();
        $table->string('pekerjaan_wali')->nullable();
        $table->string('penghasilan_wali')->nullable();
        $table->string('no_hp_wali', 20)->nullable();

        // PIP
        $table->string('no_kks')->nullable();
        $table->string('no_pkh')->nullable();
        $table->string('no_kip')->nullable();
    });
}

public function down()
{
    Schema::table('pendaftarans', function (Blueprint $table) {
        $table->dropColumn([
            'nik', 'hobi', 'cita_cita', 'anak_ke', 'jumlah_saudara', 'status_tinggal',
            'nama_sekolah', 'jenis_sekolah', 'status_sekolah', 'npsn_sekolah',
            'desa_kelurahan', 'kecamatan', 'kabupaten_kota', 'kode_pos',
            'no_kk', 'nama_kepala_keluarga', 'status_kepemilikan_rumah',
            'nama_ayah', 'nik_ayah', 'status_ayah', 'pendidikan_ayah', 'pekerjaan_ayah', 'penghasilan_ayah', 'no_hp_ayah',
            'nama_ibu', 'nik_ibu', 'status_ibu', 'pendidikan_ibu', 'pekerjaan_ibu', 'penghasilan_ibu', 'no_hp_ibu',
            'nama_wali', 'nik_wali', 'status_wali', 'pendidikan_wali', 'pekerjaan_wali', 'penghasilan_wali', 'no_hp_wali',
            'no_kks', 'no_pkh', 'no_kip',
        ]);
    });
}
};
