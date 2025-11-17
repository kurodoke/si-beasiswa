<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\User;
use App\Models\LaporanBeasiswa;
use App\Models\Periode;
use App\Models\Beasiswa;
use App\Models\Berita;


class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $now = now();
        $lastMonth = now()->subDays(30);

        // Total laporan
        $total = LaporanBeasiswa::count();
        $totalLastMonth = LaporanBeasiswa::where('created_at', '<', $lastMonth)->count();
        $deltaTotal = $total - $totalLastMonth;

        // Laporan belum diverifikasi
        $unverified = LaporanBeasiswa::where('status_validasi', '!=', 'disetujui')->count();
        $verifiedThisMonth = LaporanBeasiswa::where('status_validasi', 'disetujui')
            ->whereBetween('verified_at', [$lastMonth, $now])
            ->count();
        $deltaUnverified = -$verifiedThisMonth;

        // Laporan sudah diverifikasi
        $verified = LaporanBeasiswa::where('status_validasi', 'disetujui')->count();
        $verifiedLastMonth = LaporanBeasiswa::where('status_validasi', 'disetujui')
            ->where('verified_at', '<', $lastMonth)
            ->count();
        $deltaVerified = $verified - $verifiedLastMonth;

        $total_laporan_setiap_periode = Periode::select(
            'id',
            'periode',
            'bulan_mulai',
            'tahun_mulai',
            'bulan_selesai',
            'tahun_selesai'
        )
        ->withCount([
            'laporanBeasiswa as jumlah_laporan' => function ($query) {
                $query->where('status_validasi', 'disetujui');
            }
        ])
        ->get();

        $laporan = LaporanBeasiswa::with(['beasiswa', 'verifier', 'dokumenBukti', 'periode'])
            ->where('status_validasi', 'disetujui')
            ->latest()
            ->limit(5)
            ->get();

        $laporan_per_beasiswa = Beasiswa::withCount([
                'laporanBeasiswa as jumlah_laporan' => function ($query) {
                    $query->where('status_validasi', 'disetujui');
                }
            ])
            ->having('jumlah_laporan', '>', 0) 
            ->get(['id', 'nama_beasiswa', 'jenis_beasiswa']);

        return Inertia::render('Dashboard/Index', [
            'laporan' => $laporan,

            // data untuk UI dashboard
            'summary' => [
                'total_laporan' => $total,
                'delta_total' => $deltaTotal,

                'belum_diverifikasi' => $unverified,
                'delta_belum_diverifikasi' => $deltaUnverified,

                'sudah_diverifikasi' => $verified,
                'delta_sudah_diverifikasi' => $deltaVerified,
            ],
            'laporan_per_periode' => $total_laporan_setiap_periode,

            'laporan_per_beasiswa' => $laporan_per_beasiswa,

        ]);
    }
}