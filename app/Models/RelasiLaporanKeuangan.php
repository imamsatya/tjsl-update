<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelasiLaporanKeuangan extends Model
{
    protected $guarded = [];

    protected $table = 'relasi_laporan_keuangan';

    public function laporankeuangan()
    {
        return $this->belongsTo('App\Models\LaporanKeuangan', 'laporan_keuangan_id');
    }
}