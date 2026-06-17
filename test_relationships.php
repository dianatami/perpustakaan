<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

// List all users
echo "=== All Users ===\n";
$allUsers = \App\Models\User::select('id', 'nama', 'role')->get();
foreach ($allUsers as $u) {
    $roleLabel = $u->role == 0 ? 'Anggota' : ($u->role == 1 ? 'Admin' : 'Guru');
    echo "- ID: {$u->id}, Nama: {$u->nama}, Role: {$u->role} ($roleLabel)\n";
}

// Check if relationship exists
echo "\n=== Testing Borrow Relationship ===\n";
$user = \App\Models\User::where('role', 0)->orWhere('role', 2)->first();
if ($user) {
    echo "User found: " . $user->nama . "\n";
    echo "Role: " . $user->role . " (" . ($user->role == 0 ? 'Anggota' : 'Guru') . ")\n";
    echo "Has bookrent relationship: " . (method_exists($user, 'bookrent') ? 'YES' : 'NO') . "\n";
    try {
        $count = $user->bookrent()->count();
        echo "Bookrent count: " . $count . "\n";
        echo "✅ Relationship works!\n";
    } catch (Exception $e) {
        echo "❌ ERROR: " . $e->getMessage() . "\n";
    }
} else {
    echo "❌ No anggota/guru users found\n";
}

// Check Bookrent and DetailBookrent relationship
echo "\n--- Checking Bookrent Model ---\n";
$bookrent = \App\Models\Bookrent::first();
if ($bookrent) {
    echo "Bookrent found: ID " . $bookrent->id . "\n";
    echo "Has details relationship: " . (method_exists($bookrent, 'details') ? 'YES' : 'NO') . "\n";
    try {
        $details = $bookrent->details()->count();
        echo "Details count: " . $details . "\n";
        echo "✅ Bookrent relationship works!\n";
    } catch (Exception $e) {
        echo "❌ ERROR: " . $e->getMessage() . "\n";
    }
}
