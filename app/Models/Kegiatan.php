<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $guarded = [];

    public function target_tpb()
    {
    	return $this->belongsTo('App\Models\TargetTpb', 'target_tpb_id');
    }

    public function provinsi()
    {
    	return $this->belongsTo('App\Models\Provinsi', 'provinsi_id');
    }

    public function kota()
    {
    	return $this->belongsTo('App\Models\Kota', 'kota_id');
    }
    
    public function satuan_ukur()
    {
    	return $this->belongsTo('App\Models\SatuanUkur', 'satuan_ukur_id');
    }

    public function bulan_nama()
    {
    	return $this->belongsTo('App\Models\Bulan', 'bulan');
    }

    public function kegiatan_realisasi()
    {
    	return $this->belongsTo('App\Models\KegiatanRealisasi', 'kegiatan_id');
    }
}
