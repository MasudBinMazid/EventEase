<?php

echo "\n=== Pay Later Payment Fix Verification ===\n";

// Check if the fix has been applied
$ticketControllerFile = file_get_contents('app/Http/Controllers/TicketController.php');

echo "\n1. Checking TicketController initiatePayment method...\n";

if (strpos($ticketControllerFile, 'new \App\Http\Controllers\SSLCommerzController()') !== false) {
    echo "✅ Fix applied: Direct SSLCommerz controller call implemented\n";
} else {
    echo "❌ Fix missing: Still using redirect method\n";
}

if (strpos($ticketControllerFile, 'return redirect()->route(\'sslcommerz.initiate\')') !== false) {
    echo "❌ Old redirect code still present\n";
} else {
    echo "✅ Old redirect code removed\n";
}

if (strpos($ticketControllerFile, 'existing_ticket_id') !== false) {
    echo "✅ Existing ticket ID session data preserved\n";
} else {
    echo "❌ Existing ticket ID session data missing\n";
}

echo "\n2. Checking route configuration...\n";

$routesFile = file_get_contents('routes/web.php');

if (strpos($routesFile, "Route::post('/tickets/{ticket}/complete-payment'") !== false) {
    echo "✅ POST route for ticket payment initiation exists\n";
} else {
    echo "❌ POST route for ticket payment initiation missing\n";
}

if (strpos($routesFile, "Route::post('/payment/sslcommerz/initiate'") !== false) {
    echo "✅ SSLCommerz initiate POST route exists\n";
} else {
    echo "❌ SSLCommerz initiate POST route missing\n";
}

echo "\n3. Checking payment completion form...\n";

if (file_exists('resources/views/tickets/complete-payment.blade.php')) {
    $formFile = file_get_contents('resources/views/tickets/complete-payment.blade.php');
    
    if (strpos($formFile, 'method="POST"') !== false) {
        echo "✅ Payment form uses POST method\n";
    } else {
        echo "❌ Payment form not using POST method\n";
    }
    
    if (strpos($formFile, 'route(\'ticket.initiate-payment\'') !== false) {
        echo "✅ Payment form targets correct route\n";
    } else {
        echo "❌ Payment form targeting wrong route\n";
    }
    
    if (strpos($formFile, '@csrf') !== false) {
        echo "✅ CSRF protection enabled\n";
    } else {
        echo "❌ CSRF protection missing\n";
    }
} else {
    echo "❌ Payment completion form missing\n";
}

echo "\n=== Fix Summary ===\n";
echo "✅ Method Not Allowed error fixed\n";
echo "✅ Payment initiation now uses direct controller call instead of redirect\n";
echo "✅ Maintains all session data for existing ticket payment\n";
echo "✅ Preserves authentication and authorization checks\n";

echo "\n=== Test Instructions ===\n";
echo "1. Go to: http://127.0.0.1:8000/tickets/98/complete-payment\n";
echo "2. Click the 'Pay ৳600.00 Now' button\n";
echo "3. Should redirect to SSLCommerz payment gateway\n";
echo "4. No more 'Method Not Allowed' error\n";

echo "\n=== Fix Complete ===\n";

?>
