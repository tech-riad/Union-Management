<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupTest extends Command
{
    protected $signature = 'backup:test {--type=database : Backup type to test}';
    protected $description = 'Test backup system functionality';

    public function handle()
    {
        $type = $this->option('type');
        
        $this->info('🧪 Starting backup system test...');
        $this->info('📋 Test Type: ' . $type);
        
        // Test 1: Check backup directory
        $backupPath = storage_path('app/backups');
        if (!is_dir($backupPath)) {
            if (!mkdir($backupPath, 0755, true)) {
                $this->error('❌ Failed to create backup directory');
                return 1;
            }
            $this->info('✅ Created backup directory');
        } else {
            $this->info('✅ Backup directory exists');
        }
        
        // Test 2: Check write permissions
        $testFile = $backupPath . '/test.txt';
        if (file_put_contents($testFile, 'test') === false) {
            $this->error('❌ No write permission in backup directory');
            return 1;
        }
        unlink($testFile);
        $this->info('✅ Write permission OK');
        
        // Test 3: Check database connection
        try {
            \DB::connection()->getPdo();
            $this->info('✅ Database connection OK');
        } catch (\Exception $e) {
            $this->error('❌ Database connection failed: ' . $e->getMessage());
            return 1;
        }
        
        // Test 4: Check required PHP extensions
        $requiredExt = ['zip', 'pdo', 'mbstring', 'xml'];
        foreach ($requiredExt as $ext) {
            if (!extension_loaded($ext)) {
                $this->error("❌ Missing PHP extension: {$ext}");
                return 1;
            }
        }
        $this->info('✅ All required PHP extensions loaded');
        
        // Test 5: Create a small test backup
        $this->info('Creating test backup...');
        
        try {
            $filename = 'test-backup-' . date('Y-m-d-His') . '.zip';
            $filepath = $backupPath . '/' . $filename;
            
            $zip = new \ZipArchive();
            if ($zip->open($filepath, \ZipArchive::CREATE) === TRUE) {
                $zip->addFromString('test.txt', 'This is a test backup file.');
                $zip->close();
                
                if (file_exists($filepath)) {
                    $size = filesize($filepath);
                    $this->info("✅ Test backup created: {$filename} ({$this->formatBytes($size)})");
                    
                    // Clean up test backup
                    unlink($filepath);
                    $this->info('✅ Cleaned up test backup');
                } else {
                    $this->error('❌ Test backup file not created');
                    return 1;
                }
            } else {
                $this->error('❌ Failed to create ZIP file');
                return 1;
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Backup test failed: ' . $e->getMessage());
            return 1;
        }
        
        $this->info('🎉 All backup system tests passed!');
        return 0;
    }
    
    private function formatBytes($bytes)
    {
        $units = ['B', 'KB', 'MB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}