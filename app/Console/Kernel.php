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
        // Process scheduled SMS messages every minute
        $schedule->command('sms:process-scheduled')
                ->everyMinute()
                ->withoutOverlapping();

        // Check SMS delivery status every 5 minutes
        $schedule->command('sms:check-delivery')
                ->everyFiveMinutes()
                ->withoutOverlapping();

        // Process recurring donations daily
        $schedule->command('donations:process-recurring')
                ->daily()
                ->at('00:00')
                ->withoutOverlapping();
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