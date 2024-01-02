<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username');
            $table->string('email');
            $table->string('name');
            $table->string('handphone')->nullable();

            $table->tinyInteger('kategori_user_id');
            $table->string('kategori_user');
            $table->string('source');
            $table->integer('id_bumn')->nullable();
            $table->string('id_angka')->nullable();
            $table->string('id_huruf')->nullable();
            $table->string('bumn_lengkap')->nullable();
            $table->string('bumn_singkat')->nullable();
            $table->string('asal_instansi')->nullable();

            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
