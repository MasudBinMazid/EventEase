@extends('layouts.app')

@section('title', 'Complete Payment - ' . $ticket->event->title)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
  <div class="max-w-4xl mx-auto px-4">
    
    <!-- Header -->
    <div class="text-center mb-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-2">Complete Your Payment</h1>
      <p class="text-gray-600">Secure your ticket by completing the payment process</p>
    </div>

    <!-- Payment Card -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
      
      <!-- Event Header -->
      <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-6">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-2xl font-bold">{{ $ticket->event->title }}</h2>
            <p class="text-indigo-200 mt-1">
              📅 {{ $ticket->event->starts_at ? $ticket->event->starts_at->format('l, F j, Y g:i A') : 'TBA' }}
            </p>
            <p class="text-indigo-200">
              📍 {{ $ticket->event->venue ?? $ticket->event->location ?? 'TBA' }}
            </p>
          </div>
          <div class="text-right">
            <div class="text-3xl font-bold">৳{{ number_format($ticket->total_amount, 2) }}</div>
            <div class="text-indigo-200">{{ $ticket->quantity }} {{ $ticket->quantity > 1 ? 'tickets' : 'ticket' }}</div>
          </div>
        </div>
      </div>

      <!-- Ticket Details -->
      <div class="p-6">
        <div class="grid md:grid-cols-2 gap-6 mb-6">
          
          <!-- Left Column -->
          <div class="space-y-4">
            <div class="border border-gray-200 rounded-lg p-4">
              <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                🎫 Ticket Information
              </h3>
              <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                  <span class="text-gray-600">Ticket Code:</span>
                  <span class="font-mono font-semibold">{{ $ticket->ticket_code }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Quantity:</span>
                  <span class="font-semibold">{{ $ticket->quantity }}</span>
                </div>
                @if($ticket->ticketType)
                <div class="flex justify-between">
                  <span class="text-gray-600">Type:</span>
                  <span class="font-semibold">{{ $ticket->ticketType->name }}</span>
                </div>
                @endif
                <div class="flex justify-between">
                  <span class="text-gray-600">Unit Price:</span>
                  <span class="font-semibold">৳{{ number_format($ticket->unit_price, 2) }}</span>
                </div>
                <div class="flex justify-between border-t pt-2">
                  <span class="text-gray-600 font-semibold">Total Amount:</span>
                  <span class="font-bold text-lg">৳{{ number_format($ticket->total_amount, 2) }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Column -->
          <div class="space-y-4">
            <div class="border border-gray-200 rounded-lg p-4">
              <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                💳 Payment Status
              </h3>
              <div class="space-y-3">
                <div class="flex items-center justify-between">
                  <span class="text-gray-600">Current Status:</span>
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                    ⏳ Payment Pending
                  </span>
                </div>
                <div class="text-sm text-gray-600">
                  Your ticket has been reserved. Complete the payment to confirm your booking.
                </div>
              </div>
            </div>

            <div class="border border-green-200 bg-green-50 rounded-lg p-4">
              <h3 class="font-semibold text-green-900 mb-2 flex items-center">
                🔒 Secure Payment
              </h3>
              <div class="text-sm text-green-800">
                Your payment will be processed securely through SSLCommerz. All major credit cards, debit cards, and mobile banking are supported.
              </div>
              <div class="flex gap-2 mt-3">
                <span class="px-2 py-1 bg-white rounded text-xs font-medium">Visa</span>
                <span class="px-2 py-1 bg-white rounded text-xs font-medium">MasterCard</span>
                <span class="px-2 py-1 bg-white rounded text-xs font-medium">bKash</span>
                <span class="px-2 py-1 bg-white rounded text-xs font-medium">Nagad</span>
                <span class="px-2 py-1 bg-white rounded text-xs font-medium">Rocket</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="border-t pt-6">
          <div class="flex gap-4 justify-center">
            <a href="{{ route('tickets.show', $ticket) }}" 
               class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
              View Ticket Details
            </a>
            
            <form action="{{ route('ticket.initiate-payment', $ticket) }}" method="POST" class="inline">
              @csrf
              <button type="submit" 
                      class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                💳 Pay ৳{{ number_format($ticket->total_amount, 2) }} Now
              </button>
            </form>
          </div>
          
          <p class="text-center text-sm text-gray-500 mt-4">
            You will be redirected to SSLCommerz secure payment gateway
          </p>
        </div>
      </div>
    </div>

    <!-- Additional Information -->
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
      <h3 class="font-semibold text-gray-900 mb-3">📱 Important Information</h3>
      <div class="text-gray-700 space-y-2">
        <p>• Your ticket is currently reserved and will be confirmed after successful payment</p>
        <p>• You will receive a confirmation email with your valid ticket after payment completion</p>
        <p>• Bring this ticket (printed or on mobile) and a valid ID to the event</p>
        <p>• For any questions, please contact our support team</p>
      </div>
    </div>

  </div>
</div>
@endsection

@section('styles')
<style>
  .fade-in {
    animation: fadeIn 0.5s ease-in-out;
  }
  
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }
</style>
@endsection
