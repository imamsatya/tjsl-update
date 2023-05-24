<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterKegiatanSyncIdBumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->integer('id_bumn_aplikasitjsl')->nullable();
        });

        Schema::table('kegiatan_realisasis', function (Blueprint $table) {
            $table->integer('id_bumn_aplikasitjsl')->nullable();
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
            $table->dropColumn('id_bumn_aplikasitjsl')->nullable();
        });

        Schema::table('kegiatan_realisasis', function (Blueprint $table) {
            $table->dropColumn('id_bumn_aplikasitjsl')->nullable();
        });
    }
}
