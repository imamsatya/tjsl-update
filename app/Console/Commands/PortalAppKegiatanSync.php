<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Models\Kegiatan;
use App\Models\KegiatanRealisasi;
use DB;

class PortalAppKegiatanSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'portalApp:KegiatanSync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Untuk get data kegiatan dari api App TJSL kedalam Portal';

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
            $activity_exists = Kegiatan::get();
            $realisasi_exists = KegiatanRealisasi::get();
            $sumber_data = 'http://aplikasitjsl.bumn.go.id/api/get-kegiatan';
            $jumlah_data = [];

            $data = $body->data; 
            foreach($data as $k=>$value){
                //Lakukan filter hanya jika id_program,tahun dan bulan tidak kosong
                if($value->id_program && $value->bulan && $value->tahun){
                    Kegiatan::updateOrCreate(
                        ['id_kegiatan_aplikasitjsl' => $value->id_kegiatan],
                        [
                            'target_tpb_id' => $value->id_program, //primary
                            'kegiatan' => $value->kegiatan?$value->kegiatan : null,
                            'provinsi_id' => $value->id_provinsi_portal?$value->id_provinsi_portal : null,
                            'kota_id' => $value->id_kab_kota_portal?$value->id_kab_kota_portal : null,
                            'indikator' => $value->id_indikator_portal?$value->id_indikator_portal : null,
                            'satuan_ukur_id' => $value->id_satuan_ukur?$value->id_satuan_ukur : null,
                            'anggaran_alokasi' => $value->alokasi_anggaran_tahun? (int)preg_replace('/\D/', '',$value->alokasi_anggaran_tahun) : 0,
                            'realisasi' => $value->realisasi_anggaran_bulan? (int)preg_replace('/\D/', '',$value->realisasi_anggaran_bulan) : 0,
                            'created_at' => $now,
                            'updated_at' => $now,
                            'sumber_data' => $sumber_data,//custom
                            'tgl_sinkronisasi_api' => $now, //custom
                            'id_kegiatan_aplikasitjsl' => $value->id_kegiatan //custom
                       ]);

                       //get id terakhir kegiatan
                       $last_id = Kegiatan::orderby('id','desc')->pluck('id')->first();
                       $id_kegiatan_apptjsl = KegiatanRealisasi::where('id_kegiatan_aplikasitjsl',$value->id_kegiatan)->first();

                       if($id_kegiatan_apptjsl){
                            $id_kegiatan_apptjsl->update(
                            [
                                'bulan' => (int)$value->bulan,
                                'tahun' => (int)$value->tahun,
                                'target' => $value->target_bulan? $value->target_bulan : null,               
                                'realisasi' => $value->realisasi_bulan? $value->realisasi_bulan : 0,            
                                'anggaran' => $value->anggaran_permintaan? (int) $value->anggaran_permintaan : 0,                     
                                'anggaran_total' => $value->realisasi_bulan? (int) $value->realisasi_bulan : 0,
                                'status_id' => 2,
                                'created_at' => $now,
                                'updated_at' => $now,
                                'sumber_data' => $sumber_data,
                                'tgl_sinkronisasi_api' => $now
                           ]);
                       }else{
                            KegiatanRealisasi::insert(
                            [
                                'kegiatan_id' => (int)$last_id,               
                                'bulan' => (int)$value->bulan,
                                'tahun' => (int)$value->tahun,
                                'target' => $value->target_bulan? $value->target_bulan : null,               
                                'realisasi' => $value->realisasi_bulan? $value->realisasi_bulan : 0,            
                                'anggaran' => $value->anggaran_permintaan? (int) $value->anggaran_permintaan : 0,                     
                                'anggaran_total' => $value->realisasi_bulan? (int) $value->realisasi_bulan : 0,
                                'status_id' => 2, //default 
                                'created_at' => $now,
                                'updated_at' => $now,
                                'sumber_data' => $sumber_data,
                                'tgl_sinkronisasi_api' => $now,
                                'id_kegiatan_aplikasitjsl' => $value->id_kegiatan                     
                           ]);
                       }

                    $banyak_data[] = count([$value]);
                }
            }

            \DB::table('log_sinkronisasi_kegiatan')->insert([
                'jumlah_data' => count($banyak_data),
                'user_id' => auth()->user()->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

        }

    }
}
