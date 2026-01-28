<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FixTCPDFNFont extends Command
{
    protected $signature = 'tcpdf:fix-n-font';
    protected $description = 'Fix TCPDF font n key issue';

    public function handle()
    {
        $this->info("Checking and fixing TCPDF font 'n' key issue...");
        
        // Font directory
        $fontDir = storage_path('fonts/tcpdf');
        
        // Create directory if doesn't exist
        if (!File::exists($fontDir)) {
            File::makeDirectory($fontDir, 0755, true);
            $this->info("Created font directory: {$fontDir}");
        }
        
        // Copy fonts from TCPDF if needed
        $this->copyTCPDFFonts($fontDir);
        
        // Fix font files
        $this->fixFontFiles($fontDir);
        
        $this->info("Done!");
        return 0;
    }
    
    private function copyTCPDFFonts($fontDir)
    {
        $sourceDir = base_path('vendor/tecnickcom/tcpdf/fonts');
        
        if (!File::exists($sourceDir)) {
            $this->error("TCPDF fonts not found at: {$sourceDir}");
            $this->info("Please install TCPDF: composer require tecnickcom/tcpdf");
            return;
        }
        
        $files = File::files($sourceDir);
        $copied = 0;
        
        foreach ($files as $file) {
            if ($file->getExtension() === 'php') {
                $destFile = $fontDir . '/' . $file->getFilename();
                if (!File::exists($destFile)) {
                    File::copy($file->getPathname(), $destFile);
                    $copied++;
                }
            }
        }
        
        if ($copied > 0) {
            $this->info("Copied {$copied} font files from TCPDF");
        }
    }
    
    private function fixFontFiles($fontDir)
    {
        $fontFiles = File::files($fontDir);
        $fixed = 0;
        
        foreach ($fontFiles as $file) {
            if ($file->getExtension() === 'php') {
                $content = File::get($file->getPathname());
                
                // Check if $enc array exists
                if (strpos($content, '$enc = array(') !== false) {
                    // Check if 'n' key exists
                    if (strpos($content, "'n'=>") === false && strpos($content, '"n"=>') === false) {
                        // Add 'n' key
                        $newContent = str_replace(
                            '$enc = array(',
                            '$enc = array(' . "\n\t" . "'n'=>array(0=>128),",
                            $content
                        );
                        
                        File::put($file->getPathname(), $newContent);
                        $fixed++;
                        $this->info("Fixed: " . $file->getFilename());
                    }
                }
            }
        }
        
        if ($fixed > 0) {
            $this->info("Fixed {$fixed} font files with missing 'n' key");
        } else {
            $this->info("No font files needed fixing");
        }
    }
}