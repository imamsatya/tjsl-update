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
        Schema::create('periode_laporan_tentatifs', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->integer('jenis_laporan_id');
            $table->date('tanggal_awal');
            $table->date('tanggal_akhir');
            $table->string('keterangan')->nullable();
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
        Schema::dropIfExists('periode_laporan_tentatifs');
    }
};
