<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

/** @var \Illuminate\Database\ConnectionInterface $conn */
$conn = $app->make('db')->connection();

$status = $conn->selectOne("SHOW TABLE STATUS LIKE 'users'");
$create = $conn->selectOne("SHOW CREATE TABLE users");

echo "Engine: ".$status->Engine.PHP_EOL;
echo "RowFormat: ".$status->Row_format.PHP_EOL;
echo "Collation: ".$status->Collation.PHP_EOL;
echo "Create:\n".$create->{'Create Table'}."\n";