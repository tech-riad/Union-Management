<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class BackupReport extends Command
{
    protected $signature = 'backup:report';
    protected $description = 'Send weekly backup report email';

    public function handle()
    {
        if (!config('backup.notifications.enabled', true)) {
            $this->info('Notifications are disabled.');
            return 0;
        }
        
        $email = config('backup.notifications.email');
        if (!$email) {
            $this->error('No notification email configured.');
            return 1;
        }
        
        $backupPath = storage_path('app/backups');
        $files = is_dir($backupPath) ? glob($backupPath . '/*.zip') : [];
        
        $reportData = [
            'total_backups' => count($files),
            'last_7_days' => $this->countRecentBackups($files, 7),
            'last_30_days' => $this->countRecentBackups($files, 30),
            'total_size' => $this->calculateTotalSize($files),
            'latest_backup' => $this->getLatestBackupInfo($files),
            'report_date' => Carbon::now()->format('F j, Y'),
            'system_status' => $this->getSystemStatus(),
        ];
        
        try {
            Mail::send('emails.backup_report', $reportData, function($message) use ($email) {
                $message->to($email)
                        ->subject('সাপ্তাহিক ব্যাকআপ রিপোর্ট - ' . config('app.name'));
            });
            
            $this->info('✅ Backup report sent to: ' . $email);
            return 0;
            
        } catch (\Exception $e) {
            $this->error('❌ Failed to send report: ' . $e->getMessage());
            return 1;
        }
    }
    
    private function countRecentBackups($files, $days)
    {
        $cutoff = Carbon::now()->subDays($days);
        $count = 0;
        
        foreach ($files as $file) {
            if (Carbon::createFromTimestamp(filemtime($file))->gte($cutoff)) {
                $count++;
            }
        }
        
        return $count;
    }
    
    private function calculateTotalSize($files)
    {
        $total = 0;
        foreach ($files as $file) {
            $total += filesize($file);
        }
        
        return $this->formatBytes($total);
    }
    
    private function getLatestBackupInfo($files)
    {
        if (empty($files)) {
            return 'No backups found';
        }
        
        usort($files, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });
        
        $latest = $files[0];
        return [
            'filename' => basename($latest),
            'size' => $this->formatBytes(filesize($latest)),
            'date' => Carbon::createFromTimestamp(filemtime($latest))->format('Y-m-d H:i:s'),
        ];
    }
    
    private function getSystemStatus()
    {
        return [
            'database' => $this->checkDatabase(),
            'storage' => $this->checkStorage(),
            'backup_dir' => is_dir(storage_path('app/backups')),
        ];
    }
    
    private function checkDatabase()
    {
        try {
            \DB::connection()->getPdo();
            return '✅ Connected';
        } catch (\Exception $e) {
            return '❌ ' . $e->getMessage();
        }
    }
    
    private function checkStorage()
    {
        $free = disk_free_space('/');
        $total = disk_total_space('/');
        $percent = round(($total - $free) / $total * 100, 2);
        
        return "{$percent}% used (" . $this->formatBytes($free) . " free)";
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