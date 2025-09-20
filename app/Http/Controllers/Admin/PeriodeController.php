<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Periode;
use Inertia\Inertia;

class PeriodeController extends Controller
{
    public function index()
    {
        $periode_laporan = Periode::select('id', 'periode', 'bulan_mulai', 'tahun_mulai', 'bulan_selesai', 'tahun_selesai')
            ->withCount('laporanBeasiswa')
            ->get()
            ->groupBy('periode')
            ->map(function ($group) {
                return [
                    'id' => $group->first()->id,
                    'periode' => $group->first()->periode,
                    'bulan_mulai' => $group->first()->bulan_mulai,
                    'tahun_mulai' => $group->first()->tahun_mulai,
                    'bulan_selesai' => $group->first()->bulan_selesai,
                    'tahun_selesai' => $group->first()->tahun_selesai,
                    'jumlah_laporan' => $group->sum('laporan_beasiswa_count'),
                ];
            })
            ->values();


        return Inertia::render('Admin/Periode/Index', [
            'data' => $periode_laporan,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'periode' => 'required|string|max:255',
        ]);

        Periode::create($request->all());

        return to_route('admin.periode.index')->with('success', 'Data berhasil ditambahkan.');
    }
    
    public function update(Request $request, Periode $periode)
    {
        $request->validate([
            'periode' => 'required|string|max:255',
        ]);

        $periode->update($request->all());

        return to_route('admin.periode.index')->with('success', 'Data berhasil diperbarui.');
    }


    public function destroy(Periode $periode)
    {
        $periode->delete();
        return to_route('admin.periode.index')->with('success', 'Data berhasil dihapus.');
    }
}
