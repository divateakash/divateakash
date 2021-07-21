<?php

namespace App\Console;

use App\Jobs\AddConversions;
use App\Jobs\FinishCampaigns;
use App\Jobs\PauseCampaigns;
use App\Jobs\ProcessAccount;
use App\Jobs\ResolveDomains;
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
        $schedule->job(new ProcessAccount)->everyMinute();
        // $schedule->job(new ProcessPendingOutboundReplies)->everyFiveMinutes();
        $schedule->job(new FinishCampaigns)->everyMinute();
        $schedule->job(new AddConversions)->everyFiveMinutes();
        $schedule->job(new ResolveDomains)->everyFiveMinutes();
        $schedule->job(new PauseCampaigns)->everyMinute();
        $schedule->command('telescope:prune')->daily();
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
