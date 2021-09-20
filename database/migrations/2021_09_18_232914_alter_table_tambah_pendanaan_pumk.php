<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableTambahPendanaanPumk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pumk_mitra_binaans', function (Blueprint $table) {
            $table->integer('id_tambahan_pendanaan')->nullable();
        });

        Schema::table('pumk_mitra_binaan_upload_gagals', function (Blueprint $table) {
            $table->integer('id_tambahan_pendanaan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pumk_mitra_binaans', function (Blueprint $table) {
            $table->dropColumn('id_tambahan_pendanaan')->nullable();
        });

        Schema::table('pumk_mitra_binaan_upload_gagals', function (Blueprint $table) {
            $table->dropColumn('id_tambahan_pendanaan')->nullable();
        });
    }
}
