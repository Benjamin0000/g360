<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Controllers\Task;

class Kernel extends ConsoleKernel
{
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
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            Task::trackFreeUsers();
        })->timezone('Africa/Lagos')->daily();

        $schedule->call(function () {
            Task::sharePendingWallet();
        })->timezone('Africa/Lagos')->dailyAt('00:30');

        $schedule->call(function () {
            Task::weeklyBonus();
        })->timezone('Africa/Lagos')->weeklyOn(3,'8:00');

        $schedule->call(function () {
            Task::autoUpgrade();
        })->everyMinute();

        $schedule->call(function () {
            Task::circleBonus();
        })->hourly();
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
