<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodeLaporanTentatifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('periode_laporans')->insert([

            [
                'nama' => 'Program RKA',
                'jenis_laporan_id' => 1,
                'tanggal_awal' => '2021-12-01 00:00:00',
                'tanggal_akhir' => '2021-12-15 00:00:00',
                'jenis_periode' => 'tentatif'
            ],
            [
                'nama' => 'Bulanan Kegiatan',
                'jenis_laporan_id' => 1,
                'tanggal_awal' => '2021-12-01 00:00:00',
                'tanggal_akhir' => '2021-12-15 00:00:00',
                'jenis_periode' => 'tentatif'
            ],
            [
                'nama' => 'Bulanan PMK',
                'jenis_laporan_id' => 1,
                'tanggal_awal' => '2021-12-01 00:00:00',
                'tanggal_akhir' => '2021-12-15 00:00:00',
                'jenis_periode' => 'tentatif'
            ],
        ]);
    }
}
