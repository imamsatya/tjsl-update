<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVersiLaporanKeuanganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('versi_laporan_keuangan', function (Blueprint $table) {
            $table->id();
            $table->string('versi', 255)->nullable();
            $table->dateTime('tanggal_awal')->nullable();
            $table->dateTime('tanggal_akhir')->nullable();
            $table->boolean('status')->nullable();
            $table->string('keterangan', 255)->nullable();
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
        Schema::dropIfExists('versi_laporan_keuangan');
    }
}
