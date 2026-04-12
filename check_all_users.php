<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = \App\Models\User::all();

echo "Total User: " . count($users) . "\n\n";

foreach ($users as $user) {
    echo "ID: " . $user->id . " | Nama: " . $user->nama . " | Email: " . $user->email . " | Role: " . $user->role . " | Status: " . $user->status . "\n";
}
