<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelasiTpbKodeIndikator extends Model
{
    protected $guarded = [];

    public function kode_indikator()
    {
    	return $this->belongsTo('App\Models\KodeIndikator', 'kode_indikator_id');
    }
}
