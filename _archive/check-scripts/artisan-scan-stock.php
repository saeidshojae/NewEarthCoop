<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

/** @var \Illuminate\Database\ConnectionInterface $conn */
$conn = $app->make('db')->connection();

$tables = ['stocks','auctions','bids','stock_transactions','wallets','wallet_transactions','holdings','holding_transactions'];
foreach ($tables as $t) {
    $exists = false;
    try {
        switch ($conn->getDriverName()) {
            case 'mysql':
                $exists = (bool) $conn->selectOne('SELECT COUNT(*) AS c FROM information_schema.tables WHERE table_schema = ? AND table_name = ?', [$conn->getDatabaseName(), $t])->c;
                break;
            case 'sqlite':
                $exists = (bool) $conn->selectOne("SELECT COUNT(*) AS c FROM sqlite_master WHERE type='table' AND name=?", [$t])->c;
                break;
            case 'pgsql':
                $exists = (bool) $conn->selectOne("SELECT COUNT(*) AS c FROM information_schema.tables WHERE table_schema = 'public' AND table_name = ?", [$t])->c;
                break;
            default:
                $exists = false;
        }
    } catch (Throwable $e) {
        $exists = false;
    }
    echo $t . ':' . ($exists ? 'YES' : 'NO') . PHP_EOL;
}
