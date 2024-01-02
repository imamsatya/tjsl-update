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
        Schema::create('log_enable_disable_input_datas', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['enable', 'disable'])->default('disable');
            $table->integer('perusahaan_id');
            $table->integer('tahun');
            $table->integer('referensi_id');
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
        Schema::dropIfExists('log_enable_disable_input_datas');
    }
};
