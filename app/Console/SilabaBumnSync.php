<?php

namespace App\Console;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Carbon\Carbon;
use DB;

class SilabaBumnSync extends Command
{
    // Sumber data bumn diubah ke portal https://satudata.bumn.go.id

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'silaba:bumnsync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Data Bumn sync from silaba';

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
     * @return mixed
     */
    public function handle()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://satudata.bumn.go.id/api/hirarkipengendali',
        [
            'headers' => ['Authorization'=>'Bearer 14|gV2GVETcj7d0t2rzc9FmFkDJdzMKdwQ6IzbRtzGU']
        ]);
        $body = json_decode($response->getBody());
        if($body->data){
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $update = 0;
            $insert = 0;
            $user_id = auth()->user()->id;
            foreach ($body->data as $value) {
                \DB::table('perusahaan_masters')
                  ->updateOrInsert(
                      ['id' => (int)$value->id_entitas],
                      [
                        'id_angka' => $value->kode_angka,
                        'id_huruf' => $value->kode_huruf,
                        'nama_lengkap' => $value->nama_lengkap,
                        'nama_singkat' => $value->nama_singkat,
                        'logo' => $value->path_logo_hires,
                        'jenis_perusahaan' => $value->bentuk_entitas,
                        'kepemilikan' => $value->kepemilikan_pemerintah?$value->kepemilikan_pemerintah : $value->kepemilikan_bumn,
                        'kepemilikan_pemerintah' => $value->kepemilikan_pemerintah,
                        'kepemilikan_bumn' => $value->kepemilikan_bumn,
                        'bidang_usaha' => $value->bidang_usaha,
                        'visi' => $value->visi,
                        'misi' => $value->misi,
                        'url' => $value->url,
                        'induk' => $value->id_entitas_induk,
                        'level' => $value->level_hirarki,
                        'status_operasi' => $value->status_operasi,
                        'klaster_industri' => $value->klaster_industri,
                        'sumber_data' => 'https://satudata.bumn.go.id/api/hirarkipengendali',
                        'klaster_industri' => $value->klaster_industri,
                        
                        'id_bumn_tjsl_old' => DB::table('perusahaans')->where('id',(int)$value->id_entitas)->pluck('id')->count() > 0? DB::table('perusahaans')->where('id',(int)$value->id_entitas)->pluck('id')->first() : 0,
                        
                        'is_active' => DB::table('perusahaans')->where('id',(int)$value->id_entitas)->pluck('id')->count() > 0? (boolean)DB::table('perusahaans')->where('id',(int)$value->id_entitas)->pluck('is_active')->first() : false,

                        'nama_lengkap_bumn_tjsl_old' => DB::table('perusahaans')->where('id',(int)$value->id_entitas)->pluck('id')->count() > 0? DB::table('perusahaans')->where('id',(int)$value->id_entitas)->pluck('nama_lengkap')->first() : false,

                        'aksi' => DB::table('perusahaans')->where('id',(int)$value->id_entitas)->pluck('id')->count() > 0? 'update' : 'insert',

                        'tgl_sinkronisasi' => $now
                      ]
                );

                $actionBaru = DB::table('perusahaans')->where('id',(int)$value->id_entitas)->pluck('id')->count() > 0? 'update' : 'insert';
                $actionLama = DB::table('perusahaan_masters')->where('id',(int)$value->id_entitas)->pluck('id')->count() > 0? 'update' : 'insert';

                if($actionBaru == 'update' && $actionLama == 'update'){
                    $update++;
                }elseif($actionBaru == 'insert' && $actionLama == 'insert'){
                    $insert++;
                }elseif($actionBaru == 'insert' && $actionLama == 'update'){
                    $insert++;
                }
            }

            \DB::table('log_sync_perusahaans')->insert([
                'jumlah_data_insert'=> $insert,
                'jumlah_data_update'=> $update,
                'sumber_data'=> 'https://satudata.bumn.go.id/api/hirarkipengendali',
                'sync_by_id'=> $user_id,
                'created_at'=> $now,
            ]);

        }
        
    }
}
