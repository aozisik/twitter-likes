<?php

namespace App\Console;

use App\Console\Commands\EngageFollowers;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\TrackConversions;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        EngageFollowers::class,
        TrackConversions::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('followers:conversions')
            ->everyFiveMinutes();

        $schedule->command('followers:engage')
            ->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
