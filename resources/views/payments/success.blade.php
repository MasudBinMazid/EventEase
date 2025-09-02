@extends('layouts.app')
@section('title', 'Payment Successful')

@section('extra-css')
<style>
  .success-container {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    min-height: 100vh;
    padding: 2rem 0;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .success-card {
    background: white;
    border-radius: 24px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    overflow: hidden;
    max-width: 600px;
    width: 90%;
    position: relative;
  }
  
  .success-header {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    padding: 3rem 2rem 2rem;
    text-align: center;
    color: white;
    position: relative;
  }
  
  .success-icon {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2.5rem;
    animation: bounce 2s infinite;
  }
  
  @keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-10px); }
    60% { transform: translateY(-5px); }
  }
  
  .success-title {
    font-size: 2rem;
    font-weight: 700;
    margin: 0 0 0.5rem 0;
  }
  
  .success-subtitle {
    opacity: 0.9;
    font-size: 1.1rem;
    margin: 0;
  }
  
  .success-body {
    padding: 2.5rem;
  }
  
  .ticket-summary {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: 2px solid #e2e8f0;
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 2rem;
  }
  
  .ticket-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e2e8f0;
  }
  
  .ticket-row:last-child {
    border-bottom: none;
    font-weight: 600;
    font-size: 1.1rem;
    color: #059669;
  }
  
  .ticket-label {
    color: #64748b;
    font-weight: 500;
  }
  
  .ticket-value {
    color: #1e293b;
    font-weight: 600;
  }
  
  .payment-details {
    background: #f0f9ff;
    border: 1px solid #0ea5e9;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
  }
  
  .payment-title {
    font-weight: 600;
    color: #0c4a6e;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  
  .payment-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    font-size: 0.9rem;
  }
  
  .payment-label {
    color: #075985;
  }
  
  .payment-value {
    color: #0c4a6e;
    font-weight: 500;
  }
  
  .action-buttons {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
  }
  
  .btn {
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    text-align: center;
    transition: all 0.3s ease;
    flex: 1;
    min-width: 150px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
  }
  
  .btn-primary {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: white;
    border: none;
  }
  
  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(79, 70, 229, 0.3);
    color: white;
  }
  
  .btn-outline {
    background: transparent;
    color: #4f46e5;
    border: 2px solid #4f46e5;
  }
  
  .btn-outline:hover {
    background: #4f46e5;
    color: white;
    transform: translateY(-2px);
  }
  
  .ticket-info {
    background: #fffbeb;
    border: 1px solid #f59e0b;
    border-radius: 12px;
    padding: 1rem;
    margin-top: 2rem;
    text-align: center;
  }
  
  .ticket-info-title {
    font-weight: 600;
    color: #92400e;
    margin-bottom: 0.5rem;
  }
  
  .ticket-info-text {
    color: #b45309;
    font-size: 0.9rem;
    line-height: 1.5;
  }
  
  @media (max-width: 768px) {
    .success-container {
      padding: 1rem 0;
    }
    
    .success-card {
      width: 95%;
    }
    
    .success-body {
      padding: 2rem 1.5rem;
    }
    
    .action-buttons {
      flex-direction: column;
    }
    
    .btn {
      min-width: auto;
    }
  }
</style>
@endsection

@section('content')
<div class="success-container">
  <div class="success-card">
    <!-- Success Header -->
    <div class="success-header">
      <div class="success-icon">🎉</div>
      <h1 class="success-title">Payment Successful!</h1>
      <p class="success-subtitle">Your tickets have been generated successfully</p>
    </div>

    <!-- Success Body -->
    <div class="success-body">
      
      <!-- Ticket Summary -->
      <div class="ticket-summary">
        <div class="ticket-row">
          <span class="ticket-label">Event</span>
          <span class="ticket-value">{{ $ticket->event->title }}</span>
        </div>
        <div class="ticket-row">
          <span class="ticket-label">Date & Time</span>
          <span class="ticket-value">{{ $ticket->event->starts_at->format('M d, Y g:i A') }}</span>
        </div>
        <div class="ticket-row">
          <span class="ticket-label">Venue</span>
          <span class="ticket-value">{{ $ticket->event->venue ?? $ticket->event->location }}</span>
        </div>
        <div class="ticket-row">
          <span class="ticket-label">Quantity</span>
          <span class="ticket-value">{{ $ticket->quantity }} {{ Str::plural('ticket', $ticket->quantity) }}</span>
        </div>
        @if($ticket->ticketType)
        <div class="ticket-row">
          <span class="ticket-label">Ticket Type</span>
          <span class="ticket-value">{{ $ticket->ticketType->name }}</span>
        </div>
        @endif
        <div class="ticket-row">
          <span class="ticket-label">Total Amount</span>
          <span class="ticket-value">৳{{ number_format($ticket->total_amount, 2) }}</span>
        </div>
      </div>

      <!-- Payment Details -->
      <div class="payment-details">
        <div class="payment-title">
          🔒 Payment Information
        </div>
        <div class="payment-row">
          <span class="payment-label">Transaction ID</span>
          <span class="payment-value">{{ $ticket->payment_txn_id }}</span>
        </div>
        <div class="payment-row">
          <span class="payment-label">Payment Method</span>
          <span class="payment-value">SSLCommerz</span>
        </div>
        <div class="payment-row">
          <span class="payment-label">Payment Status</span>
          <span class="payment-value">✅ Paid</span>
        </div>
        <div class="payment-row">
          <span class="payment-label">Ticket Code</span>
          <span class="payment-value">{{ $ticket->ticket_code }}</span>
        </div>
        @if($ticket->sslcommerz_card_type)
        <div class="payment-row">
          <span class="payment-label">Card Type</span>
          <span class="payment-value">{{ ucfirst($ticket->sslcommerz_card_type) }}</span>
        </div>
        @endif
      </div>

      <!-- Action Buttons -->
      <div class="action-buttons">
        <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-primary">
          🎫 View Ticket
        </a>
        <a href="{{ route('tickets.download', $ticket) }}" class="btn btn-outline">
          📥 Download PDF
        </a>
      </div>

      <!-- Additional Info -->
      <div class="ticket-info">
        <div class="ticket-info-title">📱 Important Information</div>
        <div class="ticket-info-text">
          Your digital tickets with QR codes are ready! Please save them to your device and bring them to the event. 
          The QR code will be scanned for entry verification.
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
