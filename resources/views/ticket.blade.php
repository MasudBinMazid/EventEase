@extends('layouts.app')
@section('title', 'Your Ticket')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
  @if(session('success'))
    <div style="background:#e6ffed;border:1px solid #b7f0c0;padding:8px 12px;border-radius:8px;margin-bottom:12px;">
      {{ session('success') }}
    </div>
  @endif

  <div style="border:1px solid #ddd;border-radius:12px;overflow:hidden;">
    @if($ticket->event->banner)
      <img src="{{ asset($ticket->event->banner) }}"
           alt="Banner" style="width:100%;max-height:260px;object-fit:cover;">
    @endif

    <div style="padding:16px;">
      <h2 style="margin:0 0 4px 0;">{{ $ticket->event->title }}</h2>
      <p style="margin:0 0 6px 0;">
        <strong>When:</strong>
        {{ optional($ticket->event->starts_at)->format('M d, Y g:i A') }}
        @if($ticket->event->ends_at) – {{ $ticket->event->ends_at->format('M d, Y g:i A') }} @endif
      </p>
      <p style="margin:0 0 6px 0;"><strong>Where:</strong> {{ $ticket->event->location }}</p>
      <p style="margin:0 0 6px 0;"><strong>Quantity:</strong> {{ $ticket->quantity }}</p>
      <p style="margin:0 0 6px 0;"><strong>Total:</strong> ৳{{ number_format($ticket->total_amount,2) }}</p>
      <p style="margin:0 0 6px 0;"><strong>Payment:</strong> {{ strtoupper($ticket->payment_option) }} — {{ strtoupper($ticket->payment_status) }}</p>
      <p style="margin:0 0 10px 0;"><strong>Ticket Code:</strong> {{ $ticket->ticket_code }}</p>

      <div style="display:flex;gap:16px;align-items:center;flex-wrap:wrap;">
        @if($ticket->qr_path)
          <img src="{{ asset('storage/'.$ticket->qr_path) }}" alt="QR Code" style="width:180px;height:180px;">
        @endif
      </div>
    </div>
  </div>

  <div style="margin-top:16px;">
    <a href="{{ route('events.index') }}" class="btn view">Back to Events</a>
  </div>
</div>
@endsection
