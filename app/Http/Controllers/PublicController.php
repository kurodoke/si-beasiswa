<?php

namespace App\Http\Controllers;

use App\Models\LaporanBeasiswa;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Berita;
use App\Models\Periode;
use App\Models\Beasiswa;
use Carbon\Carbon;

class PublicController extends Controller
{
   public function index()
    {
        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;

        $laporan = LaporanBeasiswa::with(['beasiswa', 'verifier', 'dokumenBukti', 'periode'])
            ->where('status_validasi', 'disetujui')
            ->latest()
            ->limit(5)
            ->get();
        $berita = Berita::latest()->limit(3)->get();
        $total_laporan = LaporanBeasiswa::where('status_validasi', 'disetujui')->count();

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

        // Cari periode aktif
        $periode_aktif = Periode::where(function ($query) use ($currentYear, $currentMonth) {
            // Tahun mulai kurang dari tahun sekarang
            // atau sama tahun tapi bulan mulai kurang sama bulan sekarang
            $query->where(function ($q) use ($currentYear, $currentMonth) {
                $q->where('tahun_mulai', '<', $currentYear)
                ->orWhere(function ($q2) use ($currentYear, $currentMonth) {
                    $q2->where('tahun_mulai', $currentYear)
                        ->where('bulan_mulai', '<=', $currentMonth);
                });
            })
            // Dan tahun selesai lebih besar atau sama tahun sekarang
            // dan bulan selesai lebih besar atau sama bulan sekarang
            ->where(function ($q) use ($currentYear, $currentMonth) {
                $q->where('tahun_selesai', '>', $currentYear)
                ->orWhere(function ($q2) use ($currentYear, $currentMonth) {
                    $q2->where('tahun_selesai', $currentYear)
                        ->where('bulan_selesai', '>=', $currentMonth);
                });
            });
        })->first();

        $laporan_per_beasiswa = Beasiswa::withCount([
                'laporanBeasiswa as jumlah_laporan' => function ($query) {
                    $query->where('status_validasi', 'disetujui');
                }
            ])
            ->having('jumlah_laporan', '>', 0) 
            ->get(['id', 'nama_beasiswa', 'jenis_beasiswa']);

        return Inertia::render('Public/Index', [
            'laporan' => $laporan,
            'berita' => $berita,
            'total_laporan' => $total_laporan,
            'laporan_per_periode' => $total_laporan_setiap_periode,
            'periode_aktif' => $periode_aktif,
            'laporan_per_beasiswa' => $laporan_per_beasiswa,
        ]);
    }

    public function berita()
    {
        $berita = Berita::latest()->get();
        return Inertia::render('Public/Berita', [
            'berita' => $berita,
        ]);
    }

    public function beritaDetail(Berita $berita)
    {
        return Inertia::render('Public/Detail', [
            'berita' => $berita,
        ]);
    }

    public function beasiswa()
    {
        $laporan =  $laporan = LaporanBeasiswa::with(['beasiswa', 'verifier', 'dokumenBukti', 'periode'])
            ->where('status_validasi', 'disetujui')
            ->latest()
            ->get();

        $periode_list = Periode::whereIn('id', function ($query) {
            $query->select('periode_id')
                ->from('laporan_beasiswas')
                ->whereNotNull('periode_id');
        })->get();
        return Inertia::render('Public/Beasiswa', [
            'laporan' => $laporan,
            'periode_list' => $periode_list,
        ]);
    }
}