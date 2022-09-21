<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePerusahaanMasters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perusahaan_masters', function (Blueprint $table) {
            $table->integer('id')->nullable();
            $table->string('id_angka')->nullable();
            $table->string('id_huruf')->nullable();
            $table->string('nama_lengkap')->nullable();
            $table->string('nama_singkat')->nullable();
            $table->text('logo')->nullable(); //path_logo_hires
            $table->string('jenis_perusahaan')->nullable();
            $table->string('kepemilikan')->nullable();
            $table->string('kepemilikan_pemerintah')->nullable();
            $table->string('kepemilikan_bumn')->nullable();
            $table->text('bidang_usaha')->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->text('url')->nullable();
            $table->string('induk')->nullable();
            $table->string('level')->nullable();
            $table->string('status_operasi')->nullable();
            $table->string('klaster_industri')->nullable();
            $table->string('tgl_sinkronisasi')->nullable();
            $table->string('sumber_data')->nullable();
            $table->boolean('is_active')->default(false);
            $table->string('nama_lengkap_bumn_tjsl_old')->nullable();
            $table->integer('id_bumn_tjsl_old')->nullable();
            $table->string('aksi')->nullable();
            $table->timestamps();
        });

        Schema::create('log_sync_perusahaans', function (Blueprint $table) {
            $table->string('jumlah_data_insert')->nullable();
            $table->string('jumlah_data_update')->nullable();
            $table->string('sumber_data')->nullable();
            $table->integer('sync_by_id')->nullable();
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
        Schema::dropIfExists('perusahaan_masters');
        Schema::dropIfExists('log_sync_perusahaans');
    }
}
