<?php

namespace App\Console;

use Illuminate\Database\Eloquent\Relations\Relation;
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
        Commands\Sales::class,
        Commands\XMLSitemap::class,
        Commands\Exports::class,
        Commands\CartCleaner::class,
        Commands\ViewsCleaner::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('xmlsitemap')->daily();
        $schedule->command('generate_exports')->everyMinute();
        $schedule->command('clear_carts')->daily();
        $schedule->command('sales')->everyTenMinutes();
        $schedule->command('views_сleaner')->everyMinute();
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
