<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownloadExport extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'download_exports';
}
