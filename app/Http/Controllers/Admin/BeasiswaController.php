<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Beasiswa;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BeasiswaController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Beasiswa/Index', [
            'beasiswas' => Beasiswa::all(),
        ]);
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

    public function store(Request $request)
    {
        $request->validate([
            'nama_beasiswa' => 'required|string|max:255',
            'penyelenggara' => 'required|string|max:255',
        ]);

        Beasiswa::create($request->all());

        return redirect()->route('admin.beasiswa.index');
    }
    
    public function update(Request $request, Beasiswa $beasiswa)
    {
        $request->validate([
            'nama_beasiswa' => 'required|string|max:255',
            'penyelenggara' => 'required|string|max:255',
        ]);

        $beasiswa->update($request->all());

        return redirect()->route('admin.beasiswa.index');
    }


    public function destroy(Beasiswa $beasiswa)
    {
        $beasiswa->delete();
        return redirect()->route('admin.beasiswa.index');
    }
}