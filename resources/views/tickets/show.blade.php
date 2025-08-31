@extends('layouts.app')
@section('title','Your Ticket')

@section('extra-css')
<style>
  .ticket-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 2rem 0;
  }
  
  .ticket-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    overflow: hidden;
    position: relative;
  }
  
  .ticket-header {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    padding: 2rem;
    color: white;
    text-align: center;
    position: relative;
  }
  
  .ticket-header::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 0;
    border-left: 20px solid transparent;
    border-right: 20px solid transparent;
    border-top: 20px solid #7c3aed;
  }
  
  .ticket-title {
    font-size: 1.75rem;
    font-weight: 700;
    margin: 0;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }
  
  .ticket-subtitle {
    opacity: 0.9;
    margin-top: 0.5rem;
    font-size: 1.1rem;
  }
  
  .ticket-body {
    padding: 2.5rem;
  }
  
  .event-banner {
    width: 100%;
    max-height: 280px;
    object-fit: cover;
    border-radius: 12px;
    margin-bottom: 2rem;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
  }
  
  .info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
  }
  
  .info-card {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    padding: 1.5rem;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    position: relative;
    overflow: hidden;
  }
  
  .info-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
  }
  
  .info-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.5rem;
  }
  
  .info-value {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1e293b;
    line-height: 1.5;
  }
  
  .payment-status {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }
  
  .status-paid {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
  }
  
  .status-unpaid {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
  }
  
  .status-pending {
    background: #dbeafe;
    color: #1e40af;
    border: 1px solid #bfdbfe;
  }
  
  .qr-section {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: 2px dashed #cbd5e1;
    border-radius: 16px;
    padding: 2rem;
    text-align: center;
    margin: 2rem 0;
  }
  
  .qr-code {
    width: 200px;
    height: 200px;
    margin: 0 auto 1rem;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
  }
  
  .qr-code-text {
    font-family: 'Courier New', monospace;
    font-size: 0.875rem;
    font-weight: 600;
    color: #64748b;
    background: white;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
    display: inline-block;
  }
  
  .action-buttons {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    flex-wrap: wrap;
  }
  
  .btn-primary {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: white;
    padding: 0.875rem 1.5rem;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
  }
  
  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4);
    text-decoration: none;
    color: white;
  }
  
  .btn-secondary {
    background: white;
    color: #374151;
    padding: 0.875rem 1.5rem;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    border: 2px solid #e5e7eb;
  }
  
  .btn-secondary:hover {
    background: #f9fafb;
    border-color: #d1d5db;
    transform: translateY(-1px);
    text-decoration: none;
    color: #374151;
  }
  
  .ticket-perforations {
    position: relative;
  }
  
  .ticket-perforations::before,
  .ticket-perforations::after {
    content: '';
    position: absolute;
    left: -10px;
    width: 20px;
    height: 20px;
    background: #667eea;
    border-radius: 50%;
  }
  
  .ticket-perforations::before {
    top: -10px;
  }
  
  .ticket-perforations::after {
    bottom: -10px;
  }
  
  @media (max-width: 768px) {
    .ticket-container {
      padding: 1rem 0;
    }
    
    .ticket-header {
      padding: 1.5rem;
    }
    
    .ticket-title {
      font-size: 1.5rem;
    }
    
    .ticket-body {
      padding: 1.5rem;
    }
    
    .info-grid {
      grid-template-columns: 1fr;
      gap: 1rem;
    }
    
    .action-buttons {
      flex-direction: column;
    }
    
    .btn-primary,
    .btn-secondary {
      justify-content: center;
      text-align: center;
    }
  }
</style>
@endsection

@section('content')
<div class="ticket-container">
  <div class="max-w-4xl mx-auto px-4">
    <div class="ticket-card">
      <!-- Header -->
      <div class="ticket-header">
        <h1 class="ticket-title">{{ $ticket->event->title }}</h1>
        <div class="ticket-subtitle">Official Event Ticket</div>
      </div>

      <!-- Body -->
      <div class="ticket-body">
        <!-- Event Banner -->
        @if($ticket->event->banner)
          <img src="{{ asset($ticket->event->banner) }}" alt="Event Banner" class="event-banner">
        @endif

        <!-- Event Information Grid -->
        <div class="info-grid">
          <div class="info-card">
            <div class="info-label">📅 When</div>
            <div class="info-value">
              {{ optional($ticket->event->starts_at)->format('M d, Y g:i A') }}
              @if($ticket->event->ends_at)
                <br><small style="color: #64748b;">until {{ $ticket->event->ends_at->format('M d, Y g:i A') }}</small>
              @endif
            </div>
          </div>

          <div class="info-card">
            <div class="info-label">📍 Where</div>
            <div class="info-value">{{ $ticket->event->venue ?? $ticket->event->location }}</div>
          </div>

          <div class="info-card">
            <div class="info-label">🎫 Quantity</div>
            <div class="info-value">{{ $ticket->quantity }} {{ $ticket->quantity > 1 ? 'Tickets' : 'Ticket' }}</div>
          </div>

          @if($ticket->ticket_number)
          <div class="info-card">
            <div class="info-label">🔢 Ticket Number</div>
            <div class="info-value">{{ $ticket->ticket_number }}</div>
          </div>
          @endif

          <div class="info-card">
            <div class="info-label">💳 Payment</div>
            <div class="info-value">
              {{ str_replace('_',' ', ucwords($ticket->payment_option)) }}
              <br>
              <span class="payment-status status-{{ $ticket->payment_status }}">
                @if($ticket->payment_status === 'paid')
                  ✅ {{ ucfirst($ticket->payment_status) }}
                @elseif($ticket->payment_status === 'unpaid')
                  ⏳ {{ ucfirst($ticket->payment_status) }}
                @else
                  🔄 {{ ucfirst($ticket->payment_status) }}
                @endif
              </span>
            </div>
          </div>
        </div>

        <!-- QR Code Section -->
        <div class="qr-section ticket-perforations">
          <div class="info-label" style="text-align: center; margin-bottom: 1rem;">🎯 Your QR Code</div>
          @if($ticket->qr_path && is_file(storage_path('app/public/'.$ticket->qr_path)))
            @if(str_ends_with($ticket->qr_path, '.svg'))
              <div style="width: 200px; height: 200px; margin: 0 auto 1rem;">
                {!! file_get_contents(storage_path('app/public/'.$ticket->qr_path)) !!}
              </div>
            @else
              <img src="{{ asset('storage/'.$ticket->qr_path) }}" alt="QR Code" class="qr-code">
            @endif
          @endif
          <div class="qr-code-text">{{ $ticket->ticket_code }}</div>
          <div style="margin-top: 0.5rem; font-size: 0.875rem; color: #64748b;">
            Present this QR code at the event entrance
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
          <a href="{{ route('tickets.download', $ticket) }}" class="btn-primary">
            📄 Download PDF Ticket
          </a>
          <a href="{{ route('events.show', $ticket->event) }}" class="btn-secondary">
            ← Back to Event Details
          </a>
        </div>

        <!-- Footer Note -->
        <div style="margin-top: 2rem; padding: 1rem; background: #f8fafc; border-radius: 8px; text-align: center; color: #64748b; font-size: 0.875rem;">
          <strong>Important:</strong> Please arrive 15 minutes before the event starts. Keep this ticket accessible on your device or print a copy.
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
