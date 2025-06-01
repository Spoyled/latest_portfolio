<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class CleanOldBackups extends Command
{
    protected $signature = 'backups:clean-old';
    protected $description = 'Delete backups older than 7 days';

    public function handle()
    {
        $backupPath = storage_path('app/Laravel');
        $files = File::files($backupPath);

        foreach ($files as $file) {
            $lastModified = Carbon::createFromTimestamp(File::lastModified($file));

            if ($lastModified->lt(now()->subDays(7))) {
                File::delete($file->getPathname());
                $this->info("Deleted: " . $file->getFilename());
            }
        }

        $this->info('Old backups cleaned up.');
    }
}
