<?php
/**
 * Test location hierarchy after import
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Testing Location Hierarchy\n";
echo "==========================\n\n";

// Test continent آسیا
echo "1. Continent (قاره):\n";
$asia = DB::table('continents')->where('id', 4)->first();
if ($asia) {
    echo "   ✓ آسیا found: id={$asia->id}, name={$asia->name}\n\n";
} else {
    echo "   ✗ آسیا NOT FOUND!\n\n";
}

// Test country ایران
echo "2. Country (کشور):\n";
$iran = DB::table('countries')->where('id', 74)->first();
if ($iran) {
    echo "   ✓ ایران found: id={$iran->id}, name={$iran->name}, continent_id={$iran->continent_id}\n";
    
    // Verify continent_id
    if ($iran->continent_id == 4) {
        echo "   ✓ continent_id is correct (4 = آسیا)\n\n";
    } else {
        echo "   ✗ continent_id is WRONG! Expected 4, got {$iran->continent_id}\n\n";
    }
} else {
    echo "   ✗ ایران NOT FOUND!\n\n";
}

// Test provinces
echo "3. Provinces (استان‌ها):\n";
$provinces = DB::table('provinces')->where('country_id', 74)->get();
echo "   Total provinces with country_id=74: " . $provinces->count() . "\n";
if ($provinces->count() > 0) {
    echo "   Sample provinces:\n";
    foreach ($provinces->take(5) as $province) {
        echo "   - {$province->name} (id={$province->id})\n";
    }
    echo "\n";
} else {
    echo "   ✗ NO provinces found for Iran!\n\n";
}

// Test تهران province
echo "4. Tehran Province (استان تهران):\n";
$tehran = DB::table('provinces')->where('name', 'تهران')->first();
if ($tehran) {
    echo "   ✓ تهران found: id={$tehran->id}, country_id={$tehran->country_id}\n\n";
    
    // Test counties for Tehran
    echo "5. Counties in Tehran (شهرستان‌های تهران):\n";
    $counties = DB::table('counties')->where('province_id', $tehran->id)->get();
    echo "   Total counties: " . $counties->count() . "\n";
    foreach ($counties->take(5) as $county) {
        echo "   - {$county->name} (id={$county->id})\n";
    }
} else {
    echo "   ✗ تهران NOT FOUND!\n\n";
}

echo "\n==========================\n";
echo "Test Complete!\n";
