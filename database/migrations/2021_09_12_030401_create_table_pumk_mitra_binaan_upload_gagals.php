<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePumkMitraBinaanUploadGagals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pumk_mitra_binaan_upload_gagals', function (Blueprint $table) {
            $table->increments('id');
            $table->text('nama_mitra')->nullable();
            $table->text('no_identitas')->nullable();
            $table->integer('provinsi_id')->nullable();
            $table->integer('kota_id')->nullable();
            $table->integer('sektor_usaha_id')->nullable();
            $table->integer('cara_penyaluran_id')->nullable();
            $table->integer('kolektibilitas_id')->nullable();
            $table->integer('kondisi_pinjaman_id')->nullable();
            $table->integer('jenis_pembayaran_id')->nullable();
            $table->integer('bank_account_id')->nullable();
            $table->bigInteger('nilai_aset')->nullable();
            $table->bigInteger('nilai_omset')->nullable();
            $table->text('no_pinjaman')->nullable();
            $table->text('sumber_dana')->nullable();
            $table->string('tgl_awal')->nullable();
            $table->string('tgl_jatuh_tempo')->nullable();
            $table->bigInteger('nominal_pendanaan')->nullable();
            $table->bigInteger('saldo_pokok_pendanaan')->nullable();
            $table->bigInteger('saldo_jasa_adm_pendanaan')->nullable();
            $table->bigInteger('penerimaan_pokok_bulan_berjalan')->nullable();
            $table->bigInteger('penerimaan_jasa_adm_bulan_berjalan')->nullable();
            $table->string('tgl_penerimaan_terakhir')->nullable();
            $table->integer('jumlah_sdm')->nullable();
            $table->bigInteger('kelebihan_angsuran')->nullable();
            $table->text('subsektor')->nullable();
            $table->text('hasil_produk_jasa')->nullable();
            $table->integer('created_by_id')->nullable();
            $table->integer('updated_by_id')->nullable();
            $table->integer('perusahaan_id')->nullable();
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
        Schema::dropIfExists('pumk_mitra_binaan_upload_gagals');
    }
}
