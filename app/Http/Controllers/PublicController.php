<?php

namespace App\Http\Controllers;

use App\Models\LaporanBeasiswa;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PublicController extends Controller
{
    /**
     * Menampilkan halaman utama yang berisi daftar penerima beasiswa.
     */
    public function index()
    {
        // Ambil 6 data terbaru yang sudah disetujui untuk ditampilkan di landing page

        return Inertia::render('Public/Index', [
        ]);
    }
}