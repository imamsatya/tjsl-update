<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\SilabaBumnSync;
use App\Console\Commands\PortalAppKegiatanSync;
use App\Console\Commands\SinkronisasiKegiatanByBumn;
use App\Console\Commands\SinkronisasiKegiatanGlobal;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        SilabaBumnSync::class,
        ProvinsiKotaSync::class,
        BankAccountSync::class,
        ValidasiKegiatan::class,
        PortalAppKegiatanSync::class,
        SinkronisasiKegiatanByBumn::class,
        SinkronisasiKegiatanGlobal::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //service validasi data kegiatan dilakukan setiap tanggal 15 jam 2:00
        $schedule->command('validasi:kegiatan')->monthlyOn(15, '02:00');

        //service sync kegiatan app tjsl dilakukan setiap jam 
        $schedule->command('apptjsl:kegiatansync')->everyTwoMinutes();        

        //service sync kegiatan app tjsl dilakukan setiap jam
        $schedule->command('syncglobal:activity')->everyFiveMinutes();
        
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
