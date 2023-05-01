<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PumkAnggaran extends Model
{
    protected $guarded = [];
    protected $fillable = [
        'tahun',
        'bumn_id',
        'periode_id',
        'status_id',
        'saldo_awal',
        'income_mitra_binaan',
        'income_bumn_pembina_lain',
        'income_jasa_adm_pumk',
        'income_adm_bank',
        'income_biaya_lainnya',
        'income_total',
        'outcome_mandiri',
        'outcome_kolaborasi_bumn',
        'outcome_bumn_khusus',
        'outcome_bri',
        'outcome_total',
        'saldo_akhir',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
}
