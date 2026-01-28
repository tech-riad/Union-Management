<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FixTCPDF extends Command
{
    protected $signature = 'tcpdf:fix';
    protected $description = 'Fix TCPDF font directory';

    public function handle()
    {
        $this->info("Fixing TCPDF configuration...");
        
        // Run both commands
        $this->call('tcpdf:reinstall-fonts');
        $this->call('tcpdf:fix-n-font');
        
        $this->info("TCPDF fix completed!");
        return 0;
    }
}