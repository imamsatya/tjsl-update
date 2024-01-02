<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class LogTargetTpb extends Model
{
    protected $guarded = [];

    public function status()
    {
        return $this->belongsTo('App\Models\Status', 'status_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
