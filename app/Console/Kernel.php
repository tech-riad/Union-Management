<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     */
    protected $commands = [
        // TCPDF commands
        Commands\FixTCPDF::class,
        Commands\FixTCPDFNFont::class,
        Commands\ReinstallTCPDFFonts::class,
        
        // Other commands
        Commands\CheckFonts::class,
        Commands\SetupTCPDF::class,
        
        // ✅ Backup commands
        Commands\AutoBackup::class,
        Commands\BackupClean::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // ============================================
        // ✅ AUTO BACKUP SCHEDULE
        // ============================================
        
        // Get backup settings from config
        $backupSchedule = config('backup.auto_backup.schedule', 'daily');
        $backupTime = config('backup.auto_backup.time', '02:00');
        $backupType = config('backup.auto_backup.type', 'database');
        $retentionDays = config('backup.retention_days', 30);
        
        // Schedule auto backup based on configuration
        switch ($backupSchedule) {
            case 'hourly':
                $schedule->command("backup:auto --type={$backupType}")
                         ->hourly()
                         ->description('Auto Hourly Backup')
                         ->onOneServer();
                break;
                
            case 'daily':
                $schedule->command("backup:auto --type={$backupType}")
                         ->dailyAt($backupTime)
                         ->description('Auto Daily Backup')
                         ->onOneServer();
                break;
                
            case 'weekly':
                $schedule->command("backup:auto --type={$backupType}")
                         ->weekly()
                         ->mondays()
                         ->at($backupTime)
                         ->description('Auto Weekly Backup')
                         ->onOneServer();
                break;
                
            case 'monthly':
                $schedule->command("backup:auto --type={$backupType}")
                         ->monthlyOn(1, $backupTime)
                         ->description('Auto Monthly Backup')
                         ->onOneServer();
                break;
                
            default:
                $schedule->command("backup:auto --type={$backupType}")
                         ->dailyAt('02:00')
                         ->description('Auto Daily Backup (Default)')
                         ->onOneServer();
        }
        
        // ============================================
        // ✅ CLEAN OLD BACKUPS (Weekly)
        // ============================================
        $schedule->command("backup:clean --days={$retentionDays}")
                 ->weekly()
                 ->sundays()
                 ->at('03:00')
                 ->description('Clean Old Backups')
                 ->onOneServer();
        
        // ============================================
        // ✅ DATABASE OPTIMIZATION (Monthly)
        // ============================================
        $schedule->command('optimize:clear')
                 ->monthly()
                 ->description('Clear Laravel Cache Monthly');
                 
        $schedule->command('queue:prune-batches --hours=48')
                 ->daily()
                 ->description('Prune Old Queue Batches');
        
        // ============================================
        // ✅ APPLICATION HEALTH CHECKS (Daily)
        // ============================================
        $schedule->command('system:check')
                 ->dailyAt('01:00')
                 ->description('System Health Check');
                 
        $schedule->command('log:clear')
                 ->weekly()
                 ->saturdays()
                 ->at('04:00')
                 ->description('Clear Old Log Files');
        
        // ============================================
        // ✅ BACKUP VERIFICATION (Daily - After Backup)
        // ============================================
        $schedule->command('backup:verify')
                 ->dailyAt('03:30')
                 ->description('Verify Latest Backup')
                 ->onOneServer();
        
        // ============================================
        // ✅ SEND BACKUP REPORTS (Weekly)
        // ============================================
        if (config('backup.notifications.enabled', true)) {
            $schedule->command('backup:report')
                     ->weekly()
                     ->mondays()
                     ->at('09:00')
                     ->description('Send Backup Weekly Report');
        }
        
        // ============================================
        // ✅ TEST BACKUP (Weekly - For Monitoring)
        // ============================================
        $schedule->command('backup:test')
                 ->weekly()
                 ->fridays()
                 ->at('14:00')
                 ->description('Test Backup System')
                 ->onOneServer();
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