<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BackupClean extends Command
{
    protected $signature = 'backup:clean {--days=30 : Delete backups older than X days}';
    protected $description = 'Clean old backup files';

    public function handle()
    {
        $days = $this->option('days');
        $backupPath = storage_path('app/backups');
        
        if (!is_dir($backupPath)) {
            $this->info('No backup directory found.');
            return;
        }
        
        $files = glob($backupPath . '/*.zip');
        $cutoffDate = Carbon::now()->subDays($days);
        $deletedCount = 0;
        
        foreach ($files as $file) {
            $fileTime = Carbon::createFromTimestamp(filemtime($file));
            if ($fileTime->lessThan($cutoffDate)) {
                if (unlink($file)) {
                    $this->info('Deleted: ' . basename($file));
                    $deletedCount++;
                }
            }
        }
        
        $this->info("✅ Cleanup completed. Deleted {$deletedCount} old backups.");
    }
}