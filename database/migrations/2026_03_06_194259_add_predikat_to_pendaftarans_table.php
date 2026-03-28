<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('pendaftarans', function (Blueprint $table) {
        $table->string('predikat')->nullable()->after('status');
    });
}

public function down()
{
    Schema::table('pendaftarans', function (Blueprint $table) {
        $table->dropColumn('predikat');
    });
}
};
