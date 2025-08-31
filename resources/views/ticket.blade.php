@extends('layouts.app')
@section('title', 'Your Ticket')

@section('extra-css')
  <link rel="stylesheet" href="{{ asset('assets/css/ticket.css') }}">
@endsection

@section('content')
<div class="ticket-container">
  @if(session('success'))
    <div class="ticket-success-message">
      {{ session('success') }}
    </div>
  @endif

  <div class="ticket-card">
    @if($ticket->event->banner)
      <img src="{{ asset($ticket->event->banner) }}"
           alt="Banner" class="ticket-banner">
    @endif

    <div class="ticket-content">
      <div class="ticket-header">
        <h2 class="ticket-title">{{ $ticket->event->title }}</h2>
      </div>
      
      <div class="ticket-details">
        <div class="ticket-detail-item">
          <div class="ticket-detail-label">When</div>
          <div class="ticket-detail-value">
            {{ optional($ticket->event->starts_at)->format('M d, Y g:i A') }}
            @if($ticket->event->ends_at) – {{ $ticket->event->ends_at->format('M d, Y g:i A') }} @endif
          </div>
        </div>
        <div class="ticket-detail-item">
          <div class="ticket-detail-label">Where</div>
          <div class="ticket-detail-value">{{ $ticket->event->location }}</div>
        </div>
        <div class="ticket-detail-item">
          <div class="ticket-detail-label">Quantity</div>
          <div class="ticket-detail-value">{{ $ticket->quantity }}</div>
        </div>
        <div class="ticket-detail-item">
          <div class="ticket-detail-label">Total</div>
          <div class="ticket-detail-value">৳{{ number_format($ticket->total_amount,2) }}</div>
        </div>
        <div class="ticket-detail-item">
          <div class="ticket-detail-label">Payment</div>
          <div class="ticket-detail-value">{{ strtoupper($ticket->payment_option) }} — {{ strtoupper($ticket->payment_status) }}</div>
        </div>
        <div class="ticket-detail-item">
          <div class="ticket-detail-label">Ticket Code</div>
          <div class="ticket-detail-value">{{ $ticket->ticket_code }}</div>
        </div>
      </div>

      <div class="qr-section">
        @if($ticket->qr_path)
          <h3 class="qr-title">Scan QR Code at Event</h3>
          <div class="qr-code">
            <img src="{{ asset('storage/'.$ticket->qr_path) }}" alt="QR Code" style="width:180px;height:180px;">
          </div>
          <p class="qr-instruction">Show this QR code at the event entrance</p>
        @endif
      </div>
    </div>
  </div>

  <div class="ticket-container">
    <a href="{{ route('events.index') }}" class="download-btn">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3" stroke="currentColor" stroke-width="2"/>
      </svg>
      Back to Events
    </a>
  </div>
</div>
@endsection
