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
            $table->timestamp('verified_at')->nullable()->after('status_validasi');
            $table->foreignId('verified_by')->nullable()->constrained('users')->after('verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_penerimas', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn(['verified_at', 'verified_by']);
        });
    }
};