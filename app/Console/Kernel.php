<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Definicija cron / scheduled poslova
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('orders:close-old')
            ->everyFiveMinutes()
            ->withoutOverlapping();
    }

    /**
     * Registracija artisan komandi
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
