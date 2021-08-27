<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnggaranTpb extends Model
{
    protected $guarded = [];

    public function perusahaan()
    {
        return $this->belongsTo('App\Models\Perusahaan', 'perusahaan_id');
    }

    public function tpb()
    {
        return $this->belongsTo('App\Models\Tpb', 'tpb_id');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\Status', 'status_id');
    }
}
