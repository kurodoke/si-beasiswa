<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Beasiswa;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\LaporanBeasiswa;

class BeasiswaController extends Controller
{
    public function index()
    {
        $beasiswa_laporan = Beasiswa::select('id', 'nama_beasiswa', 'jenis_beasiswa')
            ->withCount('laporanBeasiswa')
            ->get()
            ->groupBy('nama_beasiswa')
            ->map(function ($group) {
                return [
                    'id' => $group->first()->id,
                    'nama_beasiswa' => $group->first()->nama_beasiswa,
                    'jenis_beasiswa' => $group->first()->jenis_beasiswa,
                    'jumlah_laporan' => $group->sum('laporan_beasiswa_count'),
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
            'nama_beasiswa' => 'required|string|max:255',
            'jenis_beasiswa' => 'required|string|max:255',
        ]);

        Beasiswa::create($request->all());

        return to_route('admin.beasiswa.index')->with('success', 'Data berhasil ditambahkan.');
    }
    
    public function update(Request $request, Beasiswa $beasiswa)
    {
        $request->validate([
            'nama_beasiswa' => 'required|string|max:255',
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
}