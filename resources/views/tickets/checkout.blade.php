@extends('layouts.app')
@section('title','Checkout')

{{-- Add modern styling --}}
@section('extra-css')
<style>
  .checkout-container {
    background: linear-gradient(135deg, #0f172a 0%, #334155 100%);
    min-height: 100vh;
    padding: 2rem 0;
  }
  
  .checkout-card {
    background: white;
    border-radius: 24px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    overflow: hidden;
    backdrop-filter: blur(10px);
  }
  
  .checkout-header {
    background: linear-gradient(135deg, #0891b2 0%, #0d9488 100%);
    padding: 2rem;
    color: white;
    position: relative;
  }
  
  .checkout-header::after {
    content: '';
    position: absolute;
    bottom: -15px;
    left: 0;
    right: 0;
    height: 30px;
    background: linear-gradient(135deg, #0891b2 0%, #0d9488 100%);
    clip-path: ellipse(100% 100% at 50% 0%);
  }
  
  .checkout-title {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }
  
  .checkout-subtitle {
    opacity: 0.9;
    margin-top: 0.5rem;
    font-size: 1.1rem;
  }
  
  .checkout-body {
    padding: 3rem;
  }
  
  .event-summary {
    margin-bottom: 3rem;
  }
  
  .event-banner {
    width: 100%;
    height: 220px;
    object-fit: cover;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
  }
  
  .event-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
  }
  
  .detail-card {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    padding: 1.5rem;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    position: relative;
    overflow: hidden;
  }
  
  .detail-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(135deg, #0891b2 0%, #0d9488 100%);
  }
  
  .detail-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }
  
  .detail-value {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1e293b;
    line-height: 1.5;
  }
  
  .about-card {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    padding: 1.5rem;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    position: relative;
    overflow: hidden;
  }
  
  .about-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(135deg, #0891b2 0%, #0d9488 100%);
  }
  
  .about-text {
    color: #475569;
    line-height: 1.6;
    margin-top: 0.5rem;
  }
  
  .checkout-form {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border: 2px solid #e2e8f0;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
  }
  
  .form-section {
    margin-bottom: 2rem;
  }
  
  .form-section:last-child {
    margin-bottom: 0;
  }
  
  .form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }
  
  .price-display {
    background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%);
    padding: 1.5rem;
    border-radius: 12px;
    border: 1px solid #fbbf24;
    text-align: center;
  }
  
  .price-label {
    font-size: 0.875rem;
    color: #92400e;
    margin-bottom: 0.5rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }
  
  .price-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #92400e;
  }
  
  .quantity-controls {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: white;
    padding: 0.75rem;
    border-radius: 12px;
    border: 2px solid #e2e8f0;
  }
  
  .qty-btn {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    border: 1px solid #d1d5db;
    background: #f9fafb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    color: #374151;
    cursor: pointer;
    transition: all 0.2s ease;
  }
  
  .qty-btn:hover {
    background: #e5e7eb;
    border-color: #9ca3af;
  }
  
  .qty-input {
    width: 80px;
    text-align: center;
    border: none;
    background: transparent;
    font-size: 1.1rem;
    font-weight: 600;
    color: #1f2937;
  }
  
  .qty-input:focus {
    outline: none;
  }
  
  .payment-options {
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }
  
  .payment-option {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: white;
    padding: 1.25rem;
    border-radius: 12px;
    border: 2px solid #e2e8f0;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
  }
  
  .payment-option:hover {
    border-color: #0891b2;
    background: #f8faff;
  }
  
  .payment-option.selected {
    border-color: #0891b2;
    background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
    box-shadow: 0 4px 15px rgba(8, 145, 178, 0.1);
  }
  
  .payment-radio {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid #d1d5db;
    background: white;
    position: relative;
    transition: all 0.2s ease;
  }
  
  .payment-option.selected .payment-radio {
    border-color: #0891b2;
    background: #0891b2;
  }
  
  .payment-option.selected .payment-radio::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: white;
  }
  
  .payment-text {
    font-weight: 600;
    color: #1f2937;
  }
  
  .payment-option.selected .payment-text {
    color: #0891b2;
  }
  
  .total-section {
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    padding: 2rem;
    border-radius: 16px;
    color: white;
    text-align: center;
    margin: 2rem 0;
    position: relative;
    overflow: hidden;
  }
  
  .total-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill=%23ffffff%3E%3Cg opacity='0.05'%3E%3Cpath d='M0 0h60v60H0z'/%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
  }
  
  .total-label {
    font-size: 1rem;
    opacity: 0.8;
    margin-bottom: 0.5rem;
    position: relative;
    z-index: 1;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }
  
  .total-amount {
    font-size: 2.5rem;
    font-weight: 700;
    position: relative;
    z-index: 1;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }
  
  .submit-btn {
    width: 100%;
    background: linear-gradient(135deg, #0891b2 0%, #0d9488 100%);
    color: white;
    padding: 1.25rem 2rem;
    border-radius: 16px;
    border: none;
    font-size: 1.1rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 10px 25px rgba(79, 70, 229, 0.3);
    position: relative;
    overflow: hidden;
  }
  
  .submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(79, 70, 229, 0.4);
  }
  
  .submit-btn:active {
    transform: translateY(0);
  }
  
  .checkout-note {
    text-align: center;
    color: #64748b;
    font-size: 0.875rem;
    margin-top: 1.5rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
  }
  
  .error-message {
    color: #dc2626;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    font-weight: 500;
  }
  
  @media (max-width: 768px) {
    .checkout-container {
      padding: 1rem 0;
    }
    
    .checkout-body {
      padding: 2rem 1.5rem;
    }
    
    .checkout-title {
      font-size: 1.75rem;
    }
    
    .event-details {
      grid-template-columns: 1fr;
      gap: 1rem;
    }
    
    .quantity-controls {
      justify-content: center;
    }
    
    .total-amount {
      font-size: 2rem;
    }
  }
