<?php
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Load Laravel app
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "EventEase Manager Role Assignment Script\n";
echo "========================================\n\n";

// Get user email
echo "Enter user email to assign manager role: ";
$handle = fopen("php://stdin", "r");
$email = trim(fgets($handle));
fclose($handle);

if (empty($email)) {
    echo "Error: Email is required.\n";
    exit(1);
}

try {
    // Find user
    $user = \App\Models\User::where('email', $email)->first();
    
    if (!$user) {
        echo "Error: User with email '{$email}' not found.\n";
        exit(1);
    }

    $oldRole = $user->role ?? 'user';
    
    // Update role to manager
    $user->role = 'manager';
    $user->save();

    echo "\n✅ Success!\n";
    echo "User Details:\n";
    echo "  Name: {$user->name}\n";
    echo "  Email: {$user->email}\n";
    echo "  Previous Role: {$oldRole}\n";
    echo "  New Role: manager\n";
    echo "\nThe user can now access the manager panel at:\n";
    echo "  🔗 " . url('/dashboard') . " (will redirect to manager panel)\n";
    echo "  🔗 " . url('/manager') . " (direct access)\n\n";

    echo "Manager Permissions:\n";
    echo "  ✅ Full admin access to events, blogs, messages, sales, etc.\n";
    echo "  ✅ View all users\n";
    echo "  ❌ Cannot delete users\n";
    echo "  ❌ Cannot change user roles\n\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "Done!\n";
?>
