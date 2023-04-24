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
        Schema::table('pumk_anggarans', function (Blueprint $table) {
            //
            $table->biginteger('income_biaya_lainnya')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pumk_anggarans', function (Blueprint $table) {
            //
            $table->dropColumn('income_biaya_lainnya');
        });
    }
};
