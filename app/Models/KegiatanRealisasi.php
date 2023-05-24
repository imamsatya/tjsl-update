<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KegiatanRealisasi extends Model
{
    protected $guarded = [];

    public function status()
    {
    	return $this->belongsTo('App\Models\Status', 'status_id');
    }
    
    public function kegiatan()
    {
    	return $this->belongsTo('App\Models\Kegiatan', 'kegiatan_id');
    }
}
