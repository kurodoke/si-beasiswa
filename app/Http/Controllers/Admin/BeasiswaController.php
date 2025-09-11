<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Beasiswa;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\LaporanPenerima;

class BeasiswaController extends Controller
{
    public function index()
    {
        $beasiswa_laporan = Beasiswa::select('id', 'jenis_beasiswa')
            ->withCount('laporanPenerimas')
            ->get()
            ->groupBy('jenis_beasiswa')
            ->map(function ($group) {
                return [
                    'id' => $group->first()->id,
                    'jenis_beasiswa' => $group->first()->jenis_beasiswa,
                    'jumlah_beasiswa' => $group->sum('laporan_penerimas_count'),
                ];
            })
            ->values();

        return Inertia::render('Admin/Beasiswa/Index', [
            'data' => $beasiswa_laporan,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_beasiswa' => 'required|string|max:255',
        ]);

        Beasiswa::create($request->all());

        return to_route('admin.beasiswa.index')->with('success', 'Data berhasil ditambahkan.');
    }
    
    public function update(Request $request, Beasiswa $beasiswa)
    {
        $request->validate([
            'jenis_beasiswa' => 'required|string|max:255',
        ]);

        $beasiswa->update($request->all());

        return to_route('admin.beasiswa.index')->with('success', 'Data berhasil diperbarui.');
    }


    public function destroy(Beasiswa $beasiswa)
    {
        $beasiswa->delete();
        return to_route('admin.beasiswa.index')->with('success', 'Data berhasil dihapus.');
    }

    
    public function indexVerified()
    {
        return Inertia::render('Admin/Beasiswa/IndexVerified', [
            'beasiswas' => Beasiswa::all(),
        ]);
    }

    public function indexUnverified()
    {
        return Inertia::render('Admin/Beasiswa/IndexUnverified', [
            'beasiswas' => Beasiswa::all(),
        ]);
    }
}