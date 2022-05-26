<?php

namespace App\Console;

use App\Jobs\ReassignOrders;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        //
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new ReassignOrders())->withoutOverlapping()->everyMinute();
        $schedule->command('telescope:prune')->cron('0 0 */2 * *')->runInBackground();
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
