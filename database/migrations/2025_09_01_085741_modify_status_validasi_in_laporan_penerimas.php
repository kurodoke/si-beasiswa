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
        Schema::table('laporan_penerimas', function (Blueprint $table) {
        // Status baru untuk alur dua tahap
        $table->enum('status_validasi', [
            'pending',          // Baru disubmit oleh mahasiswa
            'dicek_admin',      // Sudah dicek admin, menunggu verifikator
            'disetujui',        // Final, disetujui verifikator
            'ditolak'           // Final, ditolak di tahap manapun
        ])->default('pending')->change();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_penerimas', function (Blueprint $table) {
            //
        });
    }
};
