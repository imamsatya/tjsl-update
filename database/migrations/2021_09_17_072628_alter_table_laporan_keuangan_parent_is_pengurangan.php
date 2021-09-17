<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableLaporanKeuanganParentIsPengurangan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('laporan_keuangan_parent', function (Blueprint $table) {
            $table->boolean('is_pengurangan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('laporan_keuangan_parent', function (Blueprint $table) {
            $table->dropColumn('is_pengurangan');
        });
    }
}
