<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasMany;

class Beasiswa extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function laporanBeasiswa(): hasMany
    {
        return $this->hasMany(LaporanBeasiswa::class);
    }
}