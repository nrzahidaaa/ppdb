<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('pendaftarans', function (Blueprint $table) {
        $table->string('nisn')->nullable()->change();
        $table->string('no_telp')->nullable()->change();
        $table->string('jenis_kelamin')->nullable()->change();
        $table->date('tanggal_lahir')->nullable()->change();
    });
}

public function down(): void
{
    Schema::table('pendaftarans', function (Blueprint $table) {
        $table->string('nisn')->nullable(false)->change();
        $table->string('no_telp')->nullable(false)->change();
        $table->string('jenis_kelamin')->nullable(false)->change();
        $table->date('tanggal_lahir')->nullable()->change();
    });

}
};
