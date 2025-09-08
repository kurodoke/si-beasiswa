<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Beasiswa;
use App\Models\LaporanPenerima;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PenerimaController extends Controller
{
    public function create()
    {
        return Inertia::render('Admin/Penerima/Create', [
            'beasiswas' => Beasiswa::orderBy('nama_beasiswa')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_mahasiswa' => 'required|string|max:255',
            'npm' => 'required|string|max:255|unique:laporan_penerimas,npm',
            'program_studi_id' => 'required|exists:program_studis,id',
            'beasiswa_id' => 'required|exists:beasiswas,id',
            'tahun_penerimaan' => 'required|digits:4',
        ]);

        LaporanPenerima::create([
            'nama_mahasiswa' => $request->nama_mahasiswa,
            'npm' => $request->npm,
            'program_studi_id' => $request->program_studi_id,
            'beasiswa_id' => $request->beasiswa_id,
            'tahun_penerimaan' => $request->tahun_penerimaan,
            'user_id' => null, // Karena diinput admin
            'status_validasi' => 'disetujui', // Langsung disetujui
        ]);

        return to_route('admin.validasi.index')->with('success', 'Data penerima berhasil ditambahkan.');
    }
}