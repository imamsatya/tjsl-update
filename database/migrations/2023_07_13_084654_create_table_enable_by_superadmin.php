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
        Schema::create('enable_input_by_superadmin', function (Blueprint $table) {
            $table->id();
            $table->integer('referensi_id')->nullable();
            $table->integer('perusahaan_id')->nullable();
            $table->integer('tahun')->nullable();
            $table->string('status')->nullable();
            $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('enable_input_by_superadmin');
    }
};
