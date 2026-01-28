<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class TCPDFServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // TCPDF কনস্ট্যান্টস সেট করুন
        $this->setTCPDFConstants();
        
        // TCPDF ক্লাস অটোলোড করুন
        $this->autoloadTCPDF();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // TCPDF ফন্ট ডিরেক্টরি তৈরি করুন
        $this->ensureDirectories();
    }
    
    /**
     * TCPDF কনস্ট্যান্টস সেট করুন
     */
    private function setTCPDFConstants(): void
    {
        // শুধুমাত্র একবার সেট করুন
        if (!defined('K_TCPDF_EXTERNAL_CONFIG')) {
            define('K_TCPDF_EXTERNAL_CONFIG', true);
        }
        
        // TCPDF পাথ কনস্ট্যান্টস
        if (!defined('K_PATH_MAIN')) {
            define('K_PATH_MAIN', base_path('vendor/tecnickcom/tcpdf/'));
        }
        
        if (!defined('K_PATH_FONTS')) {
            define('K_PATH_FONTS', storage_path('fonts/tcpdf') . DIRECTORY_SEPARATOR);
        }
        
        if (!defined('K_PATH_URL')) {
            define('K_PATH_URL', config('app.url'));
        }
        
        if (!defined('K_PATH_IMAGES')) {
            define('K_PATH_IMAGES', public_path());
        }
        
        if (!defined('K_BLANK_IMAGE')) {
            define('K_BLANK_IMAGE', '_blank.png');
        }
        
        if (!defined('PDF_PAGE_FORMAT')) {
            define('PDF_PAGE_FORMAT', 'A4');
        }
        
        if (!defined('PDF_PAGE_ORIENTATION')) {
            define('PDF_PAGE_ORIENTATION', 'P');
        }
        
        if (!defined('PDF_CREATOR')) {
            define('PDF_CREATOR', config('app.name', 'Laravel TCPDF'));
        }
        
        if (!defined('PDF_AUTHOR')) {
            define('PDF_AUTHOR', config('app.name', 'Laravel'));
        }
        
        if (!defined('PDF_UNIT')) {
            define('PDF_UNIT', 'mm');
        }
        
        if (!defined('PDF_MARGIN_HEADER')) {
            define('PDF_MARGIN_HEADER', 5);
        }
        
        if (!defined('PDF_MARGIN_FOOTER')) {
            define('PDF_MARGIN_FOOTER', 10);
        }
        
        if (!defined('PDF_MARGIN_TOP')) {
            define('PDF_MARGIN_TOP', 27);
        }
        
        if (!defined('PDF_MARGIN_BOTTOM')) {
            define('PDF_MARGIN_BOTTOM', 25);
        }
        
        if (!defined('PDF_MARGIN_LEFT')) {
            define('PDF_MARGIN_LEFT', 15);
        }
        
        if (!defined('PDF_MARGIN_RIGHT')) {
            define('PDF_MARGIN_RIGHT', 15);
        }
        
        if (!defined('PDF_FONT_NAME_MAIN')) {
            define('PDF_FONT_NAME_MAIN', 'freeserif');
        }
        
        if (!defined('PDF_FONT_SIZE_MAIN')) {
            define('PDF_FONT_SIZE_MAIN', 10);
        }
        
        if (!defined('PDF_FONT_NAME_DATA')) {
            define('PDF_FONT_NAME_DATA', 'freeserif');
        }
        
        if (!defined('PDF_FONT_SIZE_DATA')) {
            define('PDF_FONT_SIZE_DATA', 8);
        }
        
        if (!defined('PDF_FONT_MONOSPACED')) {
            define('PDF_FONT_MONOSPACED', 'courier');
        }
        
        if (!defined('PDF_IMAGE_SCALE_RATIO')) {
            define('PDF_IMAGE_SCALE_RATIO', 1.25);
        }
        
        if (!defined('HEAD_MAGNIFICATION')) {
            define('HEAD_MAGNIFICATION', 1.1);
        }
        
        if (!defined('K_CELL_HEIGHT_RATIO')) {
            define('K_CELL_HEIGHT_RATIO', 1.25);
        }
        
        if (!defined('K_TITLE_MAGNIFICATION')) {
            define('K_TITLE_MAGNIFICATION', 1.3);
        }
        
        if (!defined('K_SMALL_RATIO')) {
            define('K_SMALL_RATIO', 2/3);
        }
        
        if (!defined('K_THAI_TOPCHARS')) {
            define('K_THAI_TOPCHARS', true);
        }
        
        if (!defined('K_TCPDF_CALLS_IN_HTML')) {
            define('K_TCPDF_CALLS_IN_HTML', false);
        }
        
        if (!defined('K_TIMEZONE')) {
            define('K_TIMEZONE', config('app.timezone', 'UTC'));
        }
    }
    
    /**
     * TCPDF অটোলোড করুন
     */
    private function autoloadTCPDF(): void
    {
        $tcpdfPath = base_path('vendor/tecnickcom/tcpdf/tcpdf.php');
        
        if (File::exists($tcpdfPath) && !class_exists('TCPDF')) {
            require_once $tcpdfPath;
        }
    }
    
    /**
     * প্রয়োজনীয় ডিরেক্টরিগুলো তৈরি করুন
     */
    private function ensureDirectories(): void
    {
        $directories = [
            storage_path('fonts/tcpdf'),
            storage_path('app/tcpdf/temp'),
            storage_path('logs'),
        ];
        
        foreach ($directories as $directory) {
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }
        }
        
        // ফন্ট ফাইল কপি করুন যদি না থাকে
        $this->copyFontFiles();
    }
    
    /**
     * TCPDF ফন্ট ফাইল কপি করুন
     */
    private function copyFontFiles(): void
    {
        $sourceDir = base_path('vendor/tecnickcom/tcpdf/fonts');
        $destDir = storage_path('fonts/tcpdf');
        
        if (!File::exists($sourceDir) || !File::exists($destDir)) {
            return;
        }
        
        $fontFiles = File::files($sourceDir);
        
        foreach ($fontFiles as $file) {
            if ($file->getExtension() === 'php') {
                $destFile = $destDir . '/' . $file->getFilename();
                if (!File::exists($destFile)) {
                    File::copy($file->getPathname(), $destFile);
                }
            }
        }
    }
}