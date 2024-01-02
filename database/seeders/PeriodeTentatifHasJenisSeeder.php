<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodeTentatifHasJenisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('periode_has_jenis')->insert([

            [
                'periode_laporan_id' => 15,
                'jenis_laporan_id' => 1,
            ],
            [
                'periode_laporan_id' => 16,
                'jenis_laporan_id' => 1,
            ],
            [
                'periode_laporan_id' => 17,
                'jenis_laporan_id' => 1,
            ],
        ]);
    }
}
