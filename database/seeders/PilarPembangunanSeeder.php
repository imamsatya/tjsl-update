<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PilarPembangunanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('pilar_pembangunans')->insert([
            //
            [

                'nama' => 'Pilar Pembangunan Sosial',
                'jenis_anggaran' => 'non CID',
                'keterangan' => null,
            ],
            [

                'nama' => 'Pilar Pembangunan Ekonomi',
                'jenis_anggaran' => 'non CID',
                'keterangan' => null,
            ],
            [

                'nama' => 'Pilar Pembangunan Hukum dan Tata Kelola',
                'jenis_anggaran' => 'non CID',
                'keterangan' => null,
            ],
            [

                'nama' => 'Pilar Pembangunan Lingkungan',
                'jenis_anggaran' => 'non CID',
                'keterangan' => null,
            ],
        ]);
    }
}
