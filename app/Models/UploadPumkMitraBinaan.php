<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadPumkMitraBinaan extends Model
{
    protected $guard = [];
    protected $table = 'pumk_upload_mitra_binaans';
    protected $fillable = [
        'file_name', 'tahun', 'perusahaan_id', 'berhasil','update', 'gagal', 'keterangan', 'upload_by_id', 'created_at', 'updated_at'
    ];
}
