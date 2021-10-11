<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterChangeTextUploadMbGagal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pumk_mitra_binaan_upload_gagals', function (Blueprint $table) {
            $table->text('provinsi_id')->change()->nullable();
            $table->text('kota_id')->change()->nullable();
            $table->text('sektor_usaha_id')->change()->nullable();
            $table->text('cara_penyaluran_id')->change()->nullable();
            $table->text('skala_usaha_id')->change()->nullable();
            $table->text('kolektibilitas_id')->change()->nullable();
            $table->text('kondisi_pinjaman_id')->change()->nullable();
            $table->text('jenis_pembayaran_id')->change()->nullable();
            $table->text('bank_account_id')->change()->nullable();
            $table->text('nilai_aset')->change()->nullable();
            $table->text('nilai_omset')->change()->nullable();
            $table->text('tgl_awal')->change()->nullable();
            $table->text('tgl_jatuh_tempo')->change()->nullable();
            $table->text('nominal_pendanaan')->change()->nullable();
            $table->text('saldo_pokok_pendanaan')->change()->nullable();
            $table->text('saldo_jasa_adm_pendanaan')->change()->nullable();
            $table->text('penerimaan_pokok_bulan_berjalan')->change()->nullable();
            $table->text('penerimaan_jasa_adm_bulan_berjalan')->change()->nullable();
            $table->text('tgl_penerimaan_terakhir')->change()->nullable();
            $table->text('kelebihan_angsuran')->change()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
