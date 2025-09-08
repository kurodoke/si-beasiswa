<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan_penerimas', function (Blueprint $table) {
            // Ubah user_id agar boleh null
            $table->foreignId('user_id')->nullable()->change();
            
            // Tambah kolom untuk data mahasiswa mentah
            $table->string('nama_mahasiswa')->after('id');
            $table->string('npm')->unique()->after('nama_mahasiswa');
            $table->foreignId('program_studi_id')->nullable()->after('npm')->constrained('program_studis')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('laporan_penerimas', function (Blueprint $table) {
            $table->dropForeign(['program_studi_id']);
            $table->dropColumn(['nama_mahasiswa', 'npm', 'program_studi_id']);
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};