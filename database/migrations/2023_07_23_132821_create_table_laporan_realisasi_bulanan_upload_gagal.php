<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laporan_realisasi_bulanan_upload_gagals', function (Blueprint $table) {
            $table->id();
            $table->string('realisasi_upload_id')->nullable();
            $table->string('jenis_anggaran')->nullable();
            $table->string('id_program')->nullable();
            $table->string('nama_kegiatan')->nullable();
            $table->string('id_jenis_kegiatan')->nullable();
            $table->string('id_sub_kegiatan')->nullable();
            $table->string('id_provinsi')->nullable();
            $table->string('id_kabupaten')->nullable();
            $table->string('realisasi_anggaran')->nullable();
            $table->string('id_satuan_ukur')->nullable();
            $table->string('realisasi_indikator')->nullable();
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
        Schema::dropIfExists('laporan_realisasi_bulanan_upload_gagals');
    }
};
