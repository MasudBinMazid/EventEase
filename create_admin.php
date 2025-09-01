<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$admin = App\Models\User::where('email', 'admin@example.com')->first();
if (!$admin) {
    $admin = App\Models\User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => bcrypt('password123'),
        'email_verified_at' => now(),
        'role' => 'admin'
    ]);
    echo "Admin user created\n";
} else {
    $admin->role = 'admin';
    $admin->save();
    echo "Admin role updated\n";
}
echo "Login credentials:\n";
echo "Email: admin@example.com\n";
echo "Password: password123\n";
echo "URL: " . url('/admin') . "\n";
