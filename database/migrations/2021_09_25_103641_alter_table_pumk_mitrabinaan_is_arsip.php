<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablePumkMitrabinaanIsArsip extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pumk_mitra_binaans', function (Blueprint $table) {
            $table->boolean('is_arsip')->default(false);
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
            $table->dropColumn('is_arsip')->default(false);
        });
    }
}
