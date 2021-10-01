<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Types\FloatType;
use Doctrine\DBAL\Types\Type;

class AlterChangeTipeDataPumkMb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Type::hasType('double')) {
            Type::addType('double', FloatType::class);
        }
        Schema::table('pumk_mitra_binaans', function (Blueprint $table) {
            $table->double('nilai_aset')->change();
            $table->double('nilai_omset')->change();
            $table->double('nominal_pendanaan')->change();
            $table->double('saldo_pokok_pendanaan')->change();
            $table->double('saldo_jasa_adm_pendanaan')->change();
            $table->double('penerimaan_pokok_bulan_berjalan')->change();
            $table->double('penerimaan_jasa_adm_bulan_berjalan')->change();
            $table->double('kelebihan_angsuran')->change();
        });

        Schema::table('pumk_mitra_binaan_upload_gagals', function (Blueprint $table) {
            $table->double('nilai_aset')->change();
            $table->double('nilai_omset')->change();
            $table->double('nominal_pendanaan')->change();
            $table->double('saldo_pokok_pendanaan')->change();
            $table->double('saldo_jasa_adm_pendanaan')->change();
            $table->double('penerimaan_pokok_bulan_berjalan')->change();
            $table->double('penerimaan_jasa_adm_bulan_berjalan')->change();
            $table->double('kelebihan_angsuran')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
