<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ReinstallTCPDFFonts extends Command
{
    protected $signature = 'tcpdf:reinstall-fonts';
    protected $description = 'Reinstall TCPDF fonts';

    public function handle()
    {
        $fontDir = storage_path('fonts/tcpdf');
        
        // Delete existing directory
        if (File::exists($fontDir)) {
            File::deleteDirectory($fontDir);
            $this->info("Deleted existing font directory");
        }
        
        // Create new directory
        File::makeDirectory($fontDir, 0755, true);
        $this->info("Created new font directory: {$fontDir}");
        
        // Copy fonts
        $sourceDir = base_path('vendor/tecnickcom/tcpdf/fonts');
        
        if (!File::exists($sourceDir)) {
            $this->error("TCPDF fonts not found. Install with: composer require tecnickcom/tcpdf");
            return 1;
        }
        
        $files = File::files($sourceDir);
        $copied = 0;
        
        foreach ($files as $file) {
            if ($file->getExtension() === 'php') {
                File::copy($file->getPathname(), $fontDir . '/' . $file->getFilename());
                $copied++;
            }
        }
        
        $this->info("Successfully copied {$copied} font files");
        
        // Fix 'n' key issue
        $this->call('tcpdf:fix-n-font');
        
        return 0;
    }
}