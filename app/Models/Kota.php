<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    protected $guarded = [];

    public function provinsi()
    {
    	return $this->belongsTo('App\Models\Provinsi', 'provinsi_id');
    }
}
