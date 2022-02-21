<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanByAppTjsl extends Model
{
    protected $table = 'kegiatan_app_tjsl';
    protected $fillable = [
        "id_kegiatan",
        "id_bumn",
        "id_program",
        "sektor",
        "kegiatan",
        "id_provinsi_portal",
        "id_kab_kota_portal",
        "id_provinsi_origin",
        "id_kab_kota_origin",
        "kecamatan",
        "id_kelurahan_desa",
        "id_pilar_portal",
        "id_tpb_portal",
        "id_indikator_portal",
        "id_pilar_origin",
        "id_tpb_origin",
        "id_indikator_origin",
        "map_marker",
        "pemohon",
        "alamat",
        "anggaran_permintaan",
        "realisasi_total",
        "indikator_capaian_kegiatan",
        "id_satuan_ukur",
        "bulan",
        "tahun",
        "alokasi_anggaran_tahun",
        "realisasi_anggaran_bulan",
        "target_bulan",
        "realisasi_bulan"
    ];
}
