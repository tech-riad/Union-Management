<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BackupVerify extends Command
{
    protected $signature = 'backup:verify';
    protected $description = 'Verify the latest backup integrity';

    public function handle()
    {
        $this->info('🔍 Verifying latest backup...');
        
        $backupPath = storage_path('app/backups');
        
        if (!is_dir($backupPath)) {
            $this->error('No backup directory found.');
            return 1;
        }
        
        $files = glob($backupPath . '/*.zip');
        
        if (empty($files)) {
            $this->error('No backup files found.');
            return 1;
        }
        
        // Get latest backup
        usort($files, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });
        
        $latestBackup = $files[0];
        $fileSize = filesize($latestBackup);
        $fileTime = Carbon::createFromTimestamp(filemtime($latestBackup));
        
        $this->info('📁 Latest Backup: ' . basename($latestBackup));
        $this->info('📅 Created: ' . $fileTime->format('Y-m-d H:i:s'));
        $this->info('💾 Size: ' . $this->formatBytes($fileSize));
        
        // Check if file is readable
        if (!is_readable($latestBackup)) {
            $this->error('❌ Backup file is not readable!');
            return 1;
        }
        
        // Check if zip file is valid
        $zip = new \ZipArchive();
        $zipStatus = $zip->open($latestBackup);
        
        if ($zipStatus !== TRUE) {
            $this->error('❌ Invalid ZIP file: ' . $zipStatus);
            return 1;
        }
        
        // Check for essential files
        $hasDatabase = $zip->locateName('database.sql') !== false;
        $hasInfo = $zip->locateName('backup-info.json') !== false;
        
        $zip->close();
        
        $this->info('✅ ZIP file is valid');
        $this->info('📊 Contains database: ' . ($hasDatabase ? '✅ Yes' : '❌ No'));
        $this->info('📋 Contains backup info: ' . ($hasInfo ? '✅ Yes' : '❌ No'));
        
        // Check file size (should be reasonable)
        if ($fileSize < 1024) {
            $this->warn('⚠️ Backup file size is too small');
        }
        
        $this->info('🎉 Backup verification completed successfully!');
        return 0;
    }
    
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}