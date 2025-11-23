<?php
/**
 * Test API for Tehran regions
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Simulate API request for Tehran regions
$request = Illuminate\Http\Request::create('/api/locations?level=region&parent_id=city_399', 'GET');
$response = $kernel->handle($request);

echo "Testing API: /api/locations?level=region&parent_id=city_399\n";
echo "==========================================================\n\n";

$content = $response->getContent();
$data = json_decode($content, true);

echo "Response status: " . $response->getStatusCode() . "\n";
echo "Number of regions: " . count($data) . "\n\n";

if (count($data) > 0) {
    echo "✓ SUCCESS! Tehran regions found:\n";
    foreach ($data as $region) {
        echo "  - {$region['name']} (id={$region['id']})\n";
    }
} else {
    echo "✗ FAILED! No regions returned.\n";
    echo "Response: " . $content . "\n";
}

echo "\n==========================================================\n";

$kernel->terminate($request, $response);
