<?php
/**
 * Import location data from extracted SQL file
 * This safely imports only location tables data
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$sqlFile = __DIR__ . '/import_locations_only.sql';

echo "Reading SQL file...\n";
$sql = file_get_contents($sqlFile);

echo "File size: " . number_format(strlen($sql)) . " bytes\n";
echo "Splitting into statements...\n";

// Split by semicolons but keep multi-line INSERT statements together
$statements = [];
$currentStatement = '';
$lines = explode("\n", $sql);

foreach ($lines as $line) {
    $line = trim($line);
    
    // Skip comments and empty lines
    if (empty($line) || strpos($line, '--') === 0) {
        continue;
    }
    
    $currentStatement .= $line . ' ';
    
    // Check if statement is complete (ends with ;)
    if (substr($line, -1) === ';') {
        $statements[] = trim($currentStatement);
        $currentStatement = '';
    }
}

echo "Found " . count($statements) . " SQL statements\n";
echo "Executing...\n\n";

$executed = 0;
$errors = 0;

foreach ($statements as $index => $statement) {
    try {
        // Show progress for INSERT statements
        if (stripos($statement, 'INSERT INTO') === 0) {
            preg_match('/INSERT INTO `(\w+)`/', $statement, $matches);
            $table = $matches[1] ?? 'unknown';
            echo "Importing data for table: {$table}...\n";
        } else if (stripos($statement, 'TRUNCATE') === 0) {
            preg_match('/TRUNCATE TABLE `(\w+)`/', $statement, $matches);
            $table = $matches[1] ?? 'unknown';
            echo "Clearing table: {$table}...\n";
        } else if (stripos($statement, 'SET') === 0) {
            echo "Setting configuration...\n";
        }
        
        DB::statement($statement);
        $executed++;
        
    } catch (Exception $e) {
        $errors++;
        echo "Error in statement " . ($index + 1) . ": " . $e->getMessage() . "\n";
        echo "Statement: " . substr($statement, 0, 200) . "...\n\n";
    }
}

echo "\n==========================================\n";
echo "Import completed!\n";
echo "Executed: {$executed} statements\n";
echo "Errors: {$errors}\n";
echo "==========================================\n";

// Verify import
echo "\nVerifying data...\n";
$tables = ['continents', 'countries', 'provinces', 'counties', 'districts', 'cities', 'regions', 'neighborhoods', 'streets', 'alleies', 'groups'];

foreach ($tables as $table) {
    try {
        $count = DB::table($table)->count();
        echo "Table '{$table}': {$count} records\n";
    } catch (Exception $e) {
        echo "Table '{$table}': Error - " . $e->getMessage() . "\n";
    }
}
