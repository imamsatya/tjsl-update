<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodeHasJenis extends Model
{
    protected $table = 'periode_has_jenis';
    
    protected $fillable = [
        'jenis_laporan_id', 'periode_laporan_id'
    ];

    public function jenis()
    {
        return $this->belongsTo('App\Models\JenisLaporan', 'jenis_laporan_id');
    }
}
