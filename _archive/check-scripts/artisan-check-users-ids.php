<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$conn = $app->make('db')->connection();
$counts = $conn->selectOne('SELECT COUNT(*) AS total, SUM(id IS NULL) AS nulls, COUNT(DISTINCT id) AS distinct_ids FROM users');
$dups = $counts->total - $counts->distinct_ids;

echo "Total: {$counts->total}\n";
echo "Null IDs: {$counts->nulls}\n";
echo "Duplicate IDs: {$dups}\n";
