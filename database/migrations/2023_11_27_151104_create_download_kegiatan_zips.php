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
        Schema::create('download_kegiatan_zips', function (Blueprint $table) {
            $table->id();
            $table->string('description')->nullable();
            $table->string('file_path')->nullable();      
            $table->string('status')->nullable();
            $table->string('filter')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('created_at')->nullable();
            $table->string('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('download_kegiatan_zips');
    }
};
