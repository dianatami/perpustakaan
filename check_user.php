<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::first();

if ($user) {
    echo "User ditemukan:\n";
    echo "ID: " . $user->id . "\n";
    echo "Nama: " . $user->nama . "\n";
    echo "Email: " . $user->email . "\n";
    echo "Role: " . $user->role . "\n";
    echo "Status: " . $user->status . "\n";
    echo "Password Hash: " . substr($user->password, 0, 20) . "...\n";
} else {
    echo "Tidak ada user di database.\n";
}
