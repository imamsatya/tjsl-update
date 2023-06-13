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
        Schema::create('pumk_bulans', function (Blueprint $table) {
            $table->id();
            $table->integer('perusahaan_id')->nullable();
            $table->integer('status_id')->nullable();
            $table->string('tahun')->nullable();
            $table->integer('bulan_id')->nullable();
            $table->integer('nilai_penyaluran')->nullable();
            $table->integer('nilai_penyaluran_melalui_bri')->nullable();
            $table->integer('jumlah_mb')->nullable();
            $table->integer('jumlah_mb_naik_kelas')->nullable();
            $table->integer('kolektabilitas_lancar')->nullable();
            $table->integer('kolektabilitas_lancar_jumlah_mb')->nullable();
            $table->integer('kolektabilitas_kurang_lancar')->nullable();
            $table->integer('kolektabilitas_kurang_lancar_jumlah_mb')->nullable();
            $table->integer('kolektabilitas_diragukan')->nullable();
            $table->integer('kolektabilitas_diragukan_jumlah_mb')->nullable();
            $table->integer('kolektabilitas_macet')->nullable();
            $table->integer('kolektabilitas_macet_jumlah_mb')->nullable();
            $table->integer('kolektabilitas_pinjaman_bermasalah')->nullable();
            $table->integer('kolektabilitas_pinjaman_bermasalah_jumlah_mb')->nullable();
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
        Schema::dropIfExists('pumk_bulans');
    }
};
