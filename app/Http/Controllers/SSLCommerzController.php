<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\TempTransaction;
use App\Services\SSLCommerzService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SSLCommerzController extends Controller
{
    protected $sslcommerz;

    public function __construct(SSLCommerzService $sslcommerz)
    {
        $this->sslcommerz = $sslcommerz;
    }

    /**
     * Initiate SSLCommerz payment
     */
    public function initiatePayment(Request $request)
    {
        Log::info('SSLCommerz Payment Initiation Started', [
            'request_data' => $request->all(),
            'user_id' => auth()->id(),
            'session_data' => session('checkout')
        ]);

        try {
            // Validate the request
            $data = $request->validate([
                'event_id' => 'required|exists:events,id',
                'qty' => 'required|integer|min:1',
                'ticket_type_id' => 'nullable|exists:ticket_types,id',
            ]);

            Log::info('SSLCommerz validation passed', ['validated_data' => $data]);
            // Check if SSLCommerz is configured
            if (!$this->sslcommerz->isConfigured()) {
                return redirect()->back()
                    ->with('error', 'Payment gateway is not configured. Please contact support.');
            }

            // Get event details
            $event = Event::findOrFail($data['event_id']);
            $quantity = (int)$data['qty'];
            
            // Calculate amount based on ticket type or event price
            $ticketType = null;
            $unitPrice = $event->price;
            
            if (!empty($data['ticket_type_id'])) {
                $ticketType = $event->ticketTypes()->where('id', $data['ticket_type_id'])->first();
                if (!$ticketType || !$ticketType->hasQuantityAvailable($quantity)) {
                    return redirect()->back()
                        ->with('error', 'Selected ticket type is not available or insufficient quantity.');
                }
                $unitPrice = $ticketType->price;
            }
            
            $amount = $unitPrice * $quantity;

            // Create a unique transaction ID
            $transactionId = 'TXN-' . time() . '-' . Str::random(8);

            // Store in database first (more reliable than sessions for external redirects)
            $tempTransaction = TempTransaction::create([
                'transaction_id' => $transactionId,
                'user_id' => auth()->id(),
                'event_id' => $event->id,
                'quantity' => $quantity,
                'amount' => $amount,
                'status' => 'pending',
                'data' => [
                    'user_name' => auth()->user()->name,
                    'user_email' => auth()->user()->email,
                    'event_title' => $event->title,
                    'ticket_type_id' => $ticketType ? $ticketType->id : null,
                    'ticket_type_name' => $ticketType ? $ticketType->name : null,
                    'unit_price' => $unitPrice,
                    'created_at' => now()->toISOString()
                ]
            ]);

            Log::info('Transaction record created', [
                'transaction_id' => $transactionId,
                'temp_transaction_id' => $tempTransaction->id
            ]);

            // Also store in session as backup
            session()->put('sslcommerz_transaction', [
                'temp_transaction_id' => $tempTransaction->id,
                'transaction_id' => $transactionId,
                'event_id' => $event->id,
                'user_id' => auth()->id(),
                'quantity' => $quantity,
                'amount' => $amount,
                'ticket_type_id' => $ticketType ? $ticketType->id : null,
                'unit_price' => $unitPrice,
                'created_at' => now(),
            ]);

            // Prepare payment data
            $productName = $event->title . ' - Event Tickets (' . $quantity . ' tickets)';
            if ($ticketType) {
                $productName = $event->title . ' - ' . $ticketType->name . ' (' . $quantity . ' tickets)';
            }
            
            $paymentData = [
                'transaction_id' => $transactionId,
                'amount' => $amount,
                'product_name' => $productName,
                'quantity' => $quantity,
                'customer_name' => auth()->user()->name,
                'customer_email' => auth()->user()->email,
                'customer_phone' => auth()->user()->phone ?? 'N/A',
                'customer_address' => 'Dhaka, Bangladesh',
                'customer_city' => 'Dhaka',
                'success_url' => route('sslcommerz.success'),
                'fail_url' => route('sslcommerz.fail'),
                'cancel_url' => route('sslcommerz.cancel'),
                'ipn_url' => route('sslcommerz.ipn'),
            ];

            // Initiate payment with SSLCommerz
            $result = $this->sslcommerz->initiatePayment($paymentData);

            if ($result['success']) {
                // Update transaction status
                $tempTransaction->update([
                    'status' => 'processing',
                    'data' => array_merge($tempTransaction->data ?? [], ['gateway_response' => $result])
                ]);
                
                Log::info('Payment initiation successful, redirecting to gateway');
                // Redirect to SSLCommerz payment page
                return redirect($result['payment_url']);
            } else {
                
                // Mark transaction as failed
                $tempTransaction->update([
                    'status' => 'failed',
                    'data' => array_merge($tempTransaction->data ?? [], ['error' => $result['message']])
                ]);
                
                Log::error('SSLCommerz Payment Initiation Failed', [
                    'temp_transaction_id' => $tempTransaction->id,
                    'user_id' => auth()->id(),
                    'event_id' => $event->id,
                    'error' => $result['message']
                ]);

                return redirect()->back()
                    ->with('error', 'Failed to initiate payment: ' . $result['message']);
            }

        } catch (\Exception $e) {
            Log::error('SSLCommerz Payment Exception', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred while processing payment. Please try again.');
        }
    }

    /**
     * Handle successful payment
     */
    public function paymentSuccess(Request $request)
    {
        try {
            // Log the incoming request for debugging
            Log::info('SSLCommerz Success Callback Received', [
                'request_data' => $request->all(),
                'headers' => $request->headers->all()
            ]);

            $tranId = $request->input('tran_id');
            $valId = $request->input('val_id');
            $amount = $request->input('amount');
            $cardType = $request->input('card_type');
            $storeAmount = $request->input('store_amount');
            $bankTranId = $request->input('bank_tran_id');
            $status = $request->input('status');

            if (empty($tranId) || empty($valId)) {
                Log::warning('SSLCommerz Success: Missing required parameters', [
                    'tran_id' => $tranId,
                    'val_id' => $valId,
                    'all_params' => $request->all()
                ]);
                return redirect()->route('events.index')
                    ->with('error', 'Invalid payment response. Missing transaction details.');
            }

            // Find transaction record in database by transaction ID
            $tempTransaction = TempTransaction::with(['user', 'event'])
                ->where('transaction_id', $tranId)
                ->first();

            if (!$tempTransaction) {
                Log::warning('SSLCommerz Success: Transaction record not found', [
                    'tran_id' => $tranId,
                    'val_id' => $valId
                ]);
                return redirect()->route('events.index')
                    ->with('error', 'Payment successful but transaction not found. Please contact support with transaction ID: ' . $tranId);
            }

            Log::info('Transaction record found', [
                'temp_transaction_id' => $tempTransaction->id,
                'user_id' => $tempTransaction->user_id,
                'event_id' => $tempTransaction->event_id
            ]);

            // Validate the payment with SSLCommerz
            $validation = $this->sslcommerz->validatePayment($valId, $amount);

            // In sandbox mode, validation might fail due to SSLCommerz server issues
            // but payment status 'VALID' from callback is usually reliable
            $paymentIsValid = $validation['valid'] || 
                            (env('SSLCOMMERZ_ENVIRONMENT') === 'sandbox' && $status === 'VALID');

            if (!$paymentIsValid) {
                Log::warning('SSLCommerz Payment Validation Failed', [
                    'tran_id' => $tranId,
                    'val_id' => $valId,
                    'validation_result' => $validation,
                    'callback_status' => $status,
                    'environment' => env('SSLCOMMERZ_ENVIRONMENT')
                ]);

                // Mark transaction as failed
                $tempTransaction->update([
                    'status' => 'failed',
                    'data' => array_merge($tempTransaction->data ?? [], [
                        'validation_failed' => $validation,
                        'callback_status' => $status
                    ])
                ]);

                return redirect()->route('events.index')
                    ->with('error', 'Payment validation failed. Please contact support with transaction ID: ' . $tranId);
            }

            Log::info('Payment validation successful or bypassed in sandbox', [
                'validation_result' => $validation,
                'callback_status' => $status,
                'environment' => env('SSLCOMMERZ_ENVIRONMENT')
            ]);

            // Payment is valid, create the ticket
            Log::info('Creating ticket for validated payment', [
                'temp_transaction_id' => $tempTransaction->id,
                'user_id' => $tempTransaction->user_id,
                'event_id' => $tempTransaction->event_id
            ]);
            
            // Use transaction to ensure atomicity
            DB::transaction(function () use ($tempTransaction, $tranId, $valId, $bankTranId, $cardType) {
                // Login the user to create the ticket
                Log::info('Before login attempt', [
                    'current_auth' => auth()->id(),
                    'target_user_id' => $tempTransaction->user_id,
                    'user_exists' => $tempTransaction->user ? true : false
                ]);

                auth()->login($tempTransaction->user);
                
                Log::info('After login attempt', [
                    'auth_check' => auth()->check(),
                    'auth_id' => auth()->id(),
                    'session_id' => session()->getId()
                ]);
                
                // Create ticket using TicketController method with ticket type
                $ticketTypeId = isset($tempTransaction->data['ticket_type_id']) ? $tempTransaction->data['ticket_type_id'] : null;
                $ticketType = null;
                if ($ticketTypeId) {
                    $ticketType = \App\Models\TicketType::find($ticketTypeId);
                }
                $ticket = app(\App\Http\Controllers\TicketController::class)
                    ->createTicketAndQr($tempTransaction->event, $tempTransaction->quantity, 'pay_now', 'paid', $ticketType);

                Log::info('Ticket created successfully', [
                    'ticket_id' => $ticket->id,
                    'ticket_user_id' => $ticket->user_id,
                    'current_auth' => auth()->id()
                ]);

                // Update ticket with payment details
                $ticket->update([
                    'payment_txn_id' => $tranId,
                    'sslcommerz_val_id' => $valId,
                    'sslcommerz_bank_tran_id' => $bankTranId,
                    'sslcommerz_card_type' => $cardType,
                    'payment_verified_at' => now(),
                    'payment_method' => 'sslcommerz',
                ]);

                // Update transaction as completed
                $tempTransaction->update([
                    'status' => 'completed',
                    'data' => array_merge($tempTransaction->data ?? [], [
                        'ticket_id' => $ticket->id,
                        'completed_at' => now()->toISOString(),
                        'sslcommerz_val_id' => $valId,
                        'sslcommerz_bank_tran_id' => $bankTranId,
                        'sslcommerz_card_type' => $cardType
                    ])
                ]);

                // Clear session data
                session()->forget('sslcommerz_transaction');
                session()->forget('checkout');

                // Store ticket in session for success page
                session()->put('payment_success_ticket', $ticket->id);
                
                Log::info('SSLCommerz Payment Successful - Ticket Created', [
                    'ticket_id' => $ticket->id,
                    'tran_id' => $tranId,
                    'user_id' => $ticket->user_id,
                    'temp_transaction_id' => $tempTransaction->id
                ]);
            });

            return redirect()->route('sslcommerz.success.page')
                ->with('success', 'Payment successful! Your tickets have been generated.');

        } catch (\Exception $e) {
            Log::error('SSLCommerz Payment Success Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'tran_id' => $tranId ?? 'unknown'
            ]);

            // Mark transaction as failed if we found one
            if (isset($tempTransaction)) {
                $tempTransaction->update([
                    'status' => 'failed',
                    'data' => array_merge($tempTransaction->data ?? [], ['error' => $e->getMessage()])
                ]);
            }

            return redirect()->route('events.index')
                ->with('error', 'Payment processing failed. Please contact support.');
        }
    }

    /**
     * Handle failed payment
     */
    public function paymentFail(Request $request)
    {
        $tranId = $request->input('tran_id');
        $failedReason = $request->input('failedreason', 'Payment failed');

        Log::info('SSLCommerz Payment Failed', [
            'tran_id' => $tranId,
            'failed_reason' => $failedReason,
            'user_id' => auth()->id()
        ]);

        // Update temp transaction status if found
        if ($tranId) {
            $tempTransaction = TempTransaction::where('transaction_id', $tranId)->first();
            if ($tempTransaction) {
                $tempTransaction->update([
                    'status' => 'failed',
                    'data' => array_merge($tempTransaction->data ?? [], [
                        'failed_reason' => $failedReason,
                        'failed_at' => now()->toISOString()
                    ])
                ]);
            }
        }

        // Clear session data
        session()->forget('sslcommerz_transaction');

        return redirect()->route('dashboard')
            ->with('error', 'Payment failed: ' . $failedReason . '. Please try again.');
    }

    /**
     * Handle cancelled payment
     */
    public function paymentCancel(Request $request)
    {
        $tranId = $request->input('tran_id');

        Log::info('SSLCommerz Payment Cancelled', [
            'tran_id' => $tranId,
            'user_id' => auth()->id()
        ]);

        // Update temp transaction status if found
        if ($tranId) {
            $tempTransaction = TempTransaction::where('transaction_id', $tranId)->first();
            if ($tempTransaction) {
                $tempTransaction->update([
                    'status' => 'cancelled',
                    'data' => array_merge($tempTransaction->data ?? [], [
                        'cancelled_at' => now()->toISOString()
                    ])
                ]);
            }
        }

        // Clear session data
        session()->forget('sslcommerz_transaction');

        return redirect()->route('dashboard')
            ->with('warning', 'Payment was cancelled. You can try again anytime.');
    }

    /**
     * Handle IPN (Instant Payment Notification)
     */
    public function paymentIPN(Request $request)
    {
        try {
            $tranId = $request->input('tran_id');
            $valId = $request->input('val_id');
            $amount = $request->input('amount');
            $status = $request->input('status');

            Log::info('SSLCommerz IPN Received', [
                'tran_id' => $tranId,
                'val_id' => $valId,
                'amount' => $amount,
                'status' => $status,
                'full_request' => $request->all()
            ]);

            // Validate the payment
            if ($status === 'VALID' && $valId) {
                $validation = $this->sslcommerz->validatePayment($valId, $amount);
                
                if ($validation['valid']) {
                    // Find ticket by transaction ID and update if needed
                    $ticket = Ticket::where('payment_txn_id', $tranId)->first();
                    
                    if ($ticket && $ticket->payment_status !== 'paid') {
                        $ticket->update([
                            'payment_status' => 'paid',
                            'sslcommerz_val_id' => $valId,
                            'payment_verified_at' => now(),
                        ]);
                        
                        Log::info('SSLCommerz IPN: Ticket updated via IPN', [
                            'ticket_id' => $ticket->id,
                            'tran_id' => $tranId
                        ]);
                    }
                    
                    return response('VERIFIED', 200);
                }
            }

            return response('FAILED', 400);

        } catch (\Exception $e) {
            Log::error('SSLCommerz IPN Exception', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response('ERROR', 500);
        }
    }

    /**
     * Show payment success page
     */
    public function paymentSuccessPage()
    {
        $ticketId = session('payment_success_ticket');
        
        if (!$ticketId) {
            Log::warning('Payment Success Page: No ticket ID in session', [
                'user_id' => auth()->id(),
                'session_data' => session()->all()
            ]);
            return redirect()->route('dashboard')
                ->with('error', 'No ticket information found. Please check your ticket history.');
        }

        try {
            $ticket = Ticket::with(['event', 'user', 'ticketType'])->findOrFail($ticketId);

            // Ensure the ticket belongs to the current user
            if ($ticket->user_id !== auth()->id()) {
                Log::warning('Payment Success Page: Unauthorized ticket access', [
                    'user_id' => auth()->id(),
                    'ticket_user_id' => $ticket->user_id,
                    'ticket_id' => $ticketId
                ]);
                return redirect()->route('dashboard')
                    ->with('error', 'Unauthorized access to ticket.');
            }

            return view('payments.success', compact('ticket'));
            
        } catch (\Exception $e) {
            Log::error('Payment Success Page Error', [
                'error' => $e->getMessage(),
                'ticket_id' => $ticketId,
                'user_id' => auth()->id()
            ]);
            
            return redirect()->route('dashboard')
                ->with('error', 'Unable to display ticket details. Please contact support.');
        }
    }

    /**
     * Check payment status (for AJAX calls)
     */
    public function checkPaymentStatus(Request $request)
    {
        $tranId = $request->input('tran_id');
        
        if (!$tranId) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction ID required'
            ], 400);
        }

        $ticket = Ticket::where('payment_txn_id', $tranId)->first();

        if ($ticket) {
            return response()->json([
                'success' => true,
                'status' => $ticket->payment_status,
                'ticket_id' => $ticket->id,
                'ticket_code' => $ticket->ticket_code
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Transaction not found'
        ], 404);
    }
}
