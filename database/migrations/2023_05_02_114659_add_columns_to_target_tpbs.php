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
            //
            $table->boolean('kriteria_program_prioritas')->nullable()->default(false);
            $table->boolean('kriteria_program_csv')->nullable()->default(false);
            $table->boolean('kriteria_program_umum')->nullable()->default(false);
            $table->string('pelaksanaan_program')->nullable();
            $table->integer('mitra_bumn_id')->nullable();
            $table->boolean('multi_years')->nullable()->default(false);
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
            //
        });
    }
};
