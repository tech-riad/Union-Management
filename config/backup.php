<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Backup Configuration
    |--------------------------------------------------------------------------
    */
    
    'enabled' => env('BACKUP_ENABLED', true),
    
    /*
    |--------------------------------------------------------------------------
    | Auto Backup Settings
    |--------------------------------------------------------------------------
    */
    'auto_backup' => [
        'enabled' => env('BACKUP_AUTO_ENABLED', true),
        'schedule' => env('BACKUP_SCHEDULE', 'daily'), // daily, weekly, monthly
        'time' => env('BACKUP_TIME', '02:00'),
        'type' => env('BACKUP_TYPE', 'database'), // all, database, files
        'max_backups' => env('BACKUP_MAX_COUNT', 10),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Retention Policy
    |--------------------------------------------------------------------------
    */
    'retention_days' => env('BACKUP_RETENTION_DAYS', 30),
    
    /*
    |--------------------------------------------------------------------------
    | Notification Settings
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'enabled' => env('BACKUP_NOTIFICATIONS', true),
        'email' => env('BACKUP_NOTIFICATION_EMAIL', 'admin@example.com'),
        'on_success' => env('BACKUP_NOTIFY_SUCCESS', true),
        'on_failure' => env('BACKUP_NOTIFY_FAILURE', true),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Storage Settings
    |--------------------------------------------------------------------------
    */
    'storage' => [
        'disk' => env('BACKUP_DISK', 'local'),
        'path' => env('BACKUP_PATH', 'backups'),
        'keep_local' => env('BACKUP_KEEP_LOCAL', true),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Database Backup Settings
    |--------------------------------------------------------------------------
    */
    'database' => [
        'connection' => env('DB_CONNECTION', 'mysql'),
        'tables' => [
            // Specify tables to include/exclude
            // 'include' => ['users', 'applications', 'invoices'],
            // 'exclude' => ['migrations', 'failed_jobs'],
        ],
        
        // ✅ XAMPP Windows এর জন্য mysqldump path যোগ করুন
        'dump_binary_path' => 'C:/xampp/mysql/bin/', // Windows XAMPP path
        'dump_command_binary_path' => 'C:/xampp/mysql/bin/', // Windows XAMPP path
        
        // Alternative paths (try different paths if above doesn't work)
        'alternative_paths' => [
            'C:\\xampp\\mysql\\bin\\',
            'C:/Program Files/xampp/mysql/bin/',
            'C:/xampp/mysql/bin/mysqldump.exe',
        ],
        
        // Database dump settings
        'dump_settings' => [
            'use_single_transaction' => true,
            'timeout' => 60 * 5, // 5 minutes
            'add_extra_option' => '--skip-comments --skip-add-drop-table',
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | File Backup Settings
    |--------------------------------------------------------------------------
    */
    'files' => [
        'include' => [
            base_path('.env'),
            base_path('app/Models'),
            base_path('app/Http/Controllers'),
            base_path('config'),
            base_path('database/migrations'),
            base_path('database/seeders'),
        ],
        'exclude' => [
            'node_modules',
            'vendor',
            'storage/logs',
            'storage/framework',
            'storage/app/backups', // Don't backup backup files
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Spatie Laravel Backup Package Compatibility Settings
    |--------------------------------------------------------------------------
    */
    
    // If using spatie/laravel-backup package, add these settings
    'spatie_backup_compatibility' => [
        'enabled' => true,
        
        'backup' => [
            'name' => env('APP_NAME', 'Union Management System') . ' Backup',
            
            'source' => [
                'files' => [
                    'include' => [
                        base_path('.env'),
                        base_path('app'),
                        base_path('config'),
                        base_path('database'),
                        base_path('resources'),
                        base_path('routes'),
                        base_path('public'),
                    ],
                    'exclude' => [
                        base_path('vendor'),
                        base_path('node_modules'),
                        storage_path('app/backups'),
                    ],
                ],

                'databases' => [
                    'mysql',
                ],
            ],

            'destination' => [
                'filename_prefix' => '',
                'disks' => [
                    'local',
                ],
            ],

            'temporary_directory' => storage_path('app/backup-temp'),
        ],

        'monitor_backups' => [
            [
                'name' => env('APP_NAME', 'Union Management System'),
                'disks' => ['local'],
                'newest_backups_should_not_be_older_than_days' => 1,
                'storage_used_may_not_be_higher_than_megabytes' => 5000,
            ],
        ],

        'cleanup' => [
            'strategy' => \Spatie\Backup\Tasks\Cleanup\Strategies\DefaultStrategy::class,

            'default_strategy' => [
                'keep_all_backups_for_days' => 7,
                'keep_daily_backups_for_days' => 16,
                'keep_weekly_backups_for_weeks' => 8,
                'keep_monthly_backups_for_months' => 4,
                'keep_yearly_backups_for_years' => 2,
                'delete_oldest_backups_when_using_more_megabytes_than' => 5000,
            ],
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Custom Backup Settings for Windows XAMPP
    |--------------------------------------------------------------------------
    */
    'windows_xampp' => [
        'mysql_path' => 'C:/xampp/mysql/bin/',
        'mysqldump_exe' => 'C:/xampp/mysql/bin/mysqldump.exe',
        'mysql_exe' => 'C:/xampp/mysql/bin/mysql.exe',
        
        // Command templates for Windows
        'dump_command' => '"C:/xampp/mysql/bin/mysqldump.exe" --host=%host% --user=%user% --password=%pass% %database% > "%output%"',
        'restore_command' => '"C:/xampp/mysql/bin/mysql.exe" --host=%host% --user=%user% --password=%pass% %database% < "%input%"',
        
        // Test if mysqldump is accessible
        'test_command' => '"C:/xampp/mysql/bin/mysqldump.exe" --version',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Fallback Settings (if spatie package not working)
    |--------------------------------------------------------------------------
    */
    'fallback_method' => [
        'enabled' => true,
        'use_custom_command' => true,
        'custom_command_class' => 'App\Console\Commands\BackupAuto',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Logging Settings
    |--------------------------------------------------------------------------
    */
    'logging' => [
        'enabled' => true,
        'channel' => env('BACKUP_LOG_CHANNEL', 'stack'),
        'level' => env('BACKUP_LOG_LEVEL', 'info'),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    */
    'security' => [
        'password_protect' => env('BACKUP_PASSWORD_PROTECT', false),
        'password' => env('BACKUP_PASSWORD', null),
        'encryption' => env('BACKUP_ENCRYPTION', false),
        'encryption_key' => env('BACKUP_ENCRYPTION_KEY', null),
    ],
];