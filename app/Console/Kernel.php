<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\SilabaBumnSync;
use App\Console\Commands\PortalAppKegiatanSync;

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

        //service sync kegiatan app tjsl dilakukan setiap menit
        $schedule->command('portalApp:KegiatanSync')->everyMinute();  

        //service sync kegiatan app tjsl dilakukan setiap menit 
        $schedule->command('apptjsl:kegiatansync')->everyMinute();        
        
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
