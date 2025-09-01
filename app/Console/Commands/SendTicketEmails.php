<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use App\Notifications\TicketPdfNotification;
use Illuminate\Console\Command;

class SendTicketEmails extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'tickets:send-emails 
                           {--ticket-id= : Send email for specific ticket ID}
                           {--status=paid : Send emails for tickets with specific status (paid/unpaid/all)}
                           {--recent= : Send emails for tickets created in the last X hours}
                           {--dry-run : Show what would be sent without actually sending}';

    /**
     * The console command description.
     */
    protected $description = 'Send PDF ticket emails to users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ticketId = $this->option('ticket-id');
        $status = $this->option('status');
        $recent = $this->option('recent');
        $dryRun = $this->option('dry-run');

        $query = Ticket::with(['user', 'event']);

        if ($ticketId) {
            $query->where('id', $ticketId);
        } else {
            if ($status !== 'all') {
                $query->where('payment_status', $status);
            }
            
            if ($recent) {
                $query->where('created_at', '>=', now()->subHours((int)$recent));
            }
        }

        $tickets = $query->get();

        if ($tickets->isEmpty()) {
            $this->info('No tickets found matching the criteria.');
            return;
        }

        $this->info("Found {$tickets->count()} ticket(s) to process.");

        if ($dryRun) {
            $this->info('DRY RUN - No emails will actually be sent:');
            $this->table(
                ['ID', 'Code', 'User', 'Email', 'Event', 'Status', 'Created'],
                $tickets->map(function ($ticket) {
                    return [
                        $ticket->id,
                        $ticket->ticket_code,
                        $ticket->user->name,
                        $ticket->user->email,
                        $ticket->event->title,
                        $ticket->payment_status,
                        $ticket->created_at->format('Y-m-d H:i'),
                    ];
                })->toArray()
            );
            return;
        }

        $sent = 0;
        $failed = 0;

        foreach ($tickets as $ticket) {
            try {
                $this->line("Sending email for ticket {$ticket->ticket_code} to {$ticket->user->email}...");
                $ticket->user->notify(new TicketPdfNotification($ticket));
                $sent++;
                $this->info("✓ Sent successfully");
            } catch (\Exception $e) {
                $failed++;
                $this->error("✗ Failed: " . $e->getMessage());
            }
        }

        $this->info("\n📧 Email sending completed:");
        $this->info("✅ Sent: {$sent}");
        if ($failed > 0) {
            $this->error("❌ Failed: {$failed}");
        }
    }
}
