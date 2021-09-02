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
}
