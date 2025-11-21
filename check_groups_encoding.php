<?php
/**
 * Check groups data and encoding
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Checking Groups Table:\n";
echo "======================\n\n";

// Get first 5 groups
$groups = DB::table('groups')->limit(5)->get();

echo "Total groups: " . DB::table('groups')->count() . "\n\n";

echo "Sample groups:\n";
foreach ($groups as $group) {
    echo "ID: {$group->id}\n";
    echo "Name: {$group->name}\n";
    echo "Name (hex): " . bin2hex($group->name) . "\n";
    echo "Group Type: {$group->group_type}\n";
    echo "---\n";
}

// Check database encoding
echo "\nDatabase encoding:\n";
$charset = DB::select("SHOW VARIABLES LIKE 'character_set_database'");
$collation = DB::select("SHOW VARIABLES LIKE 'collation_database'");
echo "Character Set: " . ($charset[0]->Value ?? 'N/A') . "\n";
echo "Collation: " . ($collation[0]->Value ?? 'N/A') . "\n";

// Check groups table encoding
echo "\nGroups table encoding:\n";
$tableStatus = DB::select("SHOW TABLE STATUS LIKE 'groups'");
if ($tableStatus) {
    echo "Collation: " . ($tableStatus[0]->Collation ?? 'N/A') . "\n";
}

// Check name column encoding
$columnInfo = DB::select("SHOW FULL COLUMNS FROM groups WHERE Field = 'name'");
if ($columnInfo) {
    echo "Name column collation: " . ($columnInfo[0]->Collation ?? 'N/A') . "\n";
    echo "Name column type: " . ($columnInfo[0]->Type ?? 'N/A') . "\n";
}
