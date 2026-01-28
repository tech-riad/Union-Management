<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CheckFonts extends Command
{
    protected $signature = 'fonts:check';
    protected $description = 'Check available fonts for PDF generation';

    public function handle()
    {
        $this->info('🔍 Checking available fonts...');
        
        // TCPDF ফন্ট চেক
        $this->checkTCPDFFonts();
        
        // ডোমপিডিএফ ফন্ট চেক
        $this->checkDomPDFFonts();
        
        // বাংলা ফন্ট চেক
        $this->checkBanglaFonts();
        
        return 0;
    }
    
    private function checkTCPDFFonts()
    {
        $this->info('📊 TCPDF Fonts:');
        
        $tcpdfFontDir = storage_path('fonts/tcpdf');
        
        if (File::exists($tcpdfFontDir)) {
            $fontFiles = File::files($tcpdfFontDir);
            $count = 0;
            
            foreach ($fontFiles as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                    $count++;
                    $fontName = pathinfo($file, PATHINFO_FILENAME);
                    $this->line("  {$count}. {$fontName}");
                }
            }
            
            $this->info("  Total: {$count} fonts");
        } else {
            $this->warn('  TCPDF font directory not found');
        }
    }
    
    private function checkDomPDFFonts()
    {
        $this->info('📊 DomPDF Fonts:');
        
        $dompdfFontDir = storage_path('fonts');
        
        if (File::exists($dompdfFontDir)) {
            $fontFiles = File::files($dompdfFontDir);
            $ttfCount = 0;
            
            foreach ($fontFiles as $file) {
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                if (in_array(strtolower($ext), ['ttf', 'otf'])) {
                    $ttfCount++;
                    $fontName = pathinfo($file, PATHINFO_FILENAME);
                    $this->line("  {$ttfCount}. {$fontName}.{$ext}");
                }
            }
            
            $this->info("  Total: {$ttfCount} TTF/OTF fonts");
        } else {
            $this->warn('  DomPDF font directory not found');
        }
    }
    
    private function checkBanglaFonts()
    {
        $this->info('🇧🇩 Bangla Fonts Available:');
        
        $banglaFonts = [
            'Nikosh' => ['tcpdf' => false, 'dompdf' => false],
            'SolaimanLipi' => ['tcpdf' => false, 'dompdf' => false],
            'SiyamRupali' => ['tcpdf' => false, 'dompdf' => false],
            'freeserif' => ['tcpdf' => true, 'dompdf' => false],
        ];
        
        // TCPDF ফন্ট চেক
        $tcpdfDir = storage_path('fonts/tcpdf');
        if (File::exists($tcpdfDir)) {
            foreach ($banglaFonts as $font => &$info) {
                $fontFile = $tcpdfDir . '/' . strtolower($font) . '.php';
                if (File::exists($fontFile)) {
                    $info['tcpdf'] = true;
                }
            }
        }
        
        // DomPDF ফন্ট চেক
        $dompdfDir = storage_path('fonts');
        if (File::exists($dompdfDir)) {
            foreach ($banglaFonts as $font => &$info) {
                $ttfFile = $dompdfDir . '/' . $font . '.ttf';
                if (File::exists($ttfFile)) {
                    $info['dompdf'] = true;
                }
            }
        }
        
        // রেজাল্ট দেখান
        foreach ($banglaFonts as $font => $info) {
            $tcpdfStatus = $info['tcpdf'] ? '✅' : '❌';
            $dompdfStatus = $info['dompdf'] ? '✅' : '❌';
            $this->line("  {$font}: TCPDF {$tcpdfStatus} | DomPDF {$dompdfStatus}");
        }
    }
}