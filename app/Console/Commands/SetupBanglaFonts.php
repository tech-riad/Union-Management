<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetupBanglaFonts extends Command
{
    protected $signature = 'fonts:setup-bangla';
    protected $description = 'Setup Bangla fonts for DomPDF';

    public function handle()
    {
        $this->info('Setting up Bangla fonts for DomPDF...');
        
        // Create directories
        File::ensureDirectoryExists(storage_path('fonts'));
        File::ensureDirectoryExists(storage_path('app/dompdf/temp'));
        
        // Font URLs (you can download from these sources)
        $fonts = [
            'Nikosh' => 'https://github.com/omicronlab/SolaimanLipi/raw/master/Nikosh.ttf',
            'SolaimanLipi' => 'https://github.com/omicronlab/SolaimanLipi/raw/master/SolaimanLipi.ttf',
            'SiyamRupali' => 'https://github.com/fonts-for-bengali/rupali/raw/main/SiyamRupali.ttf',
        ];
        
        foreach ($fonts as $name => $url) {
            $path = storage_path("fonts/{$name}.ttf");
            
            if (!File::exists($path)) {
                $this->warn("{$name}.ttf not found in storage/fonts/");
                $this->info("You can download it from: {$url}");
                $this->info("Save it as: {$path}");
            } else {
                $this->info("✓ {$name}.ttf found");
            }
        }
        
        $this->info("\nFont setup completed!");
        $this->info("Please ensure these fonts are in: " . storage_path('fonts'));
        
        return Command::SUCCESS;
    }
}