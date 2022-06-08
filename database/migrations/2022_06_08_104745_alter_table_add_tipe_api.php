<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableAddTipeApi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('log_sinkronisasi_kegiatan', function (Blueprint $table) {
            $table->text('tipe_api')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('log_sinkronisasi_kegiatan', function (Blueprint $table) {
            $table->dropColumn('tipe_api')->nullable();
        });
    }
}
