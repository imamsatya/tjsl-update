<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeTentatifHasJenis extends Model
{
    use HasFactory;
    protected $table = 'periode_tentatif_has_jenis';

    public function jenis()
    {
        return $this->belongsTo('App\Models\JenisLaporan', 'jenis_laporan_id');
    }
}
