<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownloadKegiatanZip extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'download_kegiatan_zips';
}
