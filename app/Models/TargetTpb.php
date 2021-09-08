<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TargetTpb extends Model
{
    protected $guarded = [];

    public function tpb()
    {
        return $this->belongsTo('App\Models\Tpb', 'tpb_id');
    }
}
