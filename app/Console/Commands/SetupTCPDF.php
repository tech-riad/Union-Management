<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetupTCPDF extends Command
{
    protected $signature = 'tcpdf:setup';
    protected $description = 'Complete setup for TCPDF with Bangla support';

    public function handle()
    {
        $this->info('🚀 Setting up TCPDF with Bangla support...');
        
        // ১. TCPDF ফিক্স কমান্ড রান করুন
        $this->call('tcpdf:fix');
        
        // ২. বাংলা ফন্ট ডাউনলোডের লিংক দেখান
        $this->showBanglaFontLinks();
        
        // ৩. টেস্ট PDF তৈরি করুন
        $this->createTestPDF();
        
        $this->info('🎉 TCPDF setup completed!');
        $this->line('Run: php artisan serve');
        $this->line('Then visit: http://localhost:8000/test-tcpdf');
        
        return 0;
    }
    
    private function showBanglaFontLinks()
    {
        $this->info('📥 Bangla Font Sources:');
        $this->line('  1. Nikosh: https://www.omicronlab.com/bangla-fonts.html');
        $this->line('  2. SolaimanLipi: https://www.omicronlab.com/bangla-fonts.html');
        $this->line('  3. SiyamRupali: https://github.com/fonts-for-bengali/rupali');
        $this->line('');
        $this->info('💡 To use custom Bangla fonts:');
        $this->line('  1. Download the .ttf file');
        $this->line('  2. Convert to TCPDF format using online tools');
        $this->line('  3. Place in: ' . storage_path('fonts/tcpdf'));
    }
    
    private function createTestPDF()
    {
        $this->info('📄 Creating test PDF...');
        
        try {
            $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
            
            $pdf->SetCreator('Test System');
            $pdf->SetAuthor('Test');
            $pdf->SetTitle('বাংলা টেস্ট PDF');
            
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->SetMargins(15, 15, 15);
            $pdf->SetAutoPageBreak(TRUE, 15);
            
            $pdf->AddPage();
            $pdf->SetFont('freeserif', '', 12);
            
            $html = '
            <h1>বাংলা টেস্ট</h1>
            <p>স্বরবর্ণ: অ আ ই ঈ উ ঊ ঋ এ ঐ ও ঔ</p>
            <p>যুক্তবর্ণ: ক্ষ ষ্ণ শ্র ক্ত স্ক্র</p>
            <p>বাক্য: আমার সোনার বাংলা, আমি তোমায় ভালোবাসি</p>
            ';
            
            $pdf->writeHTML($html, true, false, true, false, '');
            
            $testPath = storage_path('app/test_bangla.pdf');
            $pdf->Output($testPath, 'F');
            
            $this->info('✓ Test PDF created: ' . $testPath);
            $this->info('✓ File size: ' . filesize($testPath) . ' bytes');
            
        } catch (\Exception $e) {
            $this->error('❌ Failed to create test PDF: ' . $e->getMessage());
        }
    }
}