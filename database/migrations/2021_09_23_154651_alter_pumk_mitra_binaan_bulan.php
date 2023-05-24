<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPumkMitraBinaanBulan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pumk_mitra_binaans', function (Blueprint $table) {
            $table->integer('bulan')->nullable()->after('id');
            $table->integer('tahun')->nullable()->after('bulan');
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
            $table->dropColumn('bulan')->nullable()->after('id');
            $table->dropColumn('tahun')->nullable()->after('bulan');
        });
    }
}
