<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         $schedule->command('init:task')->everyMinute();//declara el tiempo de ejecucion
         $schedule->command('init:taskdate')->everyMinute();//declara el tiempo de ejecucion
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        Commands\ScheduleTask::class;//declara la tarea
        Commands\PostsScheduledDateTask::class;//se declara la tarea 
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
