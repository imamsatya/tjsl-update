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
        Schema::table('pilar_pembangunans', function (Blueprint $table) {
            //
            $table->string('jenis_anggaran')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pilar_pembangunans', function (Blueprint $table) {
            //
            $table->dropColumn('jenis_anggaran');
        });
    }
};