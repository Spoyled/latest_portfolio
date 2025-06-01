<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeleteOldClosedPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-old-closed-posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredPosts = Post::whereNotNull('closed_at')
            ->where('closed_at', '<=', now()->subWeeks(2))
            ->get();

        foreach ($expiredPosts as $post) {
            $post->delete();
        }

        $this->info('Old closed posts deleted successfully.');
    }

}
