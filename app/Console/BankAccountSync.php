<?php

namespace App\Console;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Carbon\Carbon;

class BankAccountSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apisync:bankaccount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync data bank account dari simanis api';

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

        // sync provinsi
        $response = $client->request('GET', env('SIMANIS_HOST') . 'api/payroll');
        $body = json_decode($response->getBody());
        if($body){
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $data = array();
            foreach ($body->data as $value) {
                $data[] = [
                      'id' => $value->id,
                      'kode_bank' => $value->kode_bank,
                      'nama' => $value->nama,
                      'keterangan' => $value->keterangan,
                      'created_at' => $now,
                      'tgl_sinkronisasi' => $now
                    ];
            }
            if(count($data) > 0){
                \DB::table('bank_account')->delete();
                \DB::table('bank_account')->insert($data);
            }
        }
        
       
    }

}