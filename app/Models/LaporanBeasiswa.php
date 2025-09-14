<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanBeasiswa extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    protected $casts = [
    'penerimaan_beasiswa' => 'date',
    'selesai_beasiswa' => 'date',
    'verified_at' => 'datetime',
    ];


    public function beasiswa()
    {
        return $this->belongsTo(Beasiswa::class);
    }

    public function dokumenBukti()
    {
        return $this->hasOne(DokumenBukti::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class);
    }

    public function getLabelPenerimaanAttribute(): string
    {
        return $this->penerimaan_beasiswa?->translatedFormat('F Y') ?? '-';
    }

    public function getLabelSelesaiAttribute(): string
    {
        return $this->selesai_beasiswa?->translatedFormat('F Y') ?? '-';
    }

    public function scopeAktifDalamPeriode($query, $periode)
    {
        $start = \Carbon\Carbon::create($periode->tahun_mulai, $periode->bulan_mulai, 1);
        $end = \Carbon\Carbon::create($periode->tahun_selesai, $periode->bulan_selesai, 1)->endOfMonth();

        return $query->whereDate('penerimaan_beasiswa', '<=', $end)
                     ->whereDate('selesai_beasiswa', '>=', $start);
    }

}