<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableIdOwnerProgram extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('target_tpbs', function (Blueprint $table) {
            $table->integer('id_owner')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('target_tpbs', function (Blueprint $table) {
            $table->dropColumn('id_owner')->nullable();
        });
    }
}
