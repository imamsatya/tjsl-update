<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePumkUploadMitraBinaans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pumk_upload_mitra_binaans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file_name')->nullable();
            $table->string('tahun')->nullable();
            $table->integer('perusahaan_id')->nullable();
            $table->integer('berhasil')->nullable();
            $table->integer('update')->nullable();
            $table->integer('gagal')->nullable();
            $table->text('keterangan')->nullable();
            $table->integer('upload_by_id')->nullable();
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
        Schema::dropIfExists('pumk_upload_mitra_binaans');
    }
}
