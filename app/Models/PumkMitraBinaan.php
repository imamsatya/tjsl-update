<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PumkMitraBinaan extends Model
{
    protected $guarded = [];
    protected $table = 'pumk_mitra_binaans';
    protected $fillable = [
        'perusahaan_id','kode_upload',
        'nama_mitra', 'no_identitas', 'provinsi_id', 'kota_id', 
        'sektor_usaha_id', 'skala_usaha_id', 'cara_penyaluran_id', 
        'kolektibilitas_id', 'kondisi_pinjaman_id', 
        'jenis_pembayaran_id', 'bank_account_id', 
        'nilai_aset', 'nilai_omset', 'no_pinjaman', 
        'sumber_dana', 'tgl_awal', 'tgl_jatuh_tempo', 
        'nominal_pendanaan', 'saldo_pokok_pendanaan', 
        'saldo_jasa_adm_pendanaan', 'penerimaan_pokok_bulan_berjalan', 
        'penerimaan_jasa_adm_bulan_berjalan', 'tgl_penerimaan_terakhir', 
        'jumlah_sdm', 'kelebihan_angsuran', 'subsektor', 
        'hasil_produk_jasa', 'created_by_id', 
        'updated_by_id', 'created_at', 'updated_at'
    ];
}
