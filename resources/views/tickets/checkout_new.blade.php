@extends('layouts.app')
@section('title','Checkout')

{{-- Add professional styling --}}
@section('extra-css')
<style>
  .checkout-container {
    background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
    min-height: 100vh;
    padding: 2rem 0;
  }
  
  .checkout-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    border: 1px solid #e5e7eb;
  }
  
  .checkout-header {
    background: linear-gradient(135deg, #0f172a 0%, #334155 100%);
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
    background: linear-gradient(135deg, #0f172a 0%, #334155 100%);
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
    justify-content: center;
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
    border-color: #4f46e5;
    background: #f8faff;
  }
  
  .payment-option.selected {
    border-color: #4f46e5;
    background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
    box-shadow: 0 4px 15px rgba(79, 70, 229, 0.1);
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
    background: linear-gradient(135deg, #0f172a 0%, #334155 100%);
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
    border-radius: 12px;
    border: none;
    font-size: 1.1rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px -1px rgba(8, 145, 178, 0.3);
    position: relative;
    overflow: hidden;
  }
  
  .submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(8, 145, 178, 0.4);
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
  
  .payment-info {
    margin-top: 1rem;
    padding: 1rem;
    background: #f0f9ff;
    border: 1px solid #0ea5e9;
    border-radius: 12px;
  }
  
  .info-card {
    text-align: center;
  }
  
  .info-title {
    font-weight: 600;
    color: #0c4a6e;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
  }
  
  .info-text {
    color: #075985;
    font-size: 0.8rem;
    margin-bottom: 0.75rem;
    line-height: 1.4;
  }
  
  .supported-methods {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    justify-content: center;
  }
  
  .method-badge {
    background: #0ea5e9;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.7rem;
    font-weight: 500;
  }
  
  .error-message {
    color: #dc2626;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    font-weight: 500;
  }
  
  .grid {
    display: grid;
  }
  
  .grid-cols-1 {
    grid-template-columns: repeat(1, minmax(0, 1fr));
  }
  
  @media (min-width: 1024px) {
    .lg\\:grid-cols-3 {
      grid-template-columns: repeat(3, minmax(0, 1fr));
    }
    
    .lg\\:col-span-2 {
      grid-column: span 2 / span 2;
    }
    
    .lg\\:col-span-1 {
      grid-column: span 1 / span 1;
    }
  }
  
  .gap-8 {
    gap: 2rem;
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
<div class="checkout-container">
  <div class="max-w-7xl mx-auto px-4">
    <div class="checkout-card">
      <!-- Header -->
      <div class="checkout-header">
        <h1 class="checkout-title">Secure Checkout</h1>
        <div class="checkout-subtitle">{{ $event->title }}</div>
      </div>

      <!-- Body -->
      <div class="checkout-body">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Event Summary (Left Side) -->
          <div class="lg:col-span-2 event-summary">
            @if($event->banner)
              <img src="{{ asset($event->banner) }}" alt="Event Banner" class="event-banner">
            @endif

            <div class="event-details">
              <div class="detail-card">
                <div class="detail-label">📅 Event Date & Time</div>
                <div class="detail-value">
                  {{ optional($event->starts_at)->format('M d, Y g:i A') }}
                  @if($event->ends_at)
                    <br><small style="color: #64748b;">until {{ $event->ends_at->format('M d, Y g:i A') }}</small>
                  @endif
                </div>
              </div>
              
              <div class="detail-card">
                <div class="detail-label">📍 Venue</div>
                <div class="detail-value">{{ $event->venue ?? $event->location }}</div>
              </div>
            </div>

            @if($event->description)
              <div class="about-card">
                <div class="detail-label">📋 About This Event</div>
                <div class="about-text">
                  {!! nl2br(e($event->description)) !!}
                </div>
              </div>
            @endif
          </div>

          <!-- Checkout Form (Right Side) -->
          <div class="lg:col-span-1">
            <form method="post" action="{{ route('tickets.confirm') }}" id="checkoutForm" class="checkout-form">
              @csrf
              <input type="hidden" name="event_id" value="{{ $event->id }}">

              <!-- Price Display -->
              <div class="form-section">
                <div class="price-display">
                  <div class="price-label">💰 Price per Ticket</div>
                  <div class="price-value">৳{{ number_format($event->price, 2) }}</div>
                </div>
              </div>

              <!-- Quantity Selection -->
              <div class="form-section">
                <label class="form-label">🎫 Number of Tickets</label>
                <div class="quantity-controls">
                  <button type="button" id="decQty" class="qty-btn">−</button>
                  <input type="number" name="qty" id="qty" min="1" value="{{ $qty }}" class="qty-input" readonly>
                  <button type="button" id="incQty" class="qty-btn">+</button>
                </div>
                @error('qty') <div class="error-message">{{ $message }}</div> @enderror
              </div>

              <!-- Payment Options -->
              <div class="form-section">
                <label class="form-label">💳 Payment Method</label>
                <div class="payment-options">
                  @foreach($allowed as $opt)
                    <label class="payment-option {{ $loop->first ? 'selected' : '' }}" data-value="{{ $opt }}">
                      <div class="payment-radio"></div>
                      <input type="radio" name="method" value="{{ $opt }}" {{ $loop->first ? 'checked' : '' }} style="display: none;">
                      <span class="payment-text">
                        @if($opt === 'pay_now')
                          💳 Pay Now (SSLCommerz)
                        @else
                          ⏰ Pay Later
                        @endif
                      </span>
                    </label>
                  @endforeach
                </div>
                @error('method') <div class="error-message">{{ $message }}</div> @enderror
                
                <!-- SSLCommerz Info -->
                <div id="sslcommerz-info" class="payment-info" style="display: none;">
                  <div class="info-card">
                    <div class="info-title">🔒 Secure Online Payment</div>
                    <div class="info-text">
                      Pay securely using SSLCommerz. Supports all major credit cards, debit cards, and mobile banking.
                    </div>
                    <div class="supported-methods">
                      <span class="method-badge">Visa</span>
                      <span class="method-badge">MasterCard</span>
                      <span class="method-badge">bKash</span>
                      <span class="method-badge">Nagad</span>
                      <span class="method-badge">Rocket</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Total Section -->
              <div class="total-section">
                <div class="total-label">Total Amount</div>
                <div class="total-amount" id="totalText">৳{{ number_format($total, 2) }}</div>
              </div>

              <!-- Submit Button -->
              <button type="submit" class="submit-btn" id="submitBtn">
                🎫 <span id="btnText">Confirm Booking</span>
              </button>

              <div class="checkout-note">
                <strong>🔒 Secure Checkout</strong><br>
                <span id="noteText">You'll receive your digital ticket and QR code after confirmation.</span>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('extra-js')
<script>
  (function () {
    const price = {{ (float) $event->price }};
    const qtyInput = document.getElementById('qty');
    const totalTxt = document.getElementById('totalText');
    const inc = document.getElementById('incQty');
    const dec = document.getElementById('decQty');
    const checkoutForm = document.getElementById('checkoutForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const noteText = document.getElementById('noteText');
    const sslcommerzInfo = document.getElementById('sslcommerz-info');

    function clamp(v){ return Math.max(1, parseInt(v || '1', 10)); }
    function recalc(){
      const q = clamp(qtyInput.value);
      qtyInput.value = q;
      const total = q * price;
      totalTxt.textContent = '৳' + total.toLocaleString('en-BD', {minimumFractionDigits: 2});
    }

    inc.addEventListener('click', () => { qtyInput.value = clamp(qtyInput.value) + 1; recalc(); });
    dec.addEventListener('click', () => { qtyInput.value = clamp(qtyInput.value) - 1; recalc(); });
    qtyInput.addEventListener('input', recalc);

    // Payment option selection
    const paymentOptions = document.querySelectorAll('.payment-option');
    paymentOptions.forEach(option => {
      option.addEventListener('click', () => {
        // Remove selected class from all options
        paymentOptions.forEach(opt => opt.classList.remove('selected'));
        // Add selected class to clicked option
        option.classList.add('selected');
        // Update the hidden radio input
        const radio = option.querySelector('input[type="radio"]');
        radio.checked = true;
        
        // Update form action and UI based on payment method
        updatePaymentUI(radio.value);
      });
    });

    function updatePaymentUI(method) {
      if (method === 'pay_now') {
        // SSLCommerz payment
        checkoutForm.action = '{{ route("sslcommerz.initiate") }}';
        btnText.textContent = 'Pay Now';
        noteText.textContent = 'You will be redirected to SSLCommerz secure payment gateway.';
        sslcommerzInfo.style.display = 'block';
      } else {
        // Pay later
        checkoutForm.action = '{{ route("tickets.confirm") }}';
        btnText.textContent = 'Confirm Booking';
        noteText.textContent = 'You\'ll receive your digital ticket and can pay later.';
        sslcommerzInfo.style.display = 'none';
      }
    }

    // Initialize UI based on default selection
    const selectedOption = document.querySelector('.payment-option.selected input[type="radio"]');
    if (selectedOption) {
      updatePaymentUI(selectedOption.value);
    }

    recalc();
  })();
</script>
@endsection
