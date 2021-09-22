<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKeuanganNilai extends Model
{
    protected $guarded = [];

    public function perusahaan()
    {
        return $this->belongsTo('App\Models\Perusahaan', 'perusahaan_id');
    }

    public function periode()
    {
        return $this->belongsTo('App\Models\PeriodeLaporan', 'periode_laporan_id');
    }

    public function laporan_keuangan()
    {
        return $this->belongsTo('App\Models\LaporanKeuangan', 'laporan_keuangan_id');
    }

    public function relasi()
    {
        return $this->belongsTo('App\Models\relasiLaporanKeuangan', 'relasi_laporan_keuangan_id');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\Status', 'status_id');
    }
}
