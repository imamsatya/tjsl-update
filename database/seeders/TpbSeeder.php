<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TpbSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('tpbs')->insert([
            //TPB 1

            [
                'no_tpb' => 'TPB 1',
                'nama' => 'Tanpa Kemiskinan',
                'jenis_anggaran' => 'non CID',
                'keterangan' => null,
            ],
            //TPB 2

            [
                'no_tpb' => 'TPB 2',
                'nama' => 'Tanpa Kelaparan',
                'jenis_anggaran' => 'non CID',
                'keterangan' => null,
            ],
            //TPB 3

            [
                'no_tpb' => 'TPB 3',
                'nama' => 'Kehidupan Sehat & Sejahtera',
                'jenis_anggaran' => 'non CID',
                'keterangan' => null,
            ],
            //TPB 4

            [
                'no_tpb' => 'TPB 4',
                'nama' => 'Pendidikan Berkualitas',
                'jenis_anggaran' => 'non CID',
                'keterangan' => null,
            ],
            //TPB 5

            [
                'no_tpb' => 'TPB 5',
                'nama' => 'Kesetaraan Gender',
                'jenis_anggaran' => 'non CID',
                'keterangan' => null,
            ],
            //TPB 6

            [
                'no_tpb' => 'TPB 6',
                'nama' => 'Air Bersih & Sanitas Layak',
                'jenis_anggaran' => 'non CID',
                'keterangan' => null,
            ],
            //TPB 7

            [
                'no_tpb' => 'TPB 7',
                'nama' => 'Energi Bersih dan Terbarukan',
                'jenis_anggaran' => 'non CID',
                'keterangan' => null,
            ],
            //TPB 8
            [
                'no_tpb' => 'TPB 8',
                'nama' => 'Pekerjaan Layak dan Pertumbuhan Ekonomi',
                'jenis_anggaran' => 'non CID',
                'keterangan' => null,
            ],
            //TPB 9
            [
                'no_tpb' => 'TPB 9',
                'nama' => 'Industri, Inovasi dan Infrastruktur',
                'jenis_anggaran' => 'non CID',
                'keterangan' => null,
            ],
            //TPB 10
            [
                'no_tpb' => 'TPB 10',
                'nama' => 'Industri, Inovasi dan Infrastruktur',
                'jenis_anggaran' => 'non CID',
                'keterangan' => null,
            ],
            //TPB 11
            [
                'no_tpb' => 'TPB 11',
                'nama' => 'Kota dan Pemukiman Yang Berkelanjutan',
                'jenis_anggaran' => 'non CID',
                'keterangan' => null,
            ],
            //TPB 12
            [
                'no_tpb' => 'TPB 12',
                'nama' => 'Konsumsi dan Produksi yang Bertanggung Jawab',
                'jenis_anggaran' => 'non CID',
                'keterangan' => null,
            ],
            //TPB 13
            [
                'no_tpb' => 'TPB 13',
                'nama' => 'Penanganan Perubahan Iklim',
                'jenis_anggaran' => 'non CID',
                'keterangan' => null,
            ],
            //TPB 14
            [
                'no_tpb' => 'TPB 14',
                'nama' => 'Ekosistem Lautan',
                'jenis_anggaran' => 'non CID',
                'keterangan' => null,
            ],
            //TPB 15
            [
                'no_tpb' => 'TPB 15',
                'nama' => 'Ekosistem Daratan',
                'jenis_anggaran' => 'non CID',
                'keterangan' => null,
            ],
            //TPB 16
            [
                'no_tpb' => 'TPB 16',
                'nama' => 'Perdamaian, Keadilan dan Kelembagaan yang Tangguh',
                'jenis_anggaran' => 'non CID',
                'keterangan' => null,
            ],
            //TPB 17
            [
                'no_tpb' => 'TPB 17',
                'nama' => 'Kemitraan Untuk Mencapai Tujuan',
                'jenis_anggaran' => 'non CID',
                'keterangan' => null,
            ],


        ]);
    }
}
