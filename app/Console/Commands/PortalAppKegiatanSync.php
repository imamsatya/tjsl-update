<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Models\TargetTpb;
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

        $response = $client->request('GET', env('APP_TJSL_HOST').'api/get-kegiatan',['verify'=>false]);
        $body = json_decode($response->getBody());

        if($body){
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $sumber_data = env('APP_TJSL_HOST').'api/get-kegiatan';
            $banyak_data = [];

            $data = $body->data; 

            //clear data log setiap 24 jam (untuk antisipasi explosive log data sync)
            if(\DB::table('log_sinkronisasi_kegiatan')->count() > 24){
                DB::table('log_sinkronisasi_kegiatan')->truncate();
            }

            //clean invalid
            $cek_activity = Kegiatan::whereNotNull('sumber_data')->count();
            if($cek_activity > 0){
                Kegiatan::whereNotNull('sumber_data')->update([
                    'is_invalid_aplikasitjsl'=>false
                ]);            
            }
            $cek_real = KegiatanRealisasi::whereNotNull('sumber_data')->count();
            if($cek_real > 0){
                KegiatanRealisasi::whereNotNull('sumber_data')->update([
                    'is_invalid_aplikasitjsl'=>false
                ]);            
            }

            foreach($data as $k=>$value){
                //Lakukan filter hanya jika id_program,tahun dan bulan tidak kosong
                if($value->id_program && $value->bulan && $value->tahun && $value->id_bumn && $value->id_bumn > 0){
                    $cek_program = TargetTpb::find((int)$value->id_program);
                    //keterangan hasil cek ketersediaan id program di portal
                    $status_program = $cek_program? 'available' : 'undefined';
                    Kegiatan::updateOrCreate(
                        ['id_kegiatan_aplikasitjsl' => $value->id_kegiatan],
                        [
                            'target_tpb_id' => is_numeric($value->id_program)? $value->id_program : 0, //primary
                            'kegiatan' => $value->kegiatan?$value->kegiatan : null,
                            'provinsi_id' => is_numeric($value->id_provinsi_portal)?$value->id_provinsi_portal : null,
                            'kota_id' => is_numeric($value->id_kab_kota_portal)?$value->id_kab_kota_portal : null,
                            'indikator' => $value->indikator_capaian_kegiatan?$value->indikator_capaian_kegiatan : null,
                            'satuan_ukur_id' => is_numeric($value->id_satuan_ukur)?$value->id_satuan_ukur : null,
                            'anggaran_alokasi' => is_numeric($value->alokasi_anggaran_tahun)? (int)preg_replace('/\D/', '',$value->alokasi_anggaran_tahun) : 0,
                            'realisasi' => $value->realisasi_anggaran_bulan? (int)preg_replace('/\D/', '',$value->realisasi_anggaran_bulan) : 0,
                            'created_at' => $now,
                            'updated_at' => $now,
                            'sumber_data' => $sumber_data,//custom audit trail
                            'tgl_sinkronisasi_api' => $now, //custom audit trail
                            'id_kegiatan_aplikasitjsl' => is_numeric($value->id_kegiatan)? $value->id_kegiatan : 0, //custom audit trail
                            'id_bumn_aplikasitjsl' => is_numeric($value->id_bumn)? $value->id_bumn : 0, //custom audit trail
                            'status_id_program_aplikasitjsl' => $status_program, //custom audit trail
                            'is_invalid_aplikasitjsl' =>$value->is_delete > 0? true : false
                       ]);

                       //get id terakhir kegiatan
                       $last_id = Kegiatan::orderby('id','desc')->pluck('id')->first();
                       $id_kegiatan_apptjsl = KegiatanRealisasi::where('id_kegiatan_aplikasitjsl',$value->id_kegiatan)->first();

                       if($id_kegiatan_apptjsl){
                            $id_kegiatan_apptjsl->update(
                            [
                                'bulan' => is_numeric($value->bulan)?(int)$value->bulan : 0,
                                'tahun' => is_numeric($value->tahun)?(int)$value->tahun : 0,
                                'target' => is_numeric($value->target_bulan)? $value->target_bulan : 0,               
                                'realisasi' => is_numeric($value->realisasi_bulan)? $value->realisasi_bulan : 0,            
                                'anggaran' => is_numeric($value->alokasi_anggaran_tahun)? (int) $value->alokasi_anggaran_tahun : 0,                     
                                'anggaran_total' => is_numeric($value->realisasi_anggaran_bulan)? (int) $value->realisasi_anggaran_bulan : 0,
                                'status_id' => 1,
                                'created_at' => $now,
                                'updated_at' => $now,
                                'sumber_data' => $sumber_data, //custom audit trail
                                'tgl_sinkronisasi_api' => $now, //custom audit trail
                                'id_bumn_aplikasitjsl' => is_numeric($value->id_bumn)?$value->id_bumn : 0, //custom audit trail
                                'status_id_program_aplikasitjsl' => $status_program, //custom audit trail
                                'is_invalid_aplikasitjsl' =>$value->is_delete > 0? true : false
                           ]);
                       }else{
                            KegiatanRealisasi::insert(
                            [
                                'kegiatan_id' => (int)$last_id,               
                                'bulan' => is_numeric($value->bulan)?(int)$value->bulan : 0,
                                'tahun' => is_numeric($value->tahun)?(int)$value->tahun : 0,
                                'target' => is_numeric($value->target_bulan)? $value->target_bulan : 0,               
                                'realisasi' => is_numeric($value->realisasi_bulan)? $value->realisasi_bulan : 0,            
                                'anggaran' => is_numeric($value->alokasi_anggaran_tahun)? (int) $value->alokasi_anggaran_tahun : 0,                     
                                'anggaran_total' => is_numeric($value->realisasi_anggaran_bulan)? (int) $value->realisasi_anggaran_bulan : 0,
                                'status_id' => 1,
                                'created_at' => $now,
                                'updated_at' => $now,
                                'sumber_data' => $sumber_data, //custom audit trail
                                'tgl_sinkronisasi_api' => $now, //custom audit trail
                                'id_bumn_aplikasitjsl' => is_numeric($value->id_bumn)?$value->id_bumn : 0, //custom audit trail
                                'status_id_program_aplikasitjsl' => $status_program, //custom audit trail
                                'is_invalid_aplikasitjsl' =>$value->is_delete > 0? true : false
                           ]);
                       }
                    if($cek_program){
                        $banyak_data[] = count([$value]);
                    }
                }
            }

       
            //catat data log
            \DB::table('log_sinkronisasi_kegiatan')->insert([
                'jumlah_data' => count($banyak_data),
                'user_id' => auth()->user()->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

        }

    }
}
