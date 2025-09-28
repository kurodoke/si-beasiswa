<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application; 
use Inertia\Inertia;
use App\Http\Controllers\Admin\BeasiswaController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\LaporanBeasiswaController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\Admin\PeriodeController;
use App\Http\Controllers\Admin\BeritaController;

Route::middleware(['auth', 'verified'])->group(function () {    
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
});

Route::middleware(['auth', 'validator'])->prefix('admin')->name('admin.')->group(function () {

    // Laporan Beasiswa
    Route::get('laporan/verified', [LaporanBeasiswaController::class, 'indexVerified'])->name('laporanbeasiswa.verified');
    Route::get('laporan/unverified', [LaporanBeasiswaController::class, 'indexUnverified'])->name('laporanbeasiswa.unverified');
    
    // Dokumen
    Route::get('/dokumen/download/{filename}', [DokumenController::class, 'download'])
        ->where('filename', '.*')
        ->name('dokumen.download');
    
    Route::post('/dokumen/upload/{id}', [DokumenController::class, 'upload'])
        ->where('id', '[0-9]+')
        ->name('dokumen.upload');


    Route::resource('laporan', LaporanBeasiswaController::class)->only(['index', 'update', 'destroy'])->names([
        'index' => 'laporanbeasiswa.index',
        'update' => 'laporanbeasiswa.update',
        'destroy' => 'laporanbeasiswa.destroy',
    ]);

    Route::middleware('admin')->group(function () {
        Route::resource('beasiswa', BeasiswaController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('periode', PeriodeController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('users', UserManagementController::class)->only(['index', 'store', 'update', 'destroy']);

        Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
        Route::get('/berita/create', [BeritaController::class, 'create'])->name('berita.create');
        Route::post('/berita/create', [BeritaController::class, 'store'])->name('berita.store');
        Route::get('/berita/{berita}', [BeritaController::class, 'show'])->name('berita.show');
        Route::post('/berita/{berita}', [BeritaController::class, 'update'])->name('berita.update');
        Route::delete('/berita/{berita}', [BeritaController::class, 'destroy'])->name('berita.destroy');
    });
});

Route::get('/pendaftaran-beasiswa/periode-{id}-{tahun_mulai}', [LaporanBeasiswaController::class, 'create'])->name('laporan.create');
Route::post('/pendaftaran-beasiswa/periode-{id}-{tahun_mulai}', [LaporanBeasiswaController::class, 'store'])->name('laporan.store');
Route::get('/', [PublicController::class, 'index'])->name('home');


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
