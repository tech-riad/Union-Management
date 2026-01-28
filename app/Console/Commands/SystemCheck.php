<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SystemCheck extends Command
{
    protected $signature = 'system:check';
    protected $description = 'Perform system health check';

    public function handle()
    {
        $this->info('🏥 Performing system health check...');
        
        $checks = [
            'Database Connection' => $this->checkDatabase(),
            'Storage Writable' => $this->checkStorage(),
            'Backup Directory' => $this->checkBackupDir(),
            'PHP Version' => $this->checkPHPVersion(),
            'Required Extensions' => $this->checkExtensions(),
            'Memory Limit' => $this->checkMemory(),
        ];
        
        $this->table(['Check', 'Status', 'Details'], $checks);
        
        $failed = collect($checks)->where('Status', '❌')->count();
        
        if ($failed > 0) {
            $this->error("❌ {$failed} checks failed!");
            return 1;
        }
        
        $this->info('✅ All system checks passed!');
        return 0;
    }
    
    private function checkDatabase()
    {
        try {
            \DB::connection()->getPdo();
            return ['Status' => '✅', 'Details' => 'Connected'];
        } catch (\Exception $e) {
            return ['Status' => '❌', 'Details' => $e->getMessage()];
        }
    }
    
    private function checkStorage()
    {
        $paths = [
            storage_path(),
            storage_path('app'),
            storage_path('logs'),
            storage_path('framework'),
        ];
        
        $unwritable = [];
        foreach ($paths as $path) {
            if (!is_writable($path)) {
                $unwritable[] = basename($path);
            }
        }
        
        if (empty($unwritable)) {
            return ['Status' => '✅', 'Details' => 'All storage paths writable'];
        }
        
        return ['Status' => '❌', 'Details' => 'Not writable: ' . implode(', ', $unwritable)];
    }
    
    private function checkBackupDir()
    {
        $backupPath = storage_path('app/backups');
        
        if (!is_dir($backupPath)) {
            if (!mkdir($backupPath, 0755, true)) {
                return ['Status' => '❌', 'Details' => 'Failed to create directory'];
            }
            return ['Status' => '⚠️', 'Details' => 'Created backup directory'];
        }
        
        if (!is_writable($backupPath)) {
            return ['Status' => '❌', 'Details' => 'Backup directory not writable'];
        }
        
        return ['Status' => '✅', 'Details' => 'OK'];
    }
    
    private function checkPHPVersion()
    {
        $required = '7.4';
        $current = phpversion();
        
        if (version_compare($current, $required, '>=')) {
            return ['Status' => '✅', 'Details' => $current];
        }
        
        return ['Status' => '❌', 'Details' => "{$current} (Required: {$required}+)"];
    }
    
    private function checkExtensions()
    {
        $required = ['pdo', 'mbstring', 'xml', 'zip', 'curl', 'json'];
        $missing = [];
        
        foreach ($required as $ext) {
            if (!extension_loaded($ext)) {
                $missing[] = $ext;
            }
        }
        
        if (empty($missing)) {
            return ['Status' => '✅', 'Details' => 'All required extensions loaded'];
        }
        
        return ['Status' => '❌', 'Details' => 'Missing: ' . implode(', ', $missing)];
    }
    
    private function checkMemory()
    {
        $limit = ini_get('memory_limit');
        $bytes = $this->convertToBytes($limit);
        $minBytes = 128 * 1024 * 1024; // 128MB
        
        if ($bytes >= $minBytes) {
            return ['Status' => '✅', 'Details' => $limit];
        }
        
        return ['Status' => '⚠️', 'Details' => "{$limit} (Recommended: 128M+)"];
    }
    
    private function convertToBytes($memory)
    {
        $unit = strtolower(substr($memory, -1));
        $value = (int) substr($memory, 0, -1);
        
        switch ($unit) {
            case 'g': $value *= 1024;
            case 'm': $value *= 1024;
            case 'k': $value *= 1024;
        }
        
        return $value;
    }
}