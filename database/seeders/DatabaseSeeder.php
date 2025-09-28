<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Beasiswa;
use App\Models\LaporanBeasiswa;
use App\Models\DokumenBukti;
use App\Models\Periode;
use App\Models\Berita;
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
            'nama_beasiswa' => 'Beasiswa Oxford',
            'jenis_beasiswa' => 'Internasional',
        ]);
        Beasiswa::create([
            'nama_beasiswa' => 'Beasiswa Harvard',
            'jenis_beasiswa' => 'Nasional',
        ]);
        Beasiswa::create([
            'nama_beasiswa' => 'Beasiswa UNIB',
            'jenis_beasiswa' => 'Regional',
        ]);

        Periode::create([
            'periode' => "Ganjil",
            'bulan_mulai' => 4,
            'tahun_mulai' => 2025,
            'bulan_selesai' => 9,
            'tahun_selesai' => 2025,
        ]);

        Periode::create([
            'periode' => "Genap",
            'bulan_mulai' => 10,
            'tahun_mulai' => 2025,
            'bulan_selesai' => 3,
            'tahun_selesai' => 2026,
        ]);

        // Laporan Penerima
        LaporanBeasiswa::create([
            'nama_mahasiswa' => 'John Doe',
            'npm' => '12345678',
            'angkatan' => '2023',
            'no_hp' => '087654321',
            'periode_id' => 1,
            'selesai_beasiswa' => '2023-12-01',
            'penerimaan_beasiswa' => '2023-01-01',
            'beasiswa_id' => 1,
            'status_validasi' => 'pending',
        ]);

        LaporanBeasiswa::create([
            'nama_mahasiswa' => 'HAHAHAHA',
            'npm' => '12345678',
            'angkatan' => '2023',
            'no_hp' => '1234567890',
            'periode_id' => 2,
            'selesai_beasiswa' => '2023-12-01',
            'penerimaan_beasiswa' => '2023-01-01',
            'beasiswa_id' => 3,
            'status_validasi' => 'pending',
        ]);


        // Dokumen Bukti
        DokumenBukti::create([
            'laporan_beasiswa_id' => 1,
            'nama_file' => '0ewVkc9nwEc2OxqGmKVNx90W6iDzzaIUfY9rGZHN.pdf',
            'path_file' => 'storage/dokumen_bukti/0ewVkc9nwEc2OxqGmKVNx90W6iDzzaIUfY9rGZHN.pdf',
        ]);

        DokumenBukti::create([
            'laporan_beasiswa_id' => 2,
            'nama_file' => 'SBUtoRVX6JmZVvqNSz9pIpQmuCyUWoi9bZ2HEMfi.pdf',
            'path_file' => 'storage/dokumen_bukti/SBUtoRVX6JmZVvqNSz9pIpQmuCyUWoi9bZ2HEMfi.pdf',
        ]);

        Berita::create([
            'judul' => 'Berita 1',
            'konten' => json_encode([
                "root" => [
                    "children" => [
                        [
                            "children" => [
                                [
                                    "detail" => 0,
                                    "format" => 0,
                                    "mode" => "normal",
                                    "style" => "",
                                    "text" => "Hello world",
                                    "type" => "text",
                                    "version" => 1,
                                ]
                            ],
                            "direction" => null,
                            "format" => "",
                            "indent" => 0,
                            "type" => "paragraph",
                            "version" => 1,
                        ]
                    ],
                    "direction" => null,
                    "format" => "",
                    "indent" => 0,
                    "type" => "root",
                    "version" => 1,
                ]
            ])
        ]);

    }
}
