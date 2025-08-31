<?php

use App\Console\Commands\ConvertQrCodesToPng;
use App\Models\Ticket;
use App\Services\QrCodeService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Register the QR code conversion command
Artisan::command('tickets:convert-qr-to-png {--force : Force conversion even if PNG already exists}', function () {
    $tickets = Ticket::whereNotNull('qr_path')
        ->where('qr_path', 'like', '%.svg')
        ->get();

    if ($tickets->count() === 0) {
        $this->info('No SVG QR codes found to convert.');
        return 0;
    }

    $this->info("Found {$tickets->count()} tickets with SVG QR codes.");

    $converted = 0;
    $skipped = 0;

    foreach ($tickets as $ticket) {
        $svgPath = $ticket->qr_path;
        $pngPath = str_replace('.svg', '.png', $svgPath);

        // Check if PNG already exists (unless force flag is used)
        if (!$this->option('force') && Storage::disk('public')->exists($pngPath)) {
            $this->line("Skipping {$ticket->ticket_code} - PNG already exists");
            $skipped++;
            continue;
        }

            try {
                // Generate new PNG QR code with compact format
                $qrData = $ticket->ticket_code . '|' . $ticket->user_id . '|' . $ticket->event_id . '|' . $ticket->payment_status;
                
                $pngBinary = QrCodeService::generatePngBinary($qrData);            // Save PNG
            Storage::disk('public')->put($pngPath, $pngBinary);

            // Update ticket record
            $ticket->qr_path = $pngPath;
            $ticket->save();

            $this->line("✓ Converted {$ticket->ticket_code}");
            $converted++;

            // Optionally remove old SVG file
            if (Storage::disk('public')->exists($svgPath)) {
                Storage::disk('public')->delete($svgPath);
                $this->line("  - Removed old SVG file");
            }

        } catch (\Exception $e) {
            $this->error("Failed to convert {$ticket->ticket_code}: " . $e->getMessage());
        }
    }

    $this->info("\nConversion complete:");
    $this->info("  Converted: $converted");
    $this->info("  Skipped: $skipped");

    return 0;
})->purpose('Convert existing SVG QR codes to PNG format for better PDF compatibility');
