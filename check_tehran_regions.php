<?php
/**
 * Check Tehran regions data
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Checking Tehran City and Regions\n";
echo "=================================\n\n";

// 1. Find Tehran city
echo "1. Finding Tehran city:\n";
$tehranCity = DB::table('cities')
    ->where('name', 'like', '%تهران%')
    ->where('name', 'NOT like', '%شهرستان%')
    ->get();

echo "   Found " . $tehranCity->count() . " cities matching 'تهران':\n";
foreach ($tehranCity as $city) {
    echo "   - ID: {$city->id}, Name: {$city->name}, Province: {$city->province_id}, County: {$city->counties_id}, District: {$city->district_id}\n";
}

// 2. Check regions table structure
echo "\n2. Regions table structure sample:\n";
$sampleRegions = DB::table('regions')->limit(5)->get();
foreach ($sampleRegions as $region) {
    $columns = get_object_vars($region);
    echo "   Columns: " . implode(', ', array_keys($columns)) . "\n";
    break;
}
echo "   Sample data:\n";
foreach ($sampleRegions as $region) {
    echo "   - ID: {$region->id}, Name: {$region->name}";
    if (isset($region->parent_id)) echo ", Parent: {$region->parent_id}";
    if (isset($region->city_id)) echo ", City: {$region->city_id}";
    if (isset($region->province_id)) echo ", Province: {$region->province_id}";
    if (isset($region->district_id)) echo ", District: {$region->district_id}";
    echo "\n";
}

// 3. Search for Tehran regions (مناطق تهران)
echo "\n3. Searching for Tehran regions (مناطق 1-22):\n";
$tehranRegions = DB::table('regions')
    ->where('name', 'like', '%منطقه%')
    ->orWhere('name', 'like', '%تهران%')
    ->limit(25)
    ->get();

echo "   Found " . $tehranRegions->count() . " regions:\n";
foreach ($tehranRegions as $region) {
    echo "   - ID: {$region->id}, Name: {$region->name}";
    if (isset($region->parent_id)) echo ", Parent: {$region->parent_id}";
    if (isset($region->city_id)) echo ", City: {$region->city_id}";
    echo "\n";
}

// 4. Check if there's a city_id column
echo "\n4. Checking columns in regions table:\n";
$columns = DB::select("DESCRIBE regions");
echo "   Columns: ";
foreach ($columns as $col) {
    echo $col->Field . " ";
}
echo "\n";

// 5. Try to find regions by different criteria
echo "\n5. Looking for regions with specific patterns:\n";
for ($i = 1; $i <= 22; $i++) {
    $region = DB::table('regions')
        ->where('name', 'like', "%منطقه {$i}%")
        ->orWhere('name', 'like', "%منطقه ۱%")
        ->orWhere('name', 'like', "%منطقه ٪%")
        ->first();
    
    if ($region) {
        echo "   ✓ Found: {$region->name} (id={$region->id})";
        if (isset($region->parent_id)) echo " parent_id={$region->parent_id}";
        echo "\n";
        break; // Just show first match
    }
}

echo "\n=================================\n";
