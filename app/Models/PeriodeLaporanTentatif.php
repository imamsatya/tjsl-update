<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeLaporanTentatif extends Model
{
    use HasFactory;
    protected $table = 'periode_laporan_tentatifs';
    public function has_jenis()
    {
        return $this->hasMany('App\Models\PeriodeTentatifHasJenis', 'periode_laporan_id');
    }
}
