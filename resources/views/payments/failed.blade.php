@extends('layouts.app')
@section('title', 'Payment Failed')

@section('extra-css')
<style>
  .fail-container {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    min-height: 100vh;
    padding: 2rem 0;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .fail-card {
    background: white;
    border-radius: 24px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    overflow: hidden;
    max-width: 600px;
    width: 90%;
    position: relative;
  }
  
  .fail-header {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    padding: 3rem 2rem 2rem;
    text-align: center;
    color: white;
    position: relative;
  }
  
  .fail-icon {
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
  
  .fail-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
  }
  
  .fail-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    font-weight: 400;
  }
  
  .fail-body {
    padding: 3rem 2rem;
    text-align: center;
  }
  
  .fail-message {
    font-size: 1.1rem;
    color: #6b7280;
    margin-bottom: 2rem;
    line-height: 1.6;
  }
  
  .fail-reason {
    background: #fee2e2;
    border: 1px solid #fecaca;
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 2rem;
    color: #991b1b;
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
  
  .help-section {
    background: #f8fafc;
    border-radius: 16px;
    padding: 2rem;
    margin-top: 2rem;
    text-align: left;
  }
  
  .help-title {
    font-weight: 600;
    color: #374151;
    margin-bottom: 1rem;
    font-size: 1.1rem;
  }
  
  .help-list {
    list-style: none;
    padding: 0;
    margin: 0;
  }
  
  .help-list li {
    padding: 0.5rem 0;
    color: #6b7280;
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
  }
  
  .help-list li::before {
    content: "💡";
    flex-shrink: 0;
  }
  
  @media (max-width: 768px) {
    .fail-container {
      padding: 1rem 0;
    }
    
    .fail-card {
      width: 95%;
    }
    
    .fail-body {
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
<div class="fail-container">
  <div class="fail-card">
    <!-- Header -->
    <div class="fail-header">
      <div class="fail-icon">
        ❌
      </div>
      <h1 class="fail-title">Payment Failed</h1>
      <p class="fail-subtitle">Your payment could not be processed</p>
    </div>
    
    <!-- Body -->
    <div class="fail-body">
      <p class="fail-message">
        We're sorry, but your payment could not be completed at this time. This could be due to various reasons.
      </p>
      
      @if($failedReason)
        <div class="fail-reason">
          <strong>Reason:</strong> {{ $failedReason }}
        </div>
      @endif
      
      <!-- Action Buttons -->
      <div class="action-buttons">
        @if($eventId)
          <a href="{{ route('events.show', $eventId) }}" class="btn btn-primary">
            🔄 Try Again
          </a>
        @endif
        <a href="{{ route('events.index') }}" class="btn btn-outline">
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
      
      <!-- Help Section -->
      <div class="help-section">
        <div class="help-title">What you can do next:</div>
        <ul class="help-list">
          <li>Check your internet connection and try again</li>
          <li>Ensure your payment method has sufficient funds</li>
          <li>Try using a different payment method</li>
          <li>Contact your bank if the issue persists</li>
          <li>Contact our support team for assistance</li>
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection
