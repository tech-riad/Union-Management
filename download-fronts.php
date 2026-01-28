<?php
// download-fonts.php - Laravel Project Root

echo "=== Font Downloader for Laravel MPDF ===\n\n";

// Define paths
$baseDir = __DIR__;
$storageDir = $baseDir . '/storage';
$fontDir = $storageDir . '/fonts';

echo "Base Directory: $baseDir\n";
echo "Storage Directory: $storageDir\n";
echo "Font Directory: $fontDir\n\n";

// Create directories if not exist
if (!file_exists($storageDir)) {
    mkdir($storageDir, 0755, true);
    echo "Created: storage/\n";
}

if (!file_exists($fontDir)) {
    mkdir($fontDir, 0755, true);
    echo "Created: storage/fonts/\n";
}

// Font URLs (using GitHub raw content)
$fonts = [
    'DejaVuSans.ttf' => [
        'url' => 'https://github.com/dejavu-fonts/dejavu-fonts/raw/master/ttf/DejaVuSans.ttf',
        'size' => 757804 // Expected size in bytes
    ],
    'DejaVuSans-Bold.ttf' => [
        'url' => 'https://github.com/dejavu-fonts/dejavu-fonts/raw/master/ttf/DejaVuSans-Bold.ttf',
        'size' => 753508
    ],
    'DejaVuSans-Oblique.ttf' => [
        'url' => 'https://github.com/dejavu-fonts/dejavu-fonts/raw/master/ttf/DejaVuSans-Oblique.ttf',
        'size' => 637684
    ],
    'DejaVuSans-BoldOblique.ttf' => [
        'url' => 'https://github.com/dejavu-fonts/dejavu-fonts/raw/master/ttf/DejaVuSans-BoldOblique.ttf',
        'size' => 638008
    ]
];

echo "\n=== Downloading Fonts ===\n\n";

$successCount = 0;
$totalFonts = count($fonts);

foreach ($fonts as $filename => $fontInfo) {
    $filePath = $fontDir . '/' . $filename;
    
    echo "Processing: $filename\n";
    echo "  URL: " . $fontInfo['url'] . "\n";
    
    // Check if already exists
    if (file_exists($filePath)) {
        $currentSize = filesize($filePath);
        if ($currentSize > 500000) { // If file is reasonably large
            echo "  ✓ Already exists (" . round($currentSize/1024) . " KB)\n\n";
            $successCount++;
            continue;
        } else {
            echo "  ⚠ File exists but seems small, re-downloading...\n";
        }
    }
    
    // Download font
    $maxAttempts = 3;
    $downloaded = false;
    
    for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
        echo "  Attempt $attempt of $maxAttempts... ";
        
        // Method 1: Use cURL if available
        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $fontInfo['url']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
            $content = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode === 200 && strlen($content) > 500000) {
                file_put_contents($filePath, $content);
                $downloaded = true;
                echo "✓ Downloaded via cURL (" . round(strlen($content)/1024) . " KB)\n";
                break;
            }
        }
        
        // Method 2: Use file_get_contents with context
        if (!$downloaded) {
            $context = stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
                'http' => [
                    'timeout' => 60,
                    'user_agent' => 'Mozilla/5.0'
                ]
            ]);
            
            $content = @file_get_contents($fontInfo['url'], false, $context);
            
            if ($content && strlen($content) > 500000) {
                file_put_contents($filePath, $content);
                $downloaded = true;
                echo "✓ Downloaded via file_get_contents (" . round(strlen($content)/1024) . " KB)\n";
                break;
            }
        }
        
        if (!$downloaded) {
            echo "✗ Failed\n";
            if ($attempt < $maxAttempts) {
                sleep(2); // Wait 2 seconds before retry
            }
        }
    }
    
    if ($downloaded) {
        $successCount++;
        
        // Verify file
        if (file_exists($filePath)) {
            $fileSize = filesize($filePath);
            echo "  ✓ Verified: " . round($fileSize/1024) . " KB\n";
            
            // Set permissions
            chmod($filePath, 0644);
        }
    } else {
        echo "  ✗ All attempts failed for $filename\n";
        
        // Provide manual download instructions
        echo "  Manual download required:\n";
        echo "  1. Visit: " . $fontInfo['url'] . "\n";
        echo "  2. Save file as: $filePath\n";
    }
    
    echo "\n";
}

echo "=== Summary ===\n";
echo "Successfully downloaded: $successCount of $totalFonts fonts\n";
echo "Font directory: $fontDir\n\n";

if ($successCount > 0) {
    echo "Files in font directory:\n";
    $files = scandir($fontDir);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $size = filesize($fontDir . '/' . $file);
            echo "  - $file (" . round($size/1024) . " KB)\n";
        }
    }
    
    echo "\n✅ Fonts are ready for MPDF!\n";
    echo "Now test PDF generation with:\n";
    echo "1. http://localhost/your-project/test-pdf\n";
    echo "2. Or visit your invoice page\n";
} else {
    echo "❌ No fonts were downloaded.\n";
    echo "Please manually download fonts from:\n";
    echo "https://github.com/dejavu-fonts/dejavu-fonts/tree/master/ttf\n";
    echo "And place them in: $fontDir\n";
}

// Create a simple test script
$testScript = $baseDir . '/test-font-check.php';
if (!file_exists($testScript)) {
    $testContent = '<?php
// test-font-check.php
$fontDir = __DIR__ . \'/storage/fonts\';
echo "Font Directory: " . $fontDir . "\n\n";

if (file_exists($fontDir)) {
    $files = scandir($fontDir);
    echo "Files found:\n";
    foreach ($files as $file) {
        if ($file !== \'.\' && $file !== \'..\') {
            $path = $fontDir . \'/\' . $file;
            $size = filesize($path);
            $writable = is_writable($path) ? "✓ Writable" : "✗ Not Writable";
            echo "  - $file (" . round($size/1024) . " KB) - $writable\n";
        }
    }
} else {
    echo "Font directory not found!\n";
}

echo "\nMPDF Test:\n";
if (class_exists(\Mpdf\Mpdf::class)) {
    echo "✓ MPDF is installed\n";
} else {
    echo "✗ MPDF not installed\n";
}
';
    
    file_put_contents($testScript, $testContent);
    echo "\nCreated test script: test-font-check.php\n";
    echo "Run: php test-font-check.php\n";
}