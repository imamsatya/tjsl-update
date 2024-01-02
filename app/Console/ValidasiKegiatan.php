<?php

namespace App\Console;

use Illuminate\Console\Command;

class ValidasiKegiatan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'validasi:kegiatan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change status finish to kegiatan';

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
        //update kegiatan status finish yang lewat tanggal 15
        \DB::statement("update kegiatan_realisasis set status_id = 1 where tahun < date_part('year', CURRENT_DATE) OR bulan < date_part('month', CURRENT_DATE)");
    }
}
