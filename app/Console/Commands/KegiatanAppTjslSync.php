<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\KegiatanByAppTjsl;
use GuzzleHttp\Client;
use Carbon\Carbon;
use DB;
use App\Models\TargetTpb;

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

        $response = $client->request('GET', env('APP_TJSL_HOST').'api/get-kegiatan',['verify'=>false]);
        $body = json_decode($response->getBody());

        if($body){
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $cek = KegiatanByAppTjsl::get();

            $datas = $body->data; 
            $data = [];
            foreach($datas as $param){
                if((int)$param->tahun > 2021){
                    $cek_program = TargetTpb::find((int)$param->id_program);
                    //keterangan hasil cek ketersediaan id program di portal
                    $status_program = $cek_program? 'available' : 'undefined';

                    \DB::table('kegiatan_app_tjsl')->updateOrInsert([
                            "id_kegiatan"	=>$param->id_kegiatan,
                        ],
                        [
                            "id_kegiatan"	=>$param->id_kegiatan,
                            "id_bumn"	=>$param->id_bumn,
                            "id_program"	=>$param->id_program,
                            "kegiatan"	=>$param->kegiatan,
                            "id_provinsi_portal"	=>$param->id_provinsi_portal,
                            "id_kab_kota_portal"	=>$param->id_kab_kota_portal,
                            "anggaran_permintaan"	=>$param->anggaran_permintaan,
                            "realisasi_total"	=>$param->realisasi_total,
                            "indikator_capaian_kegiatan"	=>$param->indikator_capaian_kegiatan,
                            "id_satuan_ukur"	=>$param->id_satuan_ukur,
                            "bulan"	=>$param->bulan,
                            "tahun"	=>$param->tahun,
                            "alokasi_anggaran_tahun"	=>$param->alokasi_anggaran_tahun,
                            "realisasi_anggaran_bulan"	=>$param->realisasi_anggaran_bulan,
                            "target_bulan"	=>$param->target_bulan,
                            "realisasi_bulan"	=>$param->realisasi_bulan,
                            "status_id_program_diportal" =>$status_program,
                            "is_delete" =>$param->is_delete,
                            "created_at" => $now
                        ]
                    );
                }
            }

        }
        
    }
}
