<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\ProcessUserMessages;

class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ProcessUserMessages::class,
    ];
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('user:process')->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {

        $this->load(__DIR__.'/Commands');

        $this->commands([
            \App\Console\ProcessUserMessages::class,
        ]);

        require base_path('routes/console.php');
    }
}
