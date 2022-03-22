<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableInvalidKegiatan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->boolean('is_invalid_aplikasitjsl')->default(false);
        });

        Schema::table('kegiatan_realisasis', function (Blueprint $table) {
            $table->boolean('is_invalid_aplikasitjsl')->default(false);
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
            $table->dropColumn('is_invalid_aplikasitjsl')->default(false);
        });

        Schema::table('kegiatan_realisasis', function (Blueprint $table) {
            $table->dropColumn('is_invalid_aplikasitjsl')->default(false);
        });
    }
}
