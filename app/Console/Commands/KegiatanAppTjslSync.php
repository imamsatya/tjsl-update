<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\KegiatanByAppTjsl;
use GuzzleHttp\Client;
use Carbon\Carbon;
use DB;

class KegiatanAppTjslSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apptjsl:kegiatansync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command untuk cek api kegiatan aplikasi TJSL';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', 'http://aplikasitjsl.bumn.go.id/api/get-kegiatan');
        $body = json_decode($response->getBody());

        if($body){
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $cek = KegiatanByAppTjsl::get();

            if(count($cek) > 0){
                DB::table('kegiatan_app_tjsl')->truncate();
            }

            $datas = $body->data; 
            $data = [];
            foreach($datas as $param){
                $data[] = [
                    "id_kegiatan"	=>$param->id_kegiatan,
                    "id_bumn"	=>$param->id_bumn,
                    "id_program"	=>$param->id_program,
                    "sektor"	=>$param->sektor,
                    "kegiatan"	=>$param->kegiatan,
                    "id_provinsi_portal"	=>$param->id_provinsi_portal,
                    "id_kab_kota_portal"	=>$param->id_kab_kota_portal,
                    // "id_provinsi_origin"	=>$param->id_provinsi_origin,
                    // "id_kab_kota_origin"	=>$param->id_kab_kota_origin,
                    "kecamatan"	=>$param->kecamatan,
                    "id_kelurahan_desa"	=>$param->id_kelurahan_desa,
                    "id_pilar_portal"	=>$param->id_pilar_portal,
                    "id_tpb_portal"	=>$param->id_tpb_portal,
                    "id_indikator_portal"	=>$param->id_indikator_portal,
                    // "id_pilar_origin"	=>$param->id_pilar_origin,
                    // "id_tpb_origin"	=>$param->id_tpb_origin,
                    // "id_indikator_origin"	=>$param->id_indikator_origin,
                    "map_marker"	=>$param->map_marker,
                    "pemohon"	=>$param->pemohon,
                    "alamat"	=>$param->alamat,
                    "anggaran_permintaan"	=>$param->anggaran_permintaan,
                    "realisasi_total"	=>$param->realisasi_total,
                    "indikator_capaian_kegiatan"	=>$param->indikator_capaian_kegiatan,
                    "id_satuan_ukur"	=>$param->id_satuan_ukur,
                    "bulan"	=>$param->bulan,
                    "tahun"	=>$param->tahun,
                    "alokasi_anggaran_tahun"	=>$param->alokasi_anggaran_tahun,
                    "realisasi_anggaran_bulan"	=>$param->realisasi_anggaran_bulan,
                    "target_bulan"	=>$param->target_bulan,
                    "realisasi_bulan"	=>$param->realisasi_bulan
                ];
            }

            if(count($data) > 0){
                \DB::table('kegiatan_app_tjsl')->insert($data);
            }


        }
        
    }
}
