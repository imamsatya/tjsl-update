<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePumkAnggarans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pumk_anggarans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tahun')->nullable();
            $table->integer('bumn_id')->nullable();
            $table->integer('periode_id')->nullable();
            $table->integer('status_id')->nullable();
            $table->biginteger('saldo_awal')->nullable();
            $table->biginteger('income_mitra_binaan')->nullable();
            $table->biginteger('income_bumn_pembina_lain')->nullable();
            $table->biginteger('income_jasa_adm_pumk')->nullable();
            $table->biginteger('income_adm_bank')->nullable();
            $table->biginteger('income_total')->nullable();
            $table->biginteger('outcome_mandiri')->nullable();
            $table->biginteger('outcome_kolaborasi_bumn')->nullable();
            $table->biginteger('outcome_bumn_khusus')->nullable();
            $table->biginteger('outcome_total')->nullable();
            $table->biginteger('saldo_akhir')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('pumk_anggarans');
    }
}
