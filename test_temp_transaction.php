<?php

require_once __DIR__ . '/bootstrap/app.php';

use Illuminate\Foundation\Application;
use App\Models\TempTransaction;
use App\Models\User;
use App\Models\Event;

$app = new Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$status = $kernel->handle(
    $input = new Symfony\Component\Console\Input\ArgvInput,
    new Symfony\Component\Console\Output\ConsoleOutput
);

try {
    // Test if TempTransaction model works
    echo "Testing TempTransaction model...\n";
    
    // This should not fail if the migration ran successfully
    $tempTransaction = new TempTransaction();
    $tempTransaction->transaction_id = 'test_' . time();
    $tempTransaction->user_id = 1;
    $tempTransaction->event_id = 1;
    $tempTransaction->quantity = 2;
    $tempTransaction->amount = 1000;
    $tempTransaction->status = 'pending';
    $tempTransaction->data = ['test' => 'data'];
    
    echo "TempTransaction model created successfully!\n";
    echo "Transaction ID: " . $tempTransaction->transaction_id . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "This might mean the migration hasn't run yet.\n";
}
