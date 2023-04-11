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
        Schema::table('periode_laporans', function (Blueprint $table) {
            //
            $table->string('jenis_periode')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('periode_laporans', function (Blueprint $table) {
            //
            $table->dropColumn('jenis_periode');
        });
    }
};
