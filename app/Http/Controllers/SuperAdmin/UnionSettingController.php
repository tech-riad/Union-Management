<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\UnionSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class UnionSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:super_admin');
    }

    /**
     * Show union settings form
     */
    public function index()
    {
        $settings = UnionSetting::getSettings();
        return view('super_admin.settings.union', compact('settings'));
    }

    /**
     * Update union settings
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'union_name' => 'required|string|max:255',
            'union_name_bangla' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'emergency_number' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:500',
            'address_bangla' => 'required|string|max:500',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png|max:512',
            'chairman_signature' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
            'chairman_seal' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
            'secretary_signature' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
            'website' => 'nullable|url|max:255',
            'facebook' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'office_start_time' => 'required|date_format:H:i',
            'office_end_time' => 'required|date_format:H:i',
            'working_days' => 'required|string|max:100',
            'chairman_name' => 'required|string|max:255',
            'chairman_phone' => 'required|string|max:20',
            'secretary_name' => 'required|string|max:255',
            'secretary_phone' => 'required|string|max:20',
            'about_us' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'privacy_policy' => 'nullable|string',
            'primary_color' => 'nullable|string|max:7|starts_with:#',
            'secondary_color' => 'nullable|string|max:7|starts_with:#',
            'currency' => 'required|string|max:10',
            'timezone' => 'required|string|max:50',
            'date_format' => 'required|string|max:20',
            'time_format' => 'required|string|max:20',
        ], [
            'primary_color.starts_with' => 'প্রাইমারি কালার # দিয়ে শুরু করতে হবে (যেমন: #3b82f6)',
            'secondary_color.starts_with' => 'সেকেন্ডারি কালার # দিয়ে শুরু করতে হবে',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'কিছু তথ্য ভুল হয়েছে। দয়া করে চেক করুন।');
        }

        $settings = UnionSetting::first();
        
        if (!$settings) {
            $settings = new UnionSetting();
        }

        $data = $request->except(['logo', 'favicon', 'chairman_signature', 'chairman_seal', 'secretary_signature']);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $data['logo'] = $this->uploadImage($request->file('logo'), 'logos', $settings->logo, 300, 300);
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            $data['favicon'] = $this->uploadImage($request->file('favicon'), 'favicons', $settings->favicon, 64, 64);
        }

        // Handle chairman signature upload
        if ($request->hasFile('chairman_signature')) {
            $data['chairman_signature'] = $this->uploadImage($request->file('chairman_signature'), 'signatures', $settings->chairman_signature, 200, 100, false);
        }

        // Handle chairman seal upload
        if ($request->hasFile('chairman_seal')) {
            $data['chairman_seal'] = $this->uploadImage($request->file('chairman_seal'), 'seals', $settings->chairman_seal, 150, 150);
        }

        // Handle secretary signature upload
        if ($request->hasFile('secretary_signature')) {
            $data['secretary_signature'] = $this->uploadImage($request->file('secretary_signature'), 'signatures', $settings->secretary_signature, 200, 100, false);
        }

        // Convert maintenance mode checkbox
        $data['maintenance_mode'] = $request->has('maintenance_mode');

        $settings->fill($data);
        $settings->save();

        return redirect()->route('super_admin.settings.union')
            ->with('success', 'ইউনিয়ন সেটিংস সফলভাবে আপডেট করা হয়েছে!');
    }

    /**
     * Upload and process image
     */
    private function uploadImage($file, $folder, $oldFile = null, $width = null, $height = null, $resize = true)
    {
        // Delete old file if exists
        if ($oldFile && file_exists(public_path('storage/' . $oldFile))) {
            unlink(public_path('storage/' . $oldFile));
        }

        $filename = $folder . '-' . time() . '.' . $file->getClientOriginalExtension();
        $path = $folder . '/' . $filename;
        
        // Create directory if not exists
        $directory = public_path('storage/' . $folder);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Process image
        $image = Image::make($file);
        
        if ($resize && $width && $height) {
            $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }
        
        $image->save(public_path('storage/' . $path));

        return $path;
    }

    /**
     * Delete a specific image
     */
    public function deleteImage(Request $request)
    {
        $request->validate([
            'type' => 'required|in:logo,favicon,chairman_signature,chairman_seal,secretary_signature'
        ]);

        $settings = UnionSetting::first();
        
        if (!$settings) {
            return response()->json(['error' => 'সেটিংস পাওয়া যায়নি'], 404);
        }

        $type = $request->type;
        $field = $type;
        
        if ($settings->$field && file_exists(public_path('storage/' . $settings->$field))) {
            unlink(public_path('storage/' . $settings->$field));
            $settings->$field = null;
            $settings->save();
        }

        return response()->json(['success' => 'ছবি ডিলিট করা হয়েছে']);
    }

    /**
     * Reset to default settings
     */
    public function reset()
    {
        $settings = UnionSetting::first();
        
        if ($settings) {
            // Delete all uploaded files
            $files = [
                'logo' => $settings->logo,
                'favicon' => $settings->favicon,
                'chairman_signature' => $settings->chairman_signature,
                'chairman_seal' => $settings->chairman_seal,
                'secretary_signature' => $settings->secretary_signature,
            ];
            
            foreach ($files as $file) {
                if ($file && file_exists(public_path('storage/' . $file))) {
                    unlink(public_path('storage/' . $file));
                }
            }
            
            $settings->delete();
        }

        return redirect()->route('super_admin.settings.union')
            ->with('success', 'সকল সেটিংস ডিফল্টে রিসেট করা হয়েছে');
    }

    // ============================================
    // ✅ BACKUP SETTINGS METHODS
    // ============================================

    /**
     * Show backup settings form
     */
    public function backupSettings()
    {
        try {
            // Get backup settings from .env or config
            $backupSettings = $this->getBackupSettings();
            
            // Get backup statistics
            $backupStats = $this->getBackupStatistics();
            
            // Merge all data
            $data = array_merge($backupSettings, $backupStats);
            
            // ✅ Add default settings if any keys are missing
            $defaultSettings = [
                'auto_backup_enabled' => true,
                'schedule' => 'daily',
                'backup_time' => '02:00',
                'backup_type' => 'database',
                'retention_days' => 30,
                'max_backups' => 10,
                'notifications_enabled' => true,
                'notification_email' => 'admin@example.com',
                'backup_path' => 'backups',
                'backup_count' => 0,
                'last_backup_size' => '0 MB',
                'last_backup_time' => 'কোনো ব্যাকআপ নেই',
                'total_backup_size' => '0 MB',
                'available_space' => '0 GB',
                'used_percentage' => 0,
                'next_backup' => 'অজানা',
            ];
            
            // Merge with defaults to ensure all keys exist
            $data = array_merge($defaultSettings, $data);
            
            return view('super_admin.settings.backup', $data);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'ব্যাকআপ সেটিংস লোড করতে সমস্যা: ' . $e->getMessage());
        }
    }

    /**
     * Update backup settings
     */
    public function updateBackupSettings(Request $request)
    {
        try {
            $validated = $request->validate([
                'auto_backup_enabled' => 'boolean',
                'schedule' => 'required|in:hourly,daily,weekly,monthly',
                'backup_time' => 'required|date_format:H:i',
                'backup_type' => 'required|in:all,database,files',
                'retention_days' => 'required|integer|min:1|max:365',
                'max_backups' => 'required|integer|min:1|max:100',
                'notifications_enabled' => 'boolean',
                'notification_email' => 'nullable|email',
                'backup_path' => 'nullable|string|max:500',
            ]);
            
            // Update .env file
            $this->updateBackupConfig($validated);
            
            // Clear config cache
            \Artisan::call('config:clear');
            
            return redirect()->back()->with('success', 'ব্যাকআপ সেটিংস সফলভাবে আপডেট হয়েছে');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'সেটিংস আপডেট ব্যর্থ: ' . $e->getMessage());
        }
    }

    /**
     * Test backup functionality
     */
    public function testBackup(Request $request)
    {
        try {
            \Log::info('Test backup requested', $request->all());
            
            $request->validate([
                'type' => 'required|in:database,full,all'
            ]);
            
            $type = $request->type;
            
            \Log::info('Starting test backup', ['type' => $type]);
            
            // First try custom backup method (without mysqldump dependency)
            try {
                \Log::info('Trying custom backup method...');
                return $this->runCustomBackup($type);
            } catch (\Exception $e) {
                \Log::warning('Custom backup failed, trying spatie backup', ['error' => $e->getMessage()]);
                
                // Fallback to spatie backup if available
                try {
                    return $this->runSpatieBackup($type);
                } catch (\Exception $spatieError) {
                    \Log::error('All backup methods failed', ['error' => $spatieError->getMessage()]);
                    
                    return response()->json([
                        'success' => false,
                        'message' => 'সকল ব্যাকআপ পদ্ধতি ব্যর্থ। দয়া করে ম্যানুয়ালি চেক করুন: ১) XAMPP MySQL ইনস্টল করা আছে কিনা, ২) config/backup.php ফাইল সঠিক কিনা।',
                        'log' => 'Custom backup error: ' . $e->getMessage() . '\nSpatie backup error: ' . $spatieError->getMessage()
                    ], 500);
                }
            }
            
        } catch (\Exception $e) {
            \Log::error('Test backup failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'টেস্ট ব্যাকআপ ব্যর্থ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Run custom backup without mysqldump dependency
     */
    private function runCustomBackup($type)
    {
        $backupDir = storage_path('app/backups');
        
        // Ensure backup directory exists
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }
        
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $output = [];
        
        if ($type === 'database' || $type === 'full' || $type === 'all') {
            \Log::info('Creating database backup...');
            
            // Create database backup using Laravel query method
            $sqlFile = $backupDir . "/database_{$timestamp}.sql";
            $sqlContent = $this->generateDatabaseBackup();
            
            if (file_put_contents($sqlFile, $sqlContent)) {
                $output[] = "✅ Database backup created: " . basename($sqlFile) . " (" . $this->formatBytes(filesize($sqlFile)) . ")";
            } else {
                throw new \Exception('Database backup file creation failed');
            }
        }
        
        if ($type === 'full' || $type === 'all') {
            \Log::info('Creating files backup...');
            
            // Create zip of important files
            $zipFile = $backupDir . "/files_{$timestamp}.zip";
            $zipCreated = $this->createFilesBackup($zipFile);
            
            if ($zipCreated) {
                $output[] = "✅ Files backup created: " . basename($zipFile) . " (" . $this->formatBytes(filesize($zipFile)) . ")";
            } else {
                $output[] = "⚠️ Files backup creation partially failed";
            }
        }
        
        // Check if any backup was created
        $files = glob($backupDir . '/*.{sql,zip}', GLOB_BRACE);
        
        if (!empty($files)) {
            usort($files, function($a, $b) {
                return filemtime($b) - filemtime($a);
            });
            
            $latest = basename($files[0]);
            $size = $this->formatBytes(filesize($files[0]));
            
            return response()->json([
                'success' => true,
                'message' => 'টেস্ট ব্যাকআপ সফলভাবে তৈরি হয়েছে',
                'filename' => $latest,
                'size' => $size,
                'log' => implode("\n", $output)
            ]);
        }
        
        throw new \Exception('No backup files were created');
    }

    /**
     * Generate database backup using Laravel queries
     */
    private function generateDatabaseBackup()
    {
        $sqlContent = "-- Union Management System Database Backup\n";
        $sqlContent .= "-- Generated: " . Carbon::now()->toDateTimeString() . "\n";
        $sqlContent .= "-- Database: " . config('database.connections.mysql.database') . "\n\n";
        
        // Get all tables
        $tables = DB::select('SHOW TABLES');
        
        foreach ($tables as $table) {
            $tableName = array_values((array)$table)[0];
            
            // Skip migrations table if it's too large
            if ($tableName === 'migrations') {
                continue;
            }
            
            // Get table structure
            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
            $createStatement = $createTable[0]->{'Create Table'};
            
            $sqlContent .= "--\n-- Table structure for table `{$tableName}`\n--\n";
            $sqlContent .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
            $sqlContent .= $createStatement . ";\n\n";
            
            // Get table data
            try {
                $rows = DB::table($tableName)->get();
                
                if ($rows->count() > 0) {
                    $sqlContent .= "--\n-- Dumping data for table `{$tableName}`\n--\n";
                    
                    $chunkSize = 1000;
                    $totalRows = $rows->count();
                    
                    for ($i = 0; $i < $totalRows; $i += $chunkSize) {
                        $chunk = DB::table($tableName)
                            ->skip($i)
                            ->take($chunkSize)
                            ->get();
                        
                        $sqlContent .= "INSERT INTO `{$tableName}` VALUES\n";
                        
                        $insertValues = [];
                        foreach ($chunk as $row) {
                            $values = [];
                            foreach ((array)$row as $value) {
                                if ($value === null) {
                                    $values[] = 'NULL';
                                } else {
                                    // Escape special characters
                                    $value = str_replace("'", "''", $value);
                                    $value = str_replace("\\", "\\\\", $value);
                                    $values[] = "'" . $value . "'";
                                }
                            }
                            $insertValues[] = '(' . implode(', ', $values) . ')';
                        }
                        
                        $sqlContent .= implode(",\n", $insertValues) . ";\n\n";
                    }
                }
            } catch (\Exception $e) {
                $sqlContent .= "-- Error dumping data for table `{$tableName}`: " . $e->getMessage() . "\n\n";
            }
        }
        
        return $sqlContent;
    }

    /**
     * Create files backup as zip
     */
    private function createFilesBackup($zipFile)
    {
        if (!class_exists('ZipArchive')) {
            \Log::warning('ZipArchive class not available');
            return false;
        }
        
        $zip = new \ZipArchive();
        
        if ($zip->open($zipFile, \ZipArchive::CREATE) !== TRUE) {
            \Log::error('Cannot open zip file: ' . $zipFile);
            return false;
        }
        
        // Important directories to backup
        $directories = [
            base_path('app'),
            base_path('config'),
            base_path('database'),
            base_path('routes'),
        ];
        
        // Important files to backup
        $files = [
            base_path('.env'),
            base_path('composer.json'),
            base_path('package.json'),
            base_path('artisan'),
        ];
        
        $addedFiles = 0;
        
        // Add directories
        foreach ($directories as $directory) {
            if (is_dir($directory)) {
                $addedFiles += $this->addDirectoryToZip($zip, $directory, basename($directory));
            }
        }
        
        // Add individual files
        foreach ($files as $file) {
            if (file_exists($file)) {
                $zip->addFile($file, basename($file));
                $addedFiles++;
            }
        }
        
        $zip->close();
        
        return $addedFiles > 0 && file_exists($zipFile);
    }

    /**
     * Add directory to zip archive
     */
    private function addDirectoryToZip($zip, $directory, $zipPath)
    {
        $added = 0;
        
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $filePath = $file->getRealPath();
                $relativePath = $zipPath . '/' . $iterator->getSubPathname();
                
                // Skip large/unnecessary files
                if ($file->getSize() > 10485760) { // 10MB limit
                    continue;
                }
                
                // Skip backup files
                if (strpos($filePath, 'backups') !== false) {
                    continue;
                }
                
                $zip->addFile($filePath, $relativePath);
                $added++;
            }
        }
        
        return $added;
    }

    /**
     * Run spatie backup (if package is installed)
     */
    private function runSpatieBackup($type)
    {
        \Log::info('Trying spatie backup package');
        
        $basePath = base_path();
        $mysqlDumpPath = config('backup.windows_xampp.mysqldump_exe', 'C:/xampp/mysql/bin/mysqldump.exe');
        
        // Check if mysqldump exists
        if (!file_exists($mysqlDumpPath)) {
            throw new \Exception('mysqldump not found at: ' . $mysqlDumpPath);
        }
        
        // Set environment variable for mysqldump path
        putenv('MYSQLDUMP_PATH=' . dirname($mysqlDumpPath));
        
        $output = [];
        $returnVar = 0;
        
        $command = "cd {$basePath} && php artisan backup:run";
        
        if ($type === 'database') {
            $command .= ' --only-db';
        } elseif ($type === 'files') {
            $command .= ' --only-files';
        }
        
        $command .= ' --disable-notifications 2>&1';
        
        \Log::info('Executing spatie backup command', ['command' => $command]);
        
        exec($command, $output, $returnVar);
        
        \Log::info('Spatie backup result', [
            'returnVar' => $returnVar,
            'output' => $output
        ]);
        
        if ($returnVar === 0) {
            $log = implode("\n", $output);
            
            // Check if backup was created
            $backupPath = storage_path('app/backups');
            $files = glob($backupPath . '/*.zip');
            
            if (!empty($files)) {
                usort($files, function($a, $b) {
                    return filemtime($b) - filemtime($a);
                });
                
                $latest = basename($files[0]);
                $size = $this->formatBytes(filesize($files[0]));
                
                return response()->json([
                    'success' => true,
                    'message' => 'টেস্ট ব্যাকআপ সফলভাবে তৈরি হয়েছে (Spatie Package)',
                    'filename' => $latest,
                    'size' => $size,
                    'log' => $log
                ]);
            }
        }
        
        throw new \Exception('Spatie backup failed: ' . implode("\n", $output));
    }

    /**
     * Get backup settings from .env and config
     */
    private function getBackupSettings()
    {
        return [
            'auto_backup_enabled' => env('BACKUP_AUTO_ENABLED', true),
            'schedule' => env('BACKUP_SCHEDULE', 'daily'),
            'backup_time' => env('BACKUP_TIME', '02:00'),
            'backup_type' => env('BACKUP_TYPE', 'database'),
            'retention_days' => env('BACKUP_RETENTION_DAYS', 30),
            'max_backups' => env('BACKUP_MAX_COUNT', 10),
            'notifications_enabled' => env('BACKUP_NOTIFICATIONS', true),
            'notification_email' => env('BACKUP_NOTIFICATION_EMAIL', 'admin@example.com'),
            'backup_path' => env('BACKUP_PATH', 'backups'),
        ];
    }

    /**
     * Get backup statistics
     */
    private function getBackupStatistics()
    {
        $backupPath = storage_path('app/backups');
        
        // Also check alternative path from env
        $alternativePath = storage_path('app/' . env('BACKUP_PATH', 'backups'));
        
        // Use alternative path if it exists
        if (is_dir($alternativePath) && !is_dir($backupPath)) {
            $backupPath = $alternativePath;
        }
        
        // Ensure backup directory exists
        if (!is_dir($backupPath)) {
            mkdir($backupPath, 0755, true);
        }
        
        $files = glob($backupPath . '/*.{sql,zip}', GLOB_BRACE);
        
        // Total backups count
        $backupCount = count($files);
        
        // Latest backup info
        $lastBackupSize = '0 MB';
        $lastBackupTime = 'কোনো ব্যাকআপ নেই';
        
        if ($backupCount > 0) {
            usort($files, function($a, $b) {
                return filemtime($b) - filemtime($a);
            });
            
            $latest = $files[0];
            $lastBackupSize = $this->formatBytes(filesize($latest));
            $lastBackupTime = Carbon::createFromTimestamp(filemtime($latest))
                ->setTimezone('Asia/Dhaka')
                ->diffForHumans();
        }
        
        // Storage info
        try {
            $totalSpace = disk_total_space('/');
            $freeSpace = disk_free_space('/');
            $usedSpace = $totalSpace - $freeSpace;
            $usedPercentage = $totalSpace > 0 ? round(($usedSpace / $totalSpace) * 100, 2) : 0;
            $availableSpace = $this->formatBytes($freeSpace);
        } catch (\Exception $e) {
            $usedPercentage = 0;
            $availableSpace = '0 GB';
        }
        
        // Calculate next backup time
        $nextBackup = $this->calculateNextBackupTime();
        
        // Calculate total backup size
        $totalBackupSize = 0;
        foreach ($files as $file) {
            $totalBackupSize += filesize($file);
        }
        
        return [
            'backup_count' => $backupCount,
            'last_backup_size' => $lastBackupSize,
            'last_backup_time' => $lastBackupTime,
            'total_backup_size' => $this->formatBytes($totalBackupSize),
            'available_space' => $availableSpace,
            'used_percentage' => $usedPercentage,
            'next_backup' => $nextBackup,
        ];
    }

    /**
     * Update backup configuration in .env file
     */
    private function updateBackupConfig($settings)
    {
        $envPath = base_path('.env');
        
        if (!file_exists($envPath)) {
            throw new \Exception('.env ফাইল পাওয়া যায়নি');
        }
        
        $envContent = file_get_contents($envPath);
        
        // Update each setting
        $envUpdates = [
            'BACKUP_AUTO_ENABLED' => isset($settings['auto_backup_enabled']) && $settings['auto_backup_enabled'] ? 'true' : 'false',
            'BACKUP_SCHEDULE' => $settings['schedule'] ?? 'daily',
            'BACKUP_TIME' => $settings['backup_time'] ?? '02:00',
            'BACKUP_TYPE' => $settings['backup_type'] ?? 'database',
            'BACKUP_RETENTION_DAYS' => $settings['retention_days'] ?? 30,
            'BACKUP_MAX_COUNT' => $settings['max_backups'] ?? 10,
            'BACKUP_NOTIFICATIONS' => isset($settings['notifications_enabled']) && $settings['notifications_enabled'] ? 'true' : 'false',
            'BACKUP_NOTIFICATION_EMAIL' => $settings['notification_email'] ?? 'admin@example.com',
            'BACKUP_PATH' => $settings['backup_path'] ?? 'backups',
        ];
        
        foreach ($envUpdates as $key => $value) {
            // Escape special characters
            $value = str_replace(['\\', '$'], ['\\\\', '\$'], $value);
            
            // Check if the key already exists
            if (preg_match("/^{$key}=.*/m", $envContent)) {
                $envContent = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $envContent);
            } else {
                // Add new key at the end
                $envContent .= "\n{$key}={$value}";
            }
        }
        
        file_put_contents($envPath, $envContent);
    }

    /**
     * Calculate next backup time based on schedule
     */
    private function calculateNextBackupTime()
    {
        try {
            $schedule = env('BACKUP_SCHEDULE', 'daily');
            $time = env('BACKUP_TIME', '02:00');
            
            $now = Carbon::now()->setTimezone('Asia/Dhaka');
            $next = Carbon::now()->setTimezone('Asia/Dhaka');
            
            switch ($schedule) {
                case 'hourly':
                    $next->addHour()->minute(0)->second(0);
                    break;
                    
                case 'daily':
                    [$hour, $minute] = explode(':', $time);
                    $next->setTime($hour, $minute, 0);
                    if ($next->lt($now)) {
                        $next->addDay();
                    }
                    break;
                    
                case 'weekly':
                    [$hour, $minute] = explode(':', $time);
                    $next->next(Carbon::MONDAY)->setTime($hour, $minute, 0);
                    break;
                    
                case 'monthly':
                    [$hour, $minute] = explode(':', $time);
                    $next->firstOfMonth()->addMonth()->setTime($hour, $minute, 0);
                    break;
                    
                default:
                    return 'অজানা';
            }
            
            return $next->diffForHumans() . ' (' . $next->format('d/m/Y H:i') . ')';
        } catch (\Exception $e) {
            return 'গণনা করতে ব্যর্থ';
        }
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        if ($bytes <= 0) {
            return '0 B';
        }
        
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Get backup logs (last 10 backups)
     */
    public function getBackupLogs(Request $request)
    {
        try {
            $backupPath = storage_path('app/backups');
            
            // Also check alternative path
            $alternativePath = storage_path('app/' . env('BACKUP_PATH', 'backups'));
            
            // Use alternative path if it exists
            if (is_dir($alternativePath) && !is_dir($backupPath)) {
                $backupPath = $alternativePath;
            }
            
            // Ensure directory exists
            if (!is_dir($backupPath)) {
                mkdir($backupPath, 0755, true);
                return response()->json([
                    'success' => true,
                    'logs' => [],
                    'total' => 0,
                    'message' => 'ব্যাকআপ ডিরেক্টরি তৈরি হয়েছে'
                ]);
            }
            
            $files = glob($backupPath . '/*.{sql,zip}', GLOB_BRACE);
            
            $logs = [];
            foreach ($files as $file) {
                $logs[] = [
                    'filename' => basename($file),
                    'size' => $this->formatBytes(filesize($file)),
                    'modified' => Carbon::createFromTimestamp(filemtime($file))
                        ->setTimezone('Asia/Dhaka')
                        ->format('d/m/Y H:i:s'),
                    'time_ago' => Carbon::createFromTimestamp(filemtime($file))
                        ->setTimezone('Asia/Dhaka')
                        ->diffForHumans(),
                    'type' => $this->getBackupType(basename($file)),
                ];
            }
            
            // Sort by modification time (newest first)
            usort($logs, function($a, $b) {
                return strtotime($b['modified']) - strtotime($a['modified']);
            });
            
            // Limit to last 10 logs
            $logs = array_slice($logs, 0, 10);
            
            return response()->json([
                'success' => true,
                'logs' => $logs,
                'total' => count($files)
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Get backup logs failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'লগ লোড করতে সমস্যা: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Determine backup type from filename
     */
    private function getBackupType($filename)
    {
        if (strpos($filename, 'database') !== false || strpos($filename, '.sql') !== false) {
            return 'database';
        } elseif (strpos($filename, 'files') !== false || strpos($filename, '.zip') !== false) {
            return 'files';
        } elseif (strpos($filename, 'full') !== false) {
            return 'full';
        } elseif (strpos($filename, 'auto') !== false) {
            return 'auto';
        } else {
            return 'unknown';
        }
    }

    /**
     * Run backup cleanup manually
     */
    public function runBackupCleanup(Request $request)
    {
        try {
            $days = $request->get('days', 30);
            
            \Log::info('Running backup cleanup', ['days' => $days]);
            
            $backupPath = storage_path('app/backups');
            $alternativePath = storage_path('app/' . env('BACKUP_PATH', 'backups'));
            
            // Use alternative path if it exists
            if (is_dir($alternativePath) && !is_dir($backupPath)) {
                $backupPath = $alternativePath;
            }
            
            if (!is_dir($backupPath)) {
                return response()->json([
                    'success' => true,
                    'message' => 'ব্যাকআপ ডিরেক্টরি নেই, ক্লিনআপের প্রয়োজন নেই',
                    'log' => 'No backup directory found'
                ]);
            }
            
            $files = glob($backupPath . '/*.{sql,zip}', GLOB_BRACE);
            $deletedCount = 0;
            $deletedSize = 0;
            $logMessages = [];
            
            $cutoffTime = time() - ($days * 24 * 60 * 60);
            
            foreach ($files as $file) {
                if (filemtime($file) < $cutoffTime) {
                    $size = filesize($file);
                    if (unlink($file)) {
                        $deletedCount++;
                        $deletedSize += $size;
                        $logMessages[] = basename($file) . ' ডিলিট করা হয়েছে (' . $this->formatBytes($size) . ')';
                    }
                }
            }
            
            $message = '';
            if ($deletedCount > 0) {
                $message = $deletedCount . 'টি পুরানো ব্যাকআপ ডিলিট করা হয়েছে, মোট সাইজ: ' . $this->formatBytes($deletedSize);
            } else {
                $message = 'কোনো পুরানো ব্যাকআপ পাওয়া যায়নি (' . $days . ' দিনের পুরানো)';
            }
            
            \Log::info('Backup cleanup completed', [
                'deleted_count' => $deletedCount,
                'deleted_size' => $deletedSize,
                'message' => $message
            ]);
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'log' => implode("\n", $logMessages)
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Backup cleanup failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'ক্লিনআপ ব্যর্থ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get system health status for backup
     */
    public function getSystemHealth(Request $request)
    {
        try {
            $health = [
                'database' => $this->checkDatabaseHealth(),
                'storage' => $this->checkStorageHealth(),
                'permissions' => $this->checkPermissions(),
                'extensions' => $this->checkExtensions(),
                'cron_job' => $this->checkCronJob(),
                'backup_system' => $this->checkBackupSystem(),
            ];
            
            $allHealthy = !in_array(false, array_column($health, 'status'));
            
            return response()->json([
                'success' => true,
                'health' => $health,
                'all_healthy' => $allHealthy,
                'timestamp' => Carbon::now()
                    ->setTimezone('Asia/Dhaka')
                    ->format('Y-m-d H:i:s')
            ]);
            
        } catch (\Exception $e) {
            \Log::error('System health check failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'সিস্টেম হেলথ চেক ব্যর্থ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check database health
     */
    private function checkDatabaseHealth()
    {
        try {
            \DB::connection()->getPdo();
            return [
                'status' => true,
                'message' => 'ডাটাবেস সংযোগ সঠিকভাবে কাজ করছে'
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => 'ডাটাবেস সংযোগ ব্যর্থ: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check storage health
     */
    private function checkStorageHealth()
    {
        $backupPath = storage_path('app/backups');
        $alternativePath = storage_path('app/' . env('BACKUP_PATH', 'backups'));
        
        // Check if directory exists and is writable
        $pathToCheck = is_dir($alternativePath) ? $alternativePath : $backupPath;
        
        if (!is_dir($pathToCheck)) {
            if (!mkdir($pathToCheck, 0755, true)) {
                return [
                    'status' => false,
                    'message' => 'ব্যাকআপ ডিরেক্টরি তৈরি করা যায়নি: ' . $pathToCheck
                ];
            }
            return [
                'status' => true,
                'message' => 'ব্যাকআপ ডিরেক্টরি তৈরি হয়েছে'
            ];
        }
        
        if (!is_writable($pathToCheck)) {
            return [
                'status' => false,
                'message' => 'ব্যাকআপ ডিরেক্টরিতে লেখার পারমিশন নেই: ' . $pathToCheck
            ];
        }
        
        // Check disk space
        try {
            $freeSpace = disk_free_space('/');
            $totalSpace = disk_total_space('/');
            $freePercentage = $totalSpace > 0 ? round(($freeSpace / $totalSpace) * 100, 2) : 0;
            
            if ($freePercentage < 10) {
                return [
                    'status' => false,
                    'message' => 'স্টোরেজ প্রায় পূর্ণ (' . $freePercentage . '% ফ্রি)'
                ];
            }
            
            return [
                'status' => true,
                'message' => 'স্টোরেজ স্বাস্থ্যকর (' . $freePercentage . '% ফ্রি)'
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => 'স্টোরেজ চেক ব্যর্থ: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check required permissions
     */
    private function checkPermissions()
    {
        $requiredPaths = [
            storage_path(),
            storage_path('app'),
            storage_path('app/backups'),
            base_path('bootstrap/cache'),
        ];
        
        // Add alternative backup path
        $alternativePath = storage_path('app/' . env('BACKUP_PATH', 'backups'));
        if (!in_array($alternativePath, $requiredPaths)) {
            $requiredPaths[] = $alternativePath;
        }
        
        $unwritable = [];
        foreach ($requiredPaths as $path) {
            if (is_dir($path) && !is_writable($path)) {
                $unwritable[] = basename($path);
            }
        }
        
        if (!empty($unwritable)) {
            return [
                'status' => false,
                'message' => 'লেখার পারমিশন নেই: ' . implode(', ', $unwritable)
            ];
        }
        
        return [
            'status' => true,
            'message' => 'সকল প্রয়োজনীয় পারমিশন রয়েছে'
        ];
    }

    /**
     * Check required PHP extensions
     */
    private function checkExtensions()
    {
        $required = ['pdo', 'mbstring', 'xml', 'json'];
        $optional = ['zip']; // zip is optional for our custom backup
        
        $missing = [];
        foreach ($required as $ext) {
            if (!extension_loaded($ext)) {
                $missing[] = $ext;
            }
        }
        
        if (!empty($missing)) {
            return [
                'status' => false,
                'message' => 'PHP এক্সটেনশন নেই: ' . implode(', ', $missing)
            ];
        }
        
        // Check optional extensions
        $optionalMissing = [];
        foreach ($optional as $ext) {
            if (!extension_loaded($ext)) {
                $optionalMissing[] = $ext;
            }
        }
        
        if (!empty($optionalMissing)) {
            return [
                'status' => true,
                'message' => 'সকল প্রয়োজনীয় PHP এক্সটেনশন রয়েছে (কিছু অপশনাল এক্সটেনশন নেই: ' . implode(', ', $optionalMissing) . ')'
            ];
        }
        
        return [
            'status' => true,
            'message' => 'সকল প্রয়োজনীয় PHP এক্সটেনশন রয়েছে'
        ];
    }

    /**
     * Check if cron job is running
     */
    private function checkCronJob()
    {
        // This is a simple check
        $cronFile = storage_path('logs/scheduler.log');
        
        if (file_exists($cronFile)) {
            $lastModified = filemtime($cronFile);
            $hoursAgo = (time() - $lastModified) / 3600;
            
            if ($hoursAgo > 24) {
                return [
                    'status' => false,
                    'message' => 'ক্রন জব ২৪ ঘন্টার বেশি সময় ধরে রান হয়নি'
                ];
            }
            
            return [
                'status' => true,
                'message' => 'ক্রন জব সক্রিয়ভাবে কাজ করছে'
            ];
        }
        
        // Check Laravel scheduler log
        $laravelLog = storage_path('logs/laravel.log');
        if (file_exists($laravelLog)) {
            $logContent = file_get_contents($laravelLog);
            if (strpos($logContent, 'Running scheduled command') !== false || 
                strpos($logContent, 'Scheduler executed successfully') !== false) {
                return [
                    'status' => true,
                    'message' => 'লারাভেল শেডিউলার কাজ করছে'
                ];
            }
        }
        
        return [
            'status' => false,
            'message' => 'ক্রন জব লগ ফাইল পাওয়া যায়নি। দয়া করে শেডিউলার সেটআপ চেক করুন।'
        ];
    }

    /**
     * Check backup system
     */
    private function checkBackupSystem()
    {
        try {
            // Check if backup directory exists and is writable
            $backupPath = storage_path('app/backups');
            
            if (!is_dir($backupPath)) {
                if (!mkdir($backupPath, 0755, true)) {
                    return [
                        'status' => false,
                        'message' => 'ব্যাকআপ ডিরেক্টরি তৈরি করা যায়নি'
                    ];
                }
            }
            
            if (!is_writable($backupPath)) {
                return [
                    'status' => false,
                    'message' => 'ব্যাকআপ ডিরেক্টরিতে লেখার পারমিশন নেই'
                ];
            }
            
            // Check if we can write to backup directory
            $testFile = $backupPath . '/test_' . time() . '.txt';
            if (file_put_contents($testFile, 'test') === false) {
                return [
                    'status' => false,
                    'message' => 'ব্যাকআপ ডিরেক্টরিতে লেখা যায় না'
                ];
            }
            
            unlink($testFile);
            
            return [
                'status' => true,
                'message' => 'ব্যাকআপ সিস্টেম সঠিকভাবে কাজ করছে'
            ];
            
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => 'ব্যাকআপ সিস্টেম চেক ব্যর্থ: ' . $e->getMessage()
            ];
        }
    }
}