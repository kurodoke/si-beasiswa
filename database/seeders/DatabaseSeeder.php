<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Beasiswa;
use App\Models\LaporanPenerima;
use App\Models\DokumenBukti;
use App\Models\Periode;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Pengguna Validator
        User::factory()->create([
            'name' => 'Validator User',
            'email' => 'validator@gmail.com',
            'password' => Hash::make('validator'),
            'role' => 'validator',
        ]);

        // Pengguna Admin
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'role' => 'admin',
        ]);


        // Beasiswa
        Beasiswa::create([
            'jenis_beasiswa' => 'Internasional',
        ]);
        Beasiswa::create([
            'jenis_beasiswa' => 'Nasional',
        ]);
        Beasiswa::create([
            'jenis_beasiswa' => 'Regional',
        ]);

        Periode::create([
            'nama_periode' => 'Periode 57',
            'bulan_mulai' => 4,
            'tahun_mulai' => 2025,
            'bulan_selesai' => 9,
            'tahun_selesai' => 2025,
        ]);

        // Laporan Penerima
        LaporanPenerima::create([
            'nama_mahasiswa' => 'John Doe',
            'npm' => '12345678',
            'angkatan' => '2023',
            'nama_beasiswa' => 'Beasiswa Internasional',
            'periode_id' => 1,
            'selesai_beasiswa' => '2023-12-01',
            'penerimaan_beasiswa' => '2023-01-01',
            'beasiswa_id' => 1,
            'status_validasi' => 'pending',
        ]);


        // Dokumen Bukti
        DokumenBukti::create([
            'laporan_penerima_id' => 1,
            'nama_file' => '0ewVkc9nwEc2OxqGmKVNx90W6iDzzaIUfY9rGZHN.pdf',
            'path_file' => 'storage/dokumen_bukti/0ewVkc9nwEc2OxqGmKVNx90W6iDzzaIUfY9rGZHN.pdf',
        ]);
    }
}
