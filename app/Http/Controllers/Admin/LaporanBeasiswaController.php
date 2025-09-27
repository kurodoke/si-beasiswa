<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanBeasiswa;
use Inertia\Inertia;
use App\Models\Periode;
use App\Models\Beasiswa;
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
    public function create(string $periode_id, string $tahun_mulai)
    {
        
        $periode = Periode::where('periode', $periode_id)
            ->where('tahun_mulai', $tahun_mulai)
            ->firstOrFail();

        $beasiswa = Beasiswa::all();

        return Inertia::render('LaporanBeasiswa/Create', [
            'periode' => $periode,
            'beasiswa' => $beasiswa
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'angkatan' => 'required|digits:4',
            'npm'=> ['required', 'regex:/^\d{6}$/'],
            'np_hp'=> ['required', 'regex:/^\d{9,11}$/'],
            'beasiswa' => 'required|exists:beasiswas,id',
            'penerimaan' => 'required|date_format:Y-m-01',
            'selesai' => 'required|date_format:Y-m-01|after_or_equal:penerimaan',
            'dokumen_bukti' => 'required|file|mimes:pdf|max:2048',
            'periode_id' => 'required|exists:periodes,id',
        ]);

        $laporan = LaporanBeasiswa::create([
            'nama_mahasiswa' => $validated['name'],
            'angkatan' => $validated['angkatan'],
            'npm' => 'G1A' . $validated['npm'],
            'no_hp' => '+62' . $validated['np_hp'], 
            'beasiswa_id' => $validated['beasiswa'],
            'periode_id' => $validated['periode_id'],
            'penerimaan_beasiswa' => $validated['penerimaan'],
            'selesai_beasiswa' => $validated['selesai'],
            'status_validasi' => 'pending',
        ]);

        if ($request->hasFile('dokumen_bukti')) {
            $file = $request->file('dokumen_bukti');
            $path = "storage/" . $file->store('dokumen_bukti', 'public');

            $filename = basename($path);

            $laporan->dokumenBukti()->create([
                'nama_file' => $filename,
                'path_file' => $path,
            ]);
        }

        return redirect()->back()->with('success', 'Laporan Beasiswa berhasil dikirim, silahkan menunggu untuk diverifikasi. Terimakasih telah mengisi formulir ini.');
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