</style>
@endsection

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
  <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
    <!-- Header -->
    <div class="px-6 py-5 bg-gradient-to-r from-indigo-600 to-purple-600">
      <h1 class="text-xl md:text-2xl font-semibold text-white">
        Checkout — {{ $event->title }}
      </h1>
    </div>

    <!-- Body -->
    <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
      <!-- Event Summary -->
      <div class="md:col-span-2 space-y-4">
        @if($event->banner)
          <img src="{{ asset($event->banner) }}" alt="banner"
               class="w-full h-44 object-cover rounded-xl">
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="rounded-lg border p-4">
            <p class="text-sm text-gray-500">When</p>
            <p class="font-medium">
              {{ optional($event->starts_at)->format('M d, Y g:i A') }}
              @if($event->ends_at) – {{ $event->ends_at->format('M d, Y g:i A') }} @endif
            </p>
          </div>
          <div class="rounded-lg border p-4">
            <p class="text-sm text-gray-500">Where</p>
            <p class="font-medium">{{ $event->venue ?? $event->location }}</p>
          </div>
        </div>

        @if($event->description)
          <div class="rounded-lg border p-4">
            <p class="text-sm text-gray-500">About</p>
            <p class="mt-1 text-gray-700 leading-relaxed">
              {!! nl2br(e($event->description)) !!}
            </p>
          </div>
        @endif
      </div>

      <!-- Ticket Form -->
      <div class="md:col-span-1">
        <form method="post" action="{{ route('tickets.confirm') }}" id="checkoutForm"
              class="rounded-xl border p-4 space-y-4">
          @csrf
          <input type="hidden" name="event_id" value="{{ $event->id }}">

          <!-- Price -->
          <div>
            <p class="text-sm text-gray-500">Price per ticket</p>
            <p class="text-lg font-semibold">৳{{ number_format($event->price, 2) }}</p>
          </div>

          <!-- Quantity with stepper -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
            <div class="flex items-center gap-2">
              <button type="button" id="decQty"
                class="h-10 w-10 rounded-lg border flex items-center justify-center hover:bg-gray-50">−</button>
              <input type="number" name="qty" id="qty" min="1"
                     value="{{ $qty }}" class="h-10 w-20 text-center rounded-lg border">
              <button type="button" id="incQty"
                class="h-10 w-10 rounded-lg border flex items-center justify-center hover:bg-gray-50">+</button>
            </div>
            @error('qty') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
          </div>

          <!-- Payment options -->
          <div>
            <p class="block text-sm font-medium text-gray-700 mb-1">Payment option</p>
            <div class="space-y-2">
              @foreach($allowed as $opt)
                <label class="flex items-center gap-2">
                  <input type="radio" name="method" value="{{ $opt }}" {{ $loop->first ? 'checked' : '' }}
                         class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                  <span class="text-sm">
                    {{ $opt === 'pay_now' ? 'Pay now' : 'Pay later' }}
                  </span>
                </label>
              @endforeach
            </div>
            @error('method') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
          </div>

          <!-- Total -->
          <div class="rounded-lg bg-gray-50 p-3">
            <p class="text-sm text-gray-500">Total</p>
            <p class="text-2xl font-bold" id="totalText">৳{{ number_format($total, 2) }}</p>
          </div>

          <!-- Submit -->
          <button type="submit"
            class="w-full inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-3 text-white font-semibold shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            Proceed to Confirm
          </button>
        </form>

        <p class="mt-3 text-xs text-gray-500 text-center">
          You’ll see your ticket and QR code after confirmation.
        </p>
      </div>
    </div>
  </div>
</div>
@endsection

@section('extra-js')
<script>
  (function () {
    const price    = {{ (float) $event->price }};
    const qtyInput = document.getElementById('qty');
    const totalTxt = document.getElementById('totalText');
    const inc      = document.getElementById('incQty');
    const dec      = document.getElementById('decQty');

    function clamp(v){ return Math.max(1, parseInt(v || '1', 10)); }
    function recalc(){
      const q = clamp(qtyInput.value);
      qtyInput.value = q;
      totalTxt.textContent = '৳' + (q * price).toFixed(2);
    }

    inc.addEventListener('click', () => { qtyInput.value = clamp(qtyInput.value) + 1; recalc(); });
    dec.addEventListener('click', () => { qtyInput.value = clamp(qtyInput.value) - 1; recalc(); });
    qtyInput.addEventListener('input', recalc);

    recalc();
  })();
</script>
@endsection
