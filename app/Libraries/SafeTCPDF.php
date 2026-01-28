<?php

namespace App\Libraries;

class SafeTCPDF extends \TCPDF
{
    /**
     * Override to handle font loading errors
     */
    public function SetFont($family, $style = '', $size = null, $fontfile = '', $subset = 'default', $out = true)
    {
        try {
            // Try to set the font
            return parent::SetFont($family, $style, $size, $fontfile, $subset, $out);
        } catch (\Exception $e) {
            // If font fails, try freeserif (supports Bengali)
            if ($family !== 'freeserif') {
                error_log("Font error: {$family} - {$e->getMessage()}. Falling back to freeserif.");
                return parent::SetFont('freeserif', $style, $size, '', $subset, $out);
            }
            
            // If freeserif also fails, use core font
            error_log("freeserif also failed. Using helvetica.");
            return parent::SetFont('helvetica', $style, $size, '', $subset, $out);
        }
    }
    
    /**
     * Override to fix font path issues
     */
    protected function _getfontpath()
    {
        $fontDir = storage_path('fonts/tcpdf');
        
        // Ensure directory exists
        if (!file_exists($fontDir)) {
            @mkdir($fontDir, 0755, true);
            
            // Try to copy fonts
            $sourceDir = base_path('vendor/tecnickcom/tcpdf/fonts');
            if (file_exists($sourceDir)) {
                $files = scandir($sourceDir);
                foreach ($files as $file) {
                    if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                        $source = $sourceDir . '/' . $file;
                        $dest = $fontDir . '/' . $file;
                        if (!file_exists($dest)) {
                            @copy($source, $dest);
                        }
                    }
                }
            }
        }
        
        return $fontDir . DIRECTORY_SEPARATOR;
    }
}