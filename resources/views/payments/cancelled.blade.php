@extends('layouts.app')
@section('title', 'Payment Cancelled')

@section('extra-css')
<style>
  .cancel-container {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    min-height: 100vh;
    padding: 2rem 0;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .cancel-card {
    background: white;
    border-radius: 24px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    overflow: hidden;
    max-width: 600px;
    width: 90%;
    position: relative;
  }
  
  .cancel-header {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    padding: 3rem 2rem 2rem;
    text-align: center;
    color: white;
    position: relative;
  }
  
  .cancel-icon {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 2.5rem;
  }
  
  .cancel-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
  }
  
  .cancel-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    font-weight: 400;
  }
  
  .cancel-body {
    padding: 3rem 2rem;
    text-align: center;
  }
  
  .cancel-message {
    font-size: 1.1rem;
    color: #6b7280;
    margin-bottom: 2rem;
    line-height: 1.6;
  }
  
  .cancel-note {
    background: #fffbeb;
    border: 1px solid #fed7aa;
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 2rem;
    color: #92400e;
    font-weight: 500;
  }
  
  .action-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
  }
  
  .btn {
    padding: 0.75rem 2rem;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 1rem;
    min-width: 150px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
  }
  
  .btn-primary {
    background: #3b82f6;
    color: white;
  }
  
  .btn-primary:hover {
    background: #2563eb;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
  }
  
  .btn-outline {
    background: transparent;
    color: #6b7280;
    border: 2px solid #e5e7eb;
  }
  
  .btn-outline:hover {
    background: #f9fafb;
    border-color: #d1d5db;
    transform: translateY(-2px);
  }
  
  .btn-warning {
    background: #f59e0b;
    color: white;
  }
  
  .btn-warning:hover {
    background: #d97706;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3);
  }
  
  .event-info {
    background: #f8fafc;
    border-radius: 16px;
    padding: 1.5rem;
    margin-top: 2rem;
    text-align: left;
  }
  
  .event-info-title {
    font-weight: 600;
    color: #374151;
    margin-bottom: 1rem;
    font-size: 1.1rem;
  }
  
  .event-detail {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #e5e7eb;
  }
  
  .event-detail:last-child {
    border-bottom: none;
  }
  
  .event-label {
    color: #6b7280;
    font-weight: 500;
  }
  
  .event-value {
    color: #374151;
    font-weight: 600;
  }
  
  @media (max-width: 768px) {
    .cancel-container {
      padding: 1rem 0;
    }
    
    .cancel-card {
      width: 95%;
    }
    
    .cancel-body {
      padding: 2rem 1.5rem;
    }
    
    .action-buttons {
      flex-direction: column;
    }
    
    .btn {
      min-width: auto;
    }
    
    .event-detail {
      flex-direction: column;
      align-items: flex-start;
      gap: 0.25rem;
    }
  }
</style>
@endsection

@section('content')
<div class="cancel-container">
  <div class="cancel-card">
    <!-- Header -->
    <div class="cancel-header">
      <div class="cancel-icon">
        ⚠️
      </div>
      <h1 class="cancel-title">Payment Cancelled</h1>
      <p class="cancel-subtitle">You cancelled the payment process</p>
    </div>
    
    <!-- Body -->
    <div class="cancel-body">
      <p class="cancel-message">
        Your payment has been cancelled and no charges have been made to your account. You can try purchasing tickets again whenever you're ready.
      </p>
      
      <div class="cancel-note">
        ℹ️ No money has been deducted from your account
      </div>
      
      <!-- Event Information (if available) -->
      @if($event)
        <div class="event-info">
          <div class="event-info-title">Event Details</div>
          <div class="event-detail">
            <span class="event-label">Event Name</span>
            <span class="event-value">{{ $event->title }}</span>
          </div>
          @if($quantity)
            <div class="event-detail">
              <span class="event-label">Quantity</span>
              <span class="event-value">{{ $quantity }} {{ Str::plural('ticket', $quantity) }}</span>
            </div>
          @endif
          @if($totalAmount)
            <div class="event-detail">
              <span class="event-label">Total Amount</span>
              <span class="event-value">৳{{ number_format($totalAmount, 2) }}</span>
            </div>
          @endif
        </div>
      @endif
      
      <!-- Action Buttons -->
      <div class="action-buttons">
        @if($eventId)
          <a href="{{ route('events.show', $eventId) }}" class="btn btn-warning">
            🎫 Buy Tickets
          </a>
        @endif
        <a href="{{ route('events.index') }}" class="btn btn-primary">
          📅 Browse Events
        </a>
        @auth
          <a href="{{ route('dashboard') }}" class="btn btn-outline">
            🏠 Dashboard
          </a>
        @else
          <a href="{{ route('login') }}" class="btn btn-outline">
            🔑 Login
          </a>
        @endauth
      </div>
    </div>
  </div>
</div>
@endsection
