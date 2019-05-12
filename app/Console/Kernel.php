<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule) {
//        $schedule->exec('touch foo.txt')->everyMinute();
        // $schedule->command('inspire')
        //          ->hourly();

//        $schedule->call(function () {
//            exec('touch f5.txt');
//        })->everyFiveMinutes();

//        $schedule->command('smm:get_statuses --save')->everyMinute();

//        $schedule->command('smm:take1')->everyMinute();
//        $schedule->command('smm:take5')->everyFiveMinutes();
//        $schedule->command('smm:take10')->everyTenMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands() {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
