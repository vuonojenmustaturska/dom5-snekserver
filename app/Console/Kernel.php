<?php

namespace Snek\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
        Commands\SnekStatus::class,
        Commands\ParseGameStats::class,
        Commands\FetchCurrentTurnStats::class,
        Commands\CheckMapThumbnails::class,
        Commands\CheckModThumbnails::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('snekstatus')->everyMinute();
        //          ->hourly();
    }
}