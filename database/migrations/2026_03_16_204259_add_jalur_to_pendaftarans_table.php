<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('pendaftarans', function (Blueprint $table) {
        $table->enum('jalur', ['reguler', 'prestasi'])->default('reguler')->after('pilihan_jurusan');
    });
}

public function down()
{
    Schema::table('pendaftarans', function (Blueprint $table) {
        $table->dropColumn('jalur');
    });
}
};
