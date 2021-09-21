<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelasiTpbKodeTujuanTpb extends Model
{
    protected $guarded = [];

    public function kode_tujuan_tpb()
    {
    	return $this->belongsTo('App\Models\KodeTujuanTpb', 'kode_tujuan_tpb_id');
    }
}
