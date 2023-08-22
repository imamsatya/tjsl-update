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
        Schema::table('enable_input_by_superadmin', function (Blueprint $table) {
            $table->datetime('tanggal_awal')->nullable();
            $table->datetime('tanggal_akhir')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('enable_input_by_superadmin', function (Blueprint $table) {
            $table->dropColumn('tanggal_awal');
            $table->dropColumn('tanggal_akhir');
        });
    }
};
