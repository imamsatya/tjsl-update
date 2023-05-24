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
        Schema::create('laporan_tble', function (Blueprint $table) {
            $table->id();
            $table->integer('perusahaan_id')->nullable();
            $table->integer('periode_laporan_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('status_id')->nullable();
            $table->string('tahun')->nullable();
            $table->string('file_name')->nullable();
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
        Schema::dropIfExists('laporan_tble');
    }
};
