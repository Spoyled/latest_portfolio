<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Post;


class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            $cutoff = Carbon::now()->subWeeks(2); 

            $posts = Post::whereNotNull('closed_at')
                ->where('closed_at', '<=', $cutoff)
                ->get();

            foreach ($posts as $post) {
                $post->forceDelete(); 
            }
        })->everyMinute();

        $schedule->command('backups:clean-old')->everyMinute();
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
