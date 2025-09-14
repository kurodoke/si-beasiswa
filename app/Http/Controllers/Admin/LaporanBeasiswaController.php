<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanBeasiswa;
use Inertia\Inertia;
use App\Models\Periode;
use Illuminate\Support\Facades\Auth;

class LaporanBeasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
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

        $laporan = LaporanBeasiswa::with(['beasiswa', 'verifier', 'dokumenBukti', 'periode'])->get();

        $periode_list = Periode::whereIn('id', function ($query) {
            $query->select('periode_id')
                ->from('laporan_beasiswas')
                ->whereNotNull('periode_id');
        })->get();

        return Inertia::render('Admin/LaporanBeasiswa/Index', [
            'data' => $laporan,
            'periode_list' => $periode_list,

            // data untuk UI dashboard
            'summary' => [
                'total_laporan' => $total,
                'delta_total' => $deltaTotal,

                'belum_diverifikasi' => $unverified,
                'delta_belum_diverifikasi' => $deltaUnverified,

                'sudah_diverifikasi' => $verified,
                'delta_sudah_diverifikasi' => $deltaVerified,
            ],
        ]);
    }

    public function indexVerified()
    {
        $laporan = LaporanBeasiswa::with(['beasiswa', 'verifier', 'dokumenBukti', 'periode'])
            ->where('status_validasi', 'disetujui')
            ->get();

        $periode_list = Periode::whereIn('id', function ($query) {
            $query->select('periode_id')
                ->from('laporan_beasiswas')
                ->whereNotNull('periode_id');
        })->get();

        return Inertia::render('Admin/LaporanBeasiswa/IndexVerified', [
            'data' => $laporan,
            'periode_list' => $periode_list,
        ]);
    }

    public function indexUnverified()
    {
        $laporan = LaporanBeasiswa::with(['beasiswa', 'verifier', 'dokumenBukti', 'periode'])
            ->where('status_validasi', 'pending')
            ->get();

        $periode_list = Periode::whereIn('id', function ($query) {
            $query->select('periode_id')
                ->from('laporan_beasiswas')
                ->whereNotNull('periode_id');
        })->get();

        return Inertia::render('Admin/LaporanBeasiswa/IndexUnverified', [
            'data' => $laporan,
            'periode_list' => $periode_list,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $laporan = LaporanBeasiswa::findOrFail($id);

        // Pastikan hanya role validator yang bisa memverifikasi
        if (Auth::user()->role !== 'validator') {
            abort(403, 'Unauthorized');
        }

        $laporan->status_validasi = 'disetujui';
        $laporan->verified_at = now();
        $laporan->verified_by = Auth::id(); // ID user yang sedang login
        $laporan->save();

        return redirect()->back()->with('success', 'Laporan berhasil diverifikasi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $laporan = LaporanBeasiswa::findOrFail($id);

        $laporan->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }

    
}
