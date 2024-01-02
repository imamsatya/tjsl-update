<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogPumkBulan extends Model
{
    use HasFactory;
    protected $table = 'log_pumk_bulans';
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
