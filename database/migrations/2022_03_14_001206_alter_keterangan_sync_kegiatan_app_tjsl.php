<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterKeteranganSyncKegiatanAppTjsl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kegiatan_app_tjsl', function (Blueprint $table) {
            $table->text('status_id_program_diportal')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kegiatan_app_tjsl', function (Blueprint $table) {
            $table->dropColumn('status_id_program_diportal')->nullable();
        });
    }
}
