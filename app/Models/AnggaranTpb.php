<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnggaranTpb extends Model
{
    protected $guarded = [];
    protected $table = 'anggaran_tpbs';
    public function perusahaan()
    {
        return $this->belongsTo('App\Models\Perusahaan', 'perusahaan_id');
    }

    public function relasi()
    {
        return $this->belongsTo('App\Models\RelasiPilarTpb', 'relasi_pilar_tpb_id');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\Status', 'status_id');
    }

    public function targettpb()
    {
        return $this->belongsTo('App\Models\TargetTpb', 'anggaran_tpb_id');
    }
}
