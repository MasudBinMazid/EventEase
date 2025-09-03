<?php
require_once 'vendor/autoload.php';

// Load Laravel app
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "EventEase User Roles Summary\n";
echo "============================\n\n";

try {
    $users = \App\Models\User::orderBy('created_at', 'desc')->get();
    
    if ($users->isEmpty()) {
        echo "No users found in the system.\n";
        exit(0);
    }

    $roleCounts = [
        'admin' => 0,
        'manager' => 0,
        'organizer' => 0,
        'user' => 0
    ];

    echo "All Users:\n";
    echo str_repeat("-", 80) . "\n";
    printf("%-5s %-25s %-30s %-15s %-15s\n", "ID", "Name", "Email", "Role", "Created");
    echo str_repeat("-", 80) . "\n";

    foreach ($users as $user) {
        $role = $user->role ?? 'user';
        $roleCounts[$role]++;
        
        printf("%-5s %-25s %-30s %-15s %-15s\n", 
            $user->id,
            substr($user->name, 0, 24),
            substr($user->email, 0, 29),
            ucfirst($role),
            $user->created_at->format('M j, Y')
        );
    }

    echo str_repeat("-", 80) . "\n";
    echo "\nRole Summary:\n";
    foreach ($roleCounts as $role => $count) {
        echo "  " . ucfirst($role) . ": {$count} users\n";
    }

    echo "\nManager Features:\n";
    echo "  ✅ Full admin access except user deletion and role changes\n";
    echo "  ✅ Access URL: /manager or /dashboard (auto-redirects)\n";
    echo "  ✅ Purple-themed interface (different from admin)\n";
    echo "  ❌ Cannot delete users or change user roles\n\n";

    if ($roleCounts['manager'] == 0) {
        echo "💡 To create a manager user, use: php assign_manager_role.php\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\nDone!\n";
?>
