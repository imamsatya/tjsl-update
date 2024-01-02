<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterKegiatanIdFromApptjsl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->integer('id_kegiatan_aplikasitjsl')->nullable();
            $table->text('tgl_sinkronisasi_api')->nullable();
            $table->text('sumber_data')->nullable();
        });

        Schema::table('kegiatan_realisasis', function (Blueprint $table) {
            $table->integer('id_kegiatan_aplikasitjsl')->nullable();
            $table->text('tgl_sinkronisasi_api')->nullable();
            $table->text('sumber_data')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->dropColumn('id_kegiatan_aplikasitjsl')->nullable();
            $table->dropColumn('tgl_sinkronisasi_api')->nullable();
            $table->dropColumn('sumber_data')->nullable();
        });

        Schema::table('kegiatan_realisasis', function (Blueprint $table) {
            $table->dropColumn('id_kegiatan_aplikasitjsl')->nullable();
            $table->dropColumn('tgl_sinkronisasi_api')->nullable();
            $table->dropColumn('sumber_data')->nullable();
        });

    }
}
