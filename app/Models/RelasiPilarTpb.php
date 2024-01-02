<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelasiPilarTpb extends Model
{
    protected $guarded = [];

    public function tpb()
    {
        return $this->belongsTo('App\Models\Tpb', 'tpb_id');
    }

    public function pilar()
    {
        return $this->belongsTo('App\Models\PilarPembangunan', 'pilar_pembangunan_id');
    }

    public function indikator()
    {
        return $this->belongsToMany('App\Models\KodeIndikator', 'App\Models\RelasiTpbKodeIndikator', 'relasi_pilar_tpb_id', 'kode_indikator_id');
    }

    public function tujuan_tpb()
    {
        return $this->belongsToMany('App\Models\KodeTujuanTpb', 'App\Models\RelasiTpbKodeTujuanTpb', 'relasi_pilar_tpb_id', 'kode_tujuan_tpb_id');
    }
}
