<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tpb extends Model
{
    protected $guarded = [];

    public function pilar()
    {
        return $this->belongsTo('App\Models\PilarPembangunan', 'pilar_pembangunan_id');
    }
}
