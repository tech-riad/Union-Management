<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\BackupLog;

class AutoBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:auto 
                            {--type=all : Backup type (all, database, files)} 
                            {--retention= : Retention days override}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create automatic backup of database and files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info('Starting automatic backup process...');
            
            // Get backup type
            $type = $this->option('type') ?? config('backup.auto_type', 'all');
            
            // Create backup
            $result = $this->createBackup($type);
            
            if ($result['success']) {
                // Log the backup
                $this->logBackup($result);
                
                // Clean old backups
                $this->cleanOldBackups();
                
                $this->info('✅ Backup completed successfully: ' . $result['filename']);
                $this->info('📊 Size: ' . $result['size']);
                $this->info('📍 Path: ' . $result['path']);
                
                return 0;
            } else {
                $this->error('❌ Backup failed: ' . $result['message']);
                return 1;
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Backup error: ' . $e->getMessage());
            return 1;
        }
    }
    
    /**
     * Create backup based on type
     */
    private function createBackup($type)
    {
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $backupPath = storage_path('app/backups');
        
        // Create backup directory if not exists
        if (!is_dir($backupPath)) {
            mkdir($backupPath, 0755, true);
        }
        
        $filename = "auto-backup-{$type}-{$timestamp}.zip";
        $filepath = $backupPath . '/' . $filename;
        
        // Create backup content
        $backupContent = [
            'type' => $type,
            'created_at' => Carbon::now()->toDateTimeString(),
            'database' => config('database.connections.mysql.database'),
            'app_version' => config('app.version', '1.0.0'),
            'laravel_version' => app()->version(),
        ];
        
        switch ($type) {
            case 'database':
                $backupContent['data'] = $this->backupDatabase();
                break;
                
            case 'files':
                $backupContent['files'] = $this->backupImportantFiles();
                break;
                
            case 'all':
                $backupContent['database'] = $this->backupDatabase();
                $backupContent['files'] = $this->backupImportantFiles();
                break;
        }
        
        // Create zip file
        $zip = new \ZipArchive();
        if ($zip->open($filepath, \ZipArchive::CREATE) === TRUE) {
            // Add backup info
            $zip->addFromString('backup-info.json', json_encode($backupContent, JSON_PRETTY_PRINT));
            
            // Add database dump if exists
            if (isset($backupContent['database'])) {
                $zip->addFromString('database.sql', $backupContent['database']);
            }
            
            // Add files if exists
            if (isset($backupContent['files']) && is_array($backupContent['files'])) {
                foreach ($backupContent['files'] as $filePath => $fileContent) {
                    $zip->addFromString('files/' . basename($filePath), $fileContent);
                }
            }
            
            $zip->close();
        }
        
        return [
            'success' => true,
            'filename' => $filename,
            'path' => $filepath,
            'size' => $this->formatBytes(filesize($filepath)),
            'type' => $type,
            'created_at' => Carbon::now()
        ];
    }
    
    /**
     * Backup database
     */
    private function backupDatabase()
    {
        $database = config('database.connections.mysql.database');
        $tables = DB::select('SHOW TABLES');
        
        $sql = "-- Database Backup\n";
        $sql .= "-- Generated: " . Carbon::now()->toDateTimeString() . "\n";
        $sql .= "-- Database: {$database}\n\n";
        
        foreach ($tables as $table) {
            $tableName = $table->{'Tables_in_' . $database};
            
            // Get table structure
            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
            $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
            $sql .= $createTable[0]->{'Create Table'} . ";\n\n";
            
            // Get table data
            $rows = DB::table($tableName)->get();
            if ($rows->count() > 0) {
                $sql .= "-- Data for table `{$tableName}`\n";
                $sql .= "INSERT INTO `{$tableName}` VALUES ";
                
                $values = [];
                foreach ($rows as $row) {
                    $rowValues = [];
                    foreach ((array)$row as $value) {
                        $rowValues[] = is_null($value) ? 'NULL' : "'" . addslashes($value) . "'";
                    }
                    $values[] = '(' . implode(', ', $rowValues) . ')';
                }
                
                $sql .= implode(",\n", $values) . ";\n\n";
            }
        }
        
        return $sql;
    }
    
    /**
     * Backup important files
     */
    private function backupImportantFiles()
    {
        $files = [
            '.env' => base_path('.env'),
            'app/Models' => base_path('app/Models'),
            'app/Http/Controllers' => base_path('app/Http/Controllers'),
            'database/migrations' => base_path('database/migrations'),
            'database/seeders' => base_path('database/seeders'),
            'config' => base_path('config'),
        ];
        
        $backupFiles = [];
        
        foreach ($files as $name => $path) {
            if (is_dir($path)) {
                $filesInDir = glob($path . '/*.php');
                foreach ($filesInDir as $file) {
                    if (file_exists($file)) {
                        $backupFiles[$name . '/' . basename($file)] = file_get_contents($file);
                    }
                }
            } elseif (file_exists($path)) {
                $backupFiles[$name] = file_get_contents($path);
            }
        }
        
        return $backupFiles;
    }
    
    /**
     * Log backup to database
     */
    private function logBackup($backupInfo)
    {
        try {
            if (!class_exists('App\\Models\\BackupLog')) {
                // Create backup_logs table migration if not exists
                return;
            }
            
            BackupLog::create([
                'filename' => $backupInfo['filename'],
                'type' => $backupInfo['type'],
                'size' => $backupInfo['size'],
                'path' => $backupInfo['path'],
                'status' => 'completed',
                'created_at' => $backupInfo['created_at']
            ]);
        } catch (\Exception $e) {
            // Silently fail if logging fails
        }
    }
    
    /**
     * Clean old backups based on retention policy
     */
    private function cleanOldBackups()
    {
        $retentionDays = $this->option('retention') ?? config('backup.retention_days', 30);
        $backupPath = storage_path('app/backups');
        
        if (!is_dir($backupPath)) {
            return;
        }
        
        $files = glob($backupPath . '/*.zip');
        $cutoffDate = Carbon::now()->subDays($retentionDays);
        
        foreach ($files as $file) {
            $fileTime = Carbon::createFromTimestamp(filemtime($file));
            if ($fileTime->lessThan($cutoffDate)) {
                unlink($file);
                $this->info('🗑️ Deleted old backup: ' . basename($file));
            }
        }
    }
    
    /**
     * Format bytes to human readable format
     */
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