<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== CONTINENTS ===\n";
$continents = DB::table('continents')->select('id', 'name')->get();
foreach ($continents as $c) {
    echo "ID: {$c->id} - {$c->name}\n";
}

echo "\n=== COUNTRIES (Iran) ===\n";
$countries = DB::table('countries')
    ->where('name', 'like', '%ایران%')
    ->orWhere('name', 'like', '%iran%')
    ->select('id', 'name', 'continent_id')
    ->get();
foreach ($countries as $c) {
    echo "ID: {$c->id} - {$c->name} (Continent: {$c->continent_id})\n";
}

echo "\n=== PROVINCES (First 5) ===\n";
$provinces = DB::table('provinces')->select('id', 'name', 'country_id')->limit(5)->get();
foreach ($provinces as $p) {
    echo "ID: {$p->id} - {$p->name} (Country: {$p->country_id})\n";
}

echo "\n=== Current register_step3.blade.php settings ===\n";
$step3File = file_get_contents(__DIR__ . '/resources/views/auth/register_step3.blade.php');
preg_match('/defaultContinentId\s*=\s*[\'"](\d+)[\'"]/', $step3File, $continentMatch);
preg_match('/defaultCountryId\s*=\s*[\'"](\d+)[\'"]/', $step3File, $countryMatch);
echo "Code expects Continent ID: " . ($continentMatch[1] ?? 'NOT FOUND') . "\n";
echo "Code expects Country ID: " . ($countryMatch[1] ?? 'NOT FOUND') . "\n";
