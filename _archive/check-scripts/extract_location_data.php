<?php
/**
 * Extract location tables data from SQL backup
 * This script safely extracts only INSERT statements for location tables
 */

$backupFile = __DIR__ . '/ybwztpvr_earth (7).sql';
$outputFile = __DIR__ . '/import_locations_only.sql';

// Tables we want to extract (from top level to bottom)
$locationTables = [
    'continents',
    'countries',
    'provinces',
    'counties',
    'districts',
    'cities',
    'regions',
    'neighborhoods',
    'streets',
    'alleies',
    'groups'  // اضافه کردن جدول groups
];

echo "Reading backup file...\n";
$lines = file($backupFile, FILE_IGNORE_NEW_LINES);
echo "Total lines: " . count($lines) . "\n";

$output = [];
$output[] = "-- Import ALL location tables + groups from backup (Oct 10, 2025)";
$output[] = "-- Complete location hierarchy + groups data";
$output[] = "";
$output[] = "SET FOREIGN_KEY_CHECKS=0;";
$output[] = "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";";
$output[] = "SET time_zone = \"+00:00\";";
$output[] = "";
$output[] = "-- Clear existing data (in reverse order to respect foreign keys)";
$output[] = "TRUNCATE TABLE `groups`;";
$output[] = "TRUNCATE TABLE `alleies`;";
$output[] = "TRUNCATE TABLE `streets`;";
$output[] = "TRUNCATE TABLE `neighborhoods`;";
$output[] = "TRUNCATE TABLE `regions`;";
$output[] = "TRUNCATE TABLE `cities`;";
$output[] = "TRUNCATE TABLE `districts`;";
$output[] = "TRUNCATE TABLE `counties`;";
$output[] = "TRUNCATE TABLE `provinces`;";
$output[] = "TRUNCATE TABLE `countries`;";
$output[] = "TRUNCATE TABLE `continents`;";
$output[] = "";

$inTargetTable = false;
$currentTable = null;
$insertBuffer = '';

foreach ($lines as $lineNum => $line) {
    // Check if this is an INSERT statement for one of our tables
    foreach ($locationTables as $table) {
        if (preg_match("/^INSERT INTO `{$table}`/i", $line)) {
            $inTargetTable = true;
            $currentTable = $table;
            $insertBuffer = $line;
            echo "Found INSERT for {$table} at line " . ($lineNum + 1) . "\n";
            break;
        }
    }
    
    if ($inTargetTable) {
        // If we're already capturing, add this line to buffer
        if ($line !== $insertBuffer) {
            $insertBuffer .= "\n" . $line;
        }
        
        // Check if this is the end of the INSERT statement
        if (preg_match('/;\s*$/', $line)) {
            $output[] = "-- Data for table `{$currentTable}`";
            $output[] = $insertBuffer;
            $output[] = "";
            $inTargetTable = false;
            $insertBuffer = '';
        }
    }
}

$output[] = "SET FOREIGN_KEY_CHECKS=1;";
$output[] = "-- Import completed successfully!";

file_put_contents($outputFile, implode("\n", $output));
echo "\nExtraction completed!\n";
echo "Output file: {$outputFile}\n";
echo "File size: " . number_format(filesize($outputFile)) . " bytes\n";
