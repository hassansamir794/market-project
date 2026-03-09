<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('db:backup')->dailyAt('02:30');
        $schedule->command('monitor:ping')->everyFiveMinutes();
        $schedule->command('inventory:alert-low-stock')->dailyAt('10:00');
        $schedule->command('inventory:weekly-report')->weeklyOn(6, '08:30');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
