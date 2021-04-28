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
        $schedule->call(function(){
            Task::trackFreeUsers();  
        })->timezone('Africa/Lagos')->daily();

        $schedule->call(function(){
            Task::sharePendingWallet();
        })->timezone('Africa/Lagos')->dailyAt('00:30');

        $schedule->call(function(){
            Task::gsClub();
        })->everyMinute();
        

        $schedule->call(function(){
            // Task::shareSignupProfit();
            // Task::sAgentRGcoin();
            Task::superAssocReward();
        })->everyThreeMinutes();

        $schedule->call(function(){
            Task::lmp();
            Task::ppp();
            Task::rPPP();
        })->everyTwoMinutes();

        $schedule->call(function(){
            Task::ranking();
        })->everyFourMinutes();


        // $schedule->call(function(){
        //     Task::gsClub();
        // })->everyThirtyMinutes();

        // $schedule->call(function(){
            
        // })->timezone('Africa/Lagos')->dailyAt('23:00');

        $schedule->call(function(){
            Task::trading();
        })->timezone('Africa/Lagos')->daily();

        // $schedule->call(function(){
            
        // })->timezone('Africa/Lagos')->dailyAt('01:00');

        // $schedule->call(function(){
            
        // })->timezone('Africa/Lagos')->dailyAt('02:00');
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
