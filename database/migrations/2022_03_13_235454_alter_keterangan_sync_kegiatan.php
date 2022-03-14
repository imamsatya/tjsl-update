<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterKeteranganSyncKegiatan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->text('status_id_program_aplikasitjsl')->nullable();
        });

        Schema::table('kegiatan_realisasis', function (Blueprint $table) {
            $table->text('status_id_program_aplikasitjsl')->nullable();
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
            $table->dropColumn('status_id_program_aplikasitjsl')->nullable();
        });

        Schema::table('kegiatan_realisasis', function (Blueprint $table) {
            $table->dropColumn('status_id_program_aplikasitjsl')->nullable();
        });
    }
}
