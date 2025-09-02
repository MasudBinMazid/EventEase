<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // GET /payments/manual
    public function manual(Request $request)
    {
        $checkout = session('checkout');
        abort_unless($checkout && auth()->id() === $checkout['user_id'], 403);

        $event = Event::findOrFail($checkout['event_id']);

        $bkash  = env('PAY_BKASH',  '017XXXXXXXX');
        $nagad  = env('PAY_NAGAD',  '018XXXXXXXX');
        $rocket = env('PAY_ROCKET', '01XXXXXXXXX');

        return view('payments.manual', compact('event', 'checkout', 'bkash', 'nagad', 'rocket'));
    }

    // POST /payments/manual/confirm
    public function manualConfirm(Request $request)
    {
        $checkout = session('checkout');
        abort_unless($checkout && auth()->id() === $checkout['user_id'], 403);

        $data = $request->validate([
            'txn_id'       => 'required|string|max:100',
            'payer_number' => 'required|string|max:30',
            'proof'        => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $event = \App\Models\Event::findOrFail($checkout['event_id']);
        
        // Get ticket type if specified
        $ticketType = null;
        if (!empty($checkout['ticket_type_id'])) {
            $ticketType = $event->ticketTypes()->where('id', $checkout['ticket_type_id'])->first();
        }

        // Store screenshot
        $proofPath = $request->file('proof')->store('payment_proofs', 'public');

        // Create ticket as UNPAID (pending admin verification)
        $ticket = app(\App\Http\Controllers\TicketController::class)
            ->createTicketAndQr($event, (int) $checkout['qty'], 'pay_now', 'unpaid', $ticketType);

        // Attach payer info + proof
        $ticket->update([
            'payment_txn_id'     => $data['txn_id'],
            'payer_number'       => $data['payer_number'],
            'payment_proof_path' => $proofPath,
        ]);

        session()->forget('checkout');

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Thanks! Your payment is submitted and pending verification.');
    }
}
