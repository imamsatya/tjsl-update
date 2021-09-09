<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogPumkAnggaran extends Model
{
    protected $table = 'log_pumk_anggarans';
    protected $fillable = [
        'pumk_anggaran_id', 
        'status_id',
        'nilai_rka',
        'created_by_id',
        'created_at'
    ];
}
