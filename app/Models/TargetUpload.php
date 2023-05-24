<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TargetUpload extends Model
{
    protected $guarded = [];

    public function perusahaan()
    {
        return $this->belongsTo('App\Models\Perusahaan', 'perusahaan_id');
    }
}
