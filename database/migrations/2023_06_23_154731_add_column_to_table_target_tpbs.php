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
        Schema::table('target_tpbs', function (Blueprint $table) {
            $table->boolean('is_enable_input_by_superadmin')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('anggaran_tpbs', function (Blueprint $table) {
            $table->dropColumn('is_enable_input_by_superadmin');
        });
    }
};
