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
        Schema::create('laporan_realisasi_bulanan_uploads', function (Blueprint $table) {
            $table->id();
            $table->string('file_name', 255)->nullable();
            $table->integer('bulan')->nullable();
            $table->integer('perusahaan_id')->nullable();
            $table->integer('berhasil')->nullable();
            $table->integer('tahun')->nullable();
            $table->integer('gagal')->nullable();
            $table->integer('user_id')->nullable();
            $table->text('keterangan')->nullable();            
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
        Schema::dropIfExists('laporan_realisasi_bulanan_uploads');
    }
};
