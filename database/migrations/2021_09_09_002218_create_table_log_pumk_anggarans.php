<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableLogPumkAnggarans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_pumk_anggarans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pumk_anggaran_id')->nullable();
            $table->integer('status_id')->nullable();
            $table->integer('created_by_id')->nullable();
            $table->integer('updated_by_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_pumk_anggarans');
    }
}
