<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisKegiatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('jenis_kegiatans')->insert([
            //TPB 1

            [

                'nama' => 'Beasiswa/Bantuan Pendidikan',
                'keterangan' => null,
            ],
            [

                'nama' => 'Prasarana Pendidikan',
                'keterangan' => null,
            ],
            [

                'nama' => 'Sarana Pendidikan',
                'keterangan' => null,
            ],
            [

                'nama' => 'Penanaman Pohon',
                'keterangan' => null,
            ],
            [

                'nama' => 'Air Bersih',
                'keterangan' => null,
            ],
            [

                'nama' => 'Pengelolaan Sampah',
                'keterangan' => null,
            ],
            [

                'nama' => 'Disabilitas',
                'keterangan' => null,
            ],
            [

                'nama' => 'Jembatan',
                'keterangan' => null,
            ],
        ]);
    }
}
