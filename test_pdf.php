<?php

require __DIR__ . '/vendor/autoload.php';

// Initialize Laravel app
$app = require_once __DIR__ . '/bootstrap/app.php';

// Boot the application
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$app->boot();

use App\Services\QrCodeService;
use Barryvdh\DomPDF\Facade\Pdf;

echo "Testing PDF generation with QR code...\n";

try {
    // Generate test QR code
    $testCode = 'TKT-' . strtoupper(bin2hex(random_bytes(4)));
    echo "Test ticket code: $testCode\n";
    
    $qrDataUrl = QrCodeService::generateBase64Png($testCode);
    echo "QR code generated successfully\n";
    
    // Create a simple HTML template for testing
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Test Ticket</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 40px; }
            .ticket { border: 1px solid #ccc; padding: 20px; border-radius: 10px; }
            .qr-container { text-align: center; margin: 20px 0; }
            .qr-code { width: 200px; height: 200px; }
        </style>
    </head>
    <body>
        <div class="ticket">
            <h1>Test Ticket</h1>
            <p><strong>Code:</strong> ' . $testCode . '</p>
            <div class="qr-container">
                <h3>QR Code</h3>
                <img src="' . $qrDataUrl . '" alt="QR Code" class="qr-code">
                <p>' . $testCode . '</p>
            </div>
        </div>
    </body>
    </html>';
    
    // Generate PDF
    $pdf = Pdf::loadHTML($html);
    
    // Save to storage for testing
    $filename = 'test_ticket_' . time() . '.pdf';
    $pdfPath = storage_path('app/public/' . $filename);
    $pdf->save($pdfPath);
    
    echo "PDF generated successfully: $filename\n";
    echo "File size: " . filesize($pdfPath) . " bytes\n";
    echo "File saved to: $pdfPath\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "Test completed.\n";
