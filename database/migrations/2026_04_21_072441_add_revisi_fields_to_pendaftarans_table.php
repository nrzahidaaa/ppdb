<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->string('status_berkas')->default('pending')->after('status');
            $table->text('catatan_admin')->nullable()->after('status_berkas');
            $table->timestamp('revisi_at')->nullable()->after('catatan_admin');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->dropColumn(['status_berkas', 'catatan_admin', 'revisi_at']);
        });
    }
};