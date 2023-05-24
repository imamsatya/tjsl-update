<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PumkMitraBinaanChangeTipeData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pumk_mitra_binaans', function (Blueprint $table) {
            $table->text('bank_account_id')->change();
        });

        Schema::table('pumk_mitra_binaan_upload_gagals', function (Blueprint $table) {
            $table->text('bank_account_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
