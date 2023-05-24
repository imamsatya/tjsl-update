<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableKegiatanAppTjsl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kegiatan_app_tjsl', function (Blueprint $table) {
            $table->increments('id');
            $table->text('id_kegiatan')->nullable();
            $table->text('id_bumn')->nullable();
            $table->text('id_program')->nullable();
            $table->text('sektor')->nullable();
            $table->text('kegiatan')->nullable();
            $table->text('id_provinsi_portal')->nullable();
            $table->text('id_kab_kota_portal')->nullable();
            $table->text('id_provinsi_origin')->nullable();
            $table->text('id_kab_kota_origin')->nullable();
            $table->text('kecamatan')->nullable();
            $table->text('id_kelurahan_desa')->nullable();
            $table->text('id_pilar_portal')->nullable();
            $table->text('id_tpb_portal')->nullable();
            $table->text('id_indikator_portal')->nullable();
            $table->text('id_pilar_origin')->nullable();
            $table->text('id_tpb_origin')->nullable();
            $table->text('id_indikator_origin')->nullable();
            $table->text('map_marker')->nullable();
            $table->text('pemohon')->nullable();
            $table->text('alamat')->nullable();
            $table->text('anggaran_permintaan')->nullable();
            $table->text('realisasi_total')->nullable();
            $table->text('indikator_capaian_kegiatan')->nullable();
            $table->text('id_satuan_ukur')->nullable();
            $table->text('bulan')->nullable();
            $table->text('tahun')->nullable();
            $table->text('alokasi_anggaran_tahun')->nullable();
            $table->text('realisasi_anggaran_bulan')->nullable();
            $table->text('target_bulan')->nullable();
            $table->text('realisasi_bulan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kegiatan_app_tjsl');
    }
}
