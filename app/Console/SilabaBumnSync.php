<?php

namespace App\Console;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Carbon\Carbon;

class SilabaBumnSync extends Command
{
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
        $response = $client->request('GET', env('SILABA_HOST') . 'service/bumn');
        $body = json_decode($response->getBody());
        if($body){
            $now = Carbon::now()->format('Y-m-d H:i:s');
            // $data = array();
            foreach ($body as $value) {
                // $data[] = [
                //       'id' => $value->id,
                //       'id_angka' => $value->id_angka,
                //       'id_huruf' => $value->id_huruf,
                //       'nama_lengkap' => $value->nama_lengkap,
                //       'nama_singkat' => $value->nama_singkat,
                //       'logo' => $value->logo,
                //       'jenis_perusahaan' => $value->jenis_perusahaan,
                //       'kepemilikan' => $value->kepemilikan,
                //       'bidang_usaha' => $value->bidang_usaha,
                //       'visi' => $value->visi,
                //       'misi' => $value->misi,
                //       'url' => $value->url,
                //       'induk' => $value->induk,
                //       'level' => $value->level,
                //       'created_at' => $now
                //     ];

                \DB::table('perusahaans')
                  ->updateOrInsert(
                      ['id' => $value->id],
                      [
                        'id_angka' => $value->id_angka,
                        'id_huruf' => $value->id_huruf,
                        'nama_lengkap' => $value->nama_lengkap,
                        'nama_singkat' => $value->nama_singkat,
                        'logo' => $value->logo,
                        'jenis_perusahaan' => $value->jenis_perusahaan,
                        'kepemilikan' => $value->kepemilikan,
                        'bidang_usaha' => $value->bidang_usaha,
                        'visi' => $value->visi,
                        'misi' => $value->misi,
                        'url' => $value->url,
                        'induk' => $value->induk,
                        'level' => $value->level,
                        'created_at' => $now,
                        'tgl_sinkronisasi' => $now
                      ]
                  );
            }
            // if(count($data) > 0){
            //     \DB::table('bumns')->delete();
            //     \DB::table('bumns')->insert($data);
            // }
        }
        
    }
}
