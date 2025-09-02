<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Services\QrCodeService;
use App\Notifications\TicketPdfNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketController extends Controller
{
    public function start(Request $request, Event $event)
    {
        $qty = max(1, (int)$request->input('quantity', 1));
        $ticketTypeId = $request->input('ticket_type_id');

        if (!auth()->check()) {
            return redirect()->route('login')->with('status', 'Please log in to buy tickets.');
        }

        // Check if event is available for booking
        if ($event->isSoldOut()) {
            return redirect()->route('events.show', $event)
                ->with('error', 'This event is sold out.');
        }

        return redirect()->route('tickets.checkout', [
            'event_id' => $event->id,
            'qty'      => $qty,
            'ticket_type_id' => $ticketTypeId,
        ]);
    }

    public function checkout(Request $request)
    {
        $event = Event::findOrFail($request->integer('event_id'));
        $qty   = max(1, (int)$request->integer('qty', 1));
        $ticketTypeId = $request->input('ticket_type_id');
        
        // For free events, go directly to checkout
        if ($event->isFree()) {
            $total = 0;
            $selectedTicketType = null;
        } else {
            // For paid events, calculate based on ticket type or event price
            if ($ticketTypeId) {
                $selectedTicketType = $event->ticketTypes()->where('id', $ticketTypeId)->first();
                if (!$selectedTicketType || !$selectedTicketType->hasQuantityAvailable($qty)) {
                    return redirect()->route('events.show', $event)
                        ->with('error', 'Selected ticket type is not available or insufficient quantity.');
                }
                $total = $selectedTicketType->price * $qty;
            } else {
                // Use event base price if no specific ticket type
                $selectedTicketType = null;
                $total = $event->price * $qty;
            }
        }

        $allowed = match($event->purchase_option ?? 'both') {
            'pay_now'   => ['pay_now'],
            'pay_later' => ['pay_later'],
            default     => ['pay_now','pay_later'],
        };

        // For free events, only allow "get ticket" (which is like pay_later)
        if ($event->isFree()) {
            $allowed = ['pay_later'];
        }

        return view('tickets.checkout_new', compact('event','qty','total','allowed','selectedTicketType'));
    }

    /**
     * POST /checkout/confirm
     * - pay_later: create ticket immediately (as before)
     * - pay_now:   DO NOT create ticket; store checkout in session & show manual payment page
     */
    public function confirm(Request $request)
    {
        $data = $request->validate([
            'event_id' => 'required|exists:events,id',
            'qty'      => 'required|integer|min:1',
            'method'   => 'required|in:pay_now,pay_later',
            'ticket_type_id' => 'nullable|exists:ticket_types,id',
        ]);

        $event  = Event::findOrFail((int)$data['event_id']);
        $qty    = (int)$data['qty'];
        $method = $data['method'];
        $ticketTypeId = $data['ticket_type_id'] ?? null;

        // Get ticket type and validate availability
        $ticketType = null;
        $unitPrice = $event->price; // Default to event price
        
        if ($ticketTypeId) {
            $ticketType = $event->ticketTypes()->where('id', $ticketTypeId)->first();
            if (!$ticketType || !$ticketType->hasQuantityAvailable($qty)) {
                return back()->with('error', 'Selected ticket type is not available or insufficient quantity.');
            }
            $unitPrice = $ticketType->price;
        }

        if ($method === 'pay_later' || $event->isFree()) {
            // Create the ticket immediately (unpaid for paid events, paid for free events)
            $paymentStatus = $event->isFree() ? 'paid' : 'unpaid';
            $ticket = $this->createTicketAndQr($event, $qty, 'pay_later', $paymentStatus, $ticketType);
            
            // Update ticket type quantity if applicable
            if ($ticketType && !$event->isFree()) {
                $ticketType->increment('quantity_sold', $qty);
                $ticketType->updateStatus();
            }
            
            return redirect()->route('tickets.show', $ticket);
        }

        // PAY NOW → manual page (no ticket yet)
        session()->put('checkout', [
            'event_id' => $event->id,
            'qty'      => $qty,
            'user_id'  => auth()->id(),
            'total'    => $unitPrice * $qty,
            'ticket_type_id' => $ticketTypeId,
            'unit_price' => $unitPrice,
        ]);

        return redirect()->route('payments.manual');
    }

    public function show(Ticket $ticket)
    {
        abort_unless(auth()->check() && auth()->id() === $ticket->user_id, 403);
        return view('tickets.show', compact('ticket'));
    }

    public function download(Ticket $ticket)
    {
        abort_unless(auth()->check() && auth()->id() === $ticket->user_id, 403);

        $pdf = Pdf::loadView('tickets.pdf', ['ticket' => $ticket]);
        return $pdf->download($ticket->ticket_code.'.pdf');
    }

    /**
     * Verify a ticket using QR code scan
     * This is a public route for event organizers to verify tickets
     */
    public function verify(string $ticketCode)
    {
        // Handle both formats: compact (TKT-XXX|user|event|status) or simple (TKT-XXX)
        $parts = explode('|', $ticketCode);
        $actualTicketCode = $parts[0]; // First part is always the ticket code
        
        $ticket = Ticket::with(['event', 'user', 'ticketType'])->where('ticket_code', $actualTicketCode)->first();
        
        if (!$ticket) {
            return response()->json([
                'valid' => false,
                'message' => 'Ticket not found',
                'status' => 'invalid'
            ], 404);
        }

        // Additional validation for compact format
        if (count($parts) > 1) {
            $qrUserId = (int)$parts[1];
            $qrEventId = (int)$parts[2];
            $qrPaymentStatus = $parts[3] ?? '';
            
            // Validate QR data matches database
            if ($ticket->user_id !== $qrUserId || 
                $ticket->event_id !== $qrEventId || 
                $ticket->payment_status !== $qrPaymentStatus) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Invalid QR code - data mismatch',
                    'status' => 'tampered'
                ], 400);
            }
        }

        $isValid = $ticket->payment_status === 'paid';
        
        return response()->json([
            'valid' => $isValid,
            'message' => $isValid ? 'Valid ticket' : 'Ticket payment not confirmed',
            'status' => $ticket->payment_status,
            'data' => [
                'ticket_code' => $ticket->ticket_code,
                'ticket_number' => $ticket->ticket_number,
                'event_title' => $ticket->event->title,
                'event_date' => $ticket->event->starts_at?->format('M d, Y g:i A'),
                'venue' => $ticket->event->venue ?? $ticket->event->location,
                'holder_name' => $ticket->user->name,
                'holder_email' => $ticket->user->email,
                'quantity' => $ticket->quantity,
                'ticket_type' => $ticket->ticketType ? $ticket->ticketType->name : null,
                'ticket_type_description' => $ticket->ticketType ? $ticket->ticketType->description : null,
                'unit_price' => $ticket->unit_price ?? $ticket->event->price,
                'total_amount' => $ticket->total_amount,
                'issued_at' => $ticket->created_at->format('M d, Y g:i A'),
                'payment_verified_at' => $ticket->payment_verified_at?->format('M d, Y g:i A')
            ]
        ]);
    }

    /**
     * Show ticket verification form for event organizers
     */
    public function verifyForm()
    {
        return view('tickets.verify');
    }

    /**
     * Helper: creates ticket, generates QR code using pure PHP library
     * (made PUBLIC so PaymentController can reuse it)
     */
    public function createTicketAndQr(Event $event, int $qty, string $paymentOption, string $paymentStatus, $ticketType = null): Ticket
    {
        $unitPrice = $ticketType ? $ticketType->price : $event->price;
        $total = $unitPrice * $qty;

        $ticket = Ticket::create([
            'user_id'        => auth()->id(),
            'event_id'       => $event->id,
            'ticket_type_id' => $ticketType ? $ticketType->id : null,
            'quantity'       => $qty,
            'total_amount'   => $total,
            'unit_price'     => $unitPrice,
            'payment_option' => $paymentOption, // 'pay_now' | 'pay_later'
            'payment_status' => $paymentStatus, // 'paid' | 'unpaid'
            'ticket_code'    => 'TKT-' . strtoupper(Str::random(8)),
            'ticket_number'  => 'TN-' . time() . '-' . strtoupper(Str::random(4)), // Add ticket number
        ]);

        // Generate QR code with essential ticket data (optimized for size)
        $qrData = $ticket->ticket_code . '|' . $ticket->user_id . '|' . $ticket->event_id . '|' . $ticket->payment_status;

        try {
            $qrPngBinary = QrCodeService::generatePngBinary($qrData);
            $path = 'tickets/'.$ticket->ticket_code.'.png';
            Storage::disk('public')->put($path, $qrPngBinary);
        } catch (\Exception $e) {
            // Fallback to SVG if PNG fails
            $qrSvg = QrCodeService::generateSvg($qrData);
            $path = 'tickets/'.$ticket->ticket_code.'.svg';
            Storage::disk('public')->put($path, $qrSvg);
        }

        $ticket->qr_path = $path;
        $ticket->save();

        // Update ticket type quantity sold if applicable
        if ($ticketType) {
            $ticketType->increment('quantity_sold', $qty);
            
            // Update ticket type status if sold out
            if ($ticketType->quantity_sold >= $ticketType->quantity_available) {
                $ticketType->update(['status' => 'sold_out']);
            }
        }

        // Send ticket PDF via email automatically
        try {
            $ticket->user->notify(new TicketPdfNotification($ticket));
        } catch (\Exception $e) {
            // Log the error but don't fail ticket creation
            \Log::error('Failed to send ticket email notification', [
                'ticket_id' => $ticket->id,
                'user_id' => $ticket->user_id,
                'error' => $e->getMessage()
            ]);
        }

        return $ticket;
    }
}
