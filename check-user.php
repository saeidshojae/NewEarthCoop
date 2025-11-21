<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Check current logged in user from session (simulated)
$user = \App\Models\User::whereHas('groups')->first();
if ($user) {
    echo "User: {$user->name} (ID: {$user->id})\n";
    echo "Total groups: {$user->groups->count()}\n";
    echo "Type 0 (عمومی): {$user->groups->where('group_type', '0')->count()}\n";
    echo "Type 1 (تخصصی): {$user->groups->where('group_type', '1')->count()}\n";
    echo "Type 2 (اختصاصی): {$user->groups->where('group_type', '2')->count()}\n";
} else {
    echo "No users with groups found!\n";
}
