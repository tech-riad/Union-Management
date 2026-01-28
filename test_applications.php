<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=========================================\n";
echo "Certificate Applications Test\n";
echo "=========================================\n\n";

try {
    // 1. Direct database count
    echo "1. Direct Database Query:\n";
    $dbCount = \Illuminate\Support\Facades\DB::table('certificate_applications')->count();
    echo "   Total Applications in DB: " . $dbCount . "\n";
    
    // 2. Model count (alternative way)
    echo "\n2. Model Query:\n";
    try {
        $modelCount = \App\Models\CertificateApplication::query()->count();
        echo "   Model Count: " . $modelCount . "\n";
    } catch (\Exception $e) {
        echo "   Model Error: " . $e->getMessage() . "\n";
    }
    
    // 3. Show sample data if exists
    if ($dbCount > 0) {
        echo "\n3. Sample Applications:\n";
        echo "   " . str_repeat("-", 50) . "\n";
        
        $apps = \Illuminate\Support\Facades\DB::table('certificate_applications')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();
        
        foreach ($apps as $app) {
            echo "   ID: " . $app->id . "\n";
            echo "   Application No: " . $app->application_no . "\n";
            echo "   Status: " . $app->status . "\n";
            echo "   User ID: " . $app->user_id . "\n";
            echo "   Certificate ID: " . $app->certificate_id . "\n";
            echo "   Fee: " . $app->fee . "\n";
            echo "   Created: " . $app->created_at . "\n";
            echo "   " . str_repeat("-", 30) . "\n";
        }
    }
    
    // 4. Check related tables
    echo "\n4. Related Tables:\n";
    
    $userCount = \Illuminate\Support\Facades\DB::table('users')->count();
    echo "   Total Users: " . $userCount . "\n";
    
    $certTypeCount = \Illuminate\Support\Facades\DB::table('certificate_types')->count();
    echo "   Total Certificate Types: " . $certTypeCount . "\n";
    
    if ($certTypeCount > 0) {
        echo "\n   Certificate Types:\n";
        $types = \Illuminate\Support\Facades\DB::table('certificate_types')->get();
        foreach ($types as $type) {
            echo "   - ID: " . $type->id . ", Name: " . $type->name . "\n";
        }
    }
    
    echo "\n=========================================\n";
    echo "Test Complete\n";
    echo "=========================================\n";
    
} catch (\Exception $e) {
    echo "\nERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}