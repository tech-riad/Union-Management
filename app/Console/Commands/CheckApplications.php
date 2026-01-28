<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckApplications extends Command
{
    protected $signature = 'app:check-applications';
    protected $description = 'Check certificate applications in database';

    public function handle()
    {
        $this->info('Checking Certificate Applications...');
        
        try {
            // Count records
            $count = DB::table('certificate_applications')->count();
            $this->info("Total Applications: {$count}");
            
            if ($count > 0) {
                // Get applications
                $applications = DB::table('certificate_applications')
                    ->select('id', 'application_no', 'status', 'user_id', 'certificate_id', 'fee')
                    ->orderBy('id', 'desc')
                    ->limit(10)
                    ->get();
                
                // Convert to array for table
                $tableData = [];
                foreach ($applications as $app) {
                    $tableData[] = [
                        $app->id,
                        $app->application_no,
                        $app->status,
                        $app->user_id,
                        $app->certificate_id,
                        $app->fee
                    ];
                }
                
                $this->table(
                    ['ID', 'Application No', 'Status', 'User ID', 'Cert ID', 'Fee'],
                    $tableData
                );
            } else {
                $this->warn('No applications found in database.');
            }
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
    }
}