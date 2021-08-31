<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodeLaporan extends Model
{
    protected $guarded = [];

    public function has_jenis()
    {
        return $this->hasMany('App\Models\PeriodeHasJenis', 'periode_laporan_id');
    }
}
