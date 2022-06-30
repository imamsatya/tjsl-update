<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TargetTpb;
use App\Models\Kegiatan;
use App\Models\KegiatanRealisasi;
use Carbon\Carbon;
use DB;

class SinkronisasiKegiatanByBumn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'syncbumn:activity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi data kegiatan masing2 bumn dari app tjsl update 20220606';

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
        $id_bumns = auth()->user()->id_bumn;
        $data = [];
        $sumber_data = '';
        if($id_bumns){
            $data = DB::table('kegiatan_app_tjsl')->where('id_bumn',(int)$id_bumns)->get();
            $sumber_data = env('APP_TJSL_HOST').'api/get-kegiatan-by-bumn/'.$id_bumns;

            $kegiatan_last = Kegiatan::whereNotNull('sumber_data')->where('id_bumn_aplikasitjsl',$id_bumns)->get();
            if(!empty($kegiatan_last)){
                foreach($kegiatan_last as $v){
                    $val = $v->update([
                        'is_invalid_aplikasitjsl'=>true
                    ]);
                }
            }
    
            // $kegiatan_realisasi_last = KegiatanRealisasi::whereNotNull('sumber_data')->where('id_bumn_aplikasitjsl',$id_bumns)->get();
            // if(!empty($kegiatan_realisasi_last)){
            //     foreach($kegiatan_realisasi_last as $v){
            //         $val = $v->update([
            //             'is_invalid_aplikasitjsl'=>true
            //         ]);
            //     }
            // }

        }
        $now = Carbon::now()->format('Y-m-d H:i:s');


        if(!empty($data)){
            foreach($data as $k=>$value){

                //Lakukan filter hanya jika id_program,tahun dan bulan tidak kosong
                if($value->id_program && $value->bulan && $value->tahun && $value->id_bumn && $value->id_bumn > 0){
                    $cek_program = TargetTpb::find((int)$value->id_program);
                    //keterangan hasil cek ketersediaan id program di portal
                    $status_program = $cek_program? 'available' : 'undefined';
                    $data_keg = Kegiatan::updateOrCreate(
                        [
                            //'id_kegiatan_aplikasitjsl' => $value->id_kegiatan
                            'target_tpb_id' => is_numeric($value->id_program)? $value->id_program : 0, //primary
                            'kegiatan' => $value->kegiatan?$value->kegiatan : null,
                            'provinsi_id' => is_numeric($value->id_provinsi_portal)?(int)$value->id_provinsi_portal : null,
                            'kota_id' => is_numeric($value->id_kab_kota_portal)?(int)$value->id_kab_kota_portal : null,
                        ],
                        [
                            'target_tpb_id' => is_numeric($value->id_program)? $value->id_program : 0, //primary
                            'kegiatan' => $value->kegiatan?$value->kegiatan : null,
                            'provinsi_id' => is_numeric($value->id_provinsi_portal)?(int)$value->id_provinsi_portal : null,
                            'kota_id' => is_numeric($value->id_kab_kota_portal)?(int)$value->id_kab_kota_portal : null,
                            'indikator' => $value->indikator_capaian_kegiatan?$value->indikator_capaian_kegiatan : null,
                            'satuan_ukur_id' => is_numeric($value->id_satuan_ukur)? (int)$value->id_satuan_ukur : null,
                            'anggaran_alokasi' => is_numeric($value->alokasi_anggaran_tahun)? (int)preg_replace('/\D/', '',$value->alokasi_anggaran_tahun) : 0,
                            'realisasi' => $value->realisasi_anggaran_bulan? (int)preg_replace('/\D/', '',$value->realisasi_anggaran_bulan) : 0,
                            'created_at' => $now,
                            'updated_at' => $now,
                            'sumber_data' => $sumber_data,//custom audit trail
                            'tgl_sinkronisasi_api' => $now, //custom audit trail
                            'id_kegiatan_aplikasitjsl' => is_numeric($value->id_kegiatan)? $value->id_kegiatan : 0, //custom audit trail
                            'id_bumn_aplikasitjsl' => is_numeric($value->id_bumn)? $value->id_bumn : 0, //custom audit trail
                            'status_id_program_aplikasitjsl' => $status_program, //custom audit trail
                            'is_invalid_aplikasitjsl' => (int)$value->is_delete > 0? true : false
                    ]);


                    $realisasi = KegiatanRealisasi::where('kegiatan_id',(int)$data_keg->id)
                                            ->where('bulan', $value->bulan)
                                            ->where('tahun', $value->tahun)
                                            ->get();

                    if($realisasi->count()==0){
                        $realisasi = KegiatanRealisasi::create([
                                'kegiatan_id' => (int)$data_keg->id,               
                                'bulan' => is_numeric($value->bulan)?(int)$value->bulan : 0,
                                'tahun' => is_numeric($value->tahun)?(int)$value->tahun : 0,
                                'target' => is_numeric($value->target_bulan)? $value->target_bulan : 0,               
                                'realisasi' => is_numeric($value->realisasi_bulan)? $value->realisasi_bulan : 0,            
                                'anggaran_total' => is_numeric($value->alokasi_anggaran_tahun)? (int) $value->alokasi_anggaran_tahun : 0,                     
                                'anggaran' => is_numeric($value->realisasi_anggaran_bulan)? (int) $value->realisasi_anggaran_bulan : 0,
                                'status_id' => 1,
                                'created_at' => $now,
                                'updated_at' => $now,
                                'sumber_data' => $sumber_data, //custom audit trail
                                'tgl_sinkronisasi_api' => $now, //custom audit trail
                                'id_kegiatan_aplikasitjsl' => is_numeric($value->id_kegiatan)? $value->id_kegiatan : 0, //custom audit trail
                                'id_bumn_aplikasitjsl' => is_numeric($value->id_bumn)?$value->id_bumn : 0, //custom audit trail
                                'status_id_program_aplikasitjsl' => $status_program, //custom audit trail
                                'is_invalid_aplikasitjsl' =>$value->is_delete > 0? true : false
                        ]);

                        $realisasi_total = KegiatanRealisasi::select(DB::Raw('sum(kegiatan_realisasis.anggaran) as total'))
                                                            ->where('kegiatan_id',(int)$data_keg->id)
                                                            ->where('bulan','<',$value->bulan)
                                                            ->where('tahun',$value->tahun)
                                                            ->first();
      
                        $paramr['anggaran_total'] = (int)$realisasi_total->total + $realisasi->anggaran;
                        $realisasi->update((array)$paramr);
                    }

                }
            }
        }

        //clear log tiap 1000x sync
        $cek_log = DB::table('log_sinkronisasi_kegiatan')->where('user_id',(int)auth()->user()->id);
        if($cek_log->count() >= 1000){ 
            $cek_log->delete();
        }
        
        //simpan log
        $jumlah = count($data);
        $log = [
                'jumlah_data' => (int)$jumlah,
                'user_id' => (int)auth()->user()->id,
                'tipe_api' => 'bumn',
                'created_at' => $now,
                'updated_at' => $now
        ];
        DB::table('log_sinkronisasi_kegiatan')->insert($log);

    }
}
