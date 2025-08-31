@extends('layouts.app')
@section('title','Your Ticket')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
  <h1 class="text-2xl font-bold">Ticket — {{ $ticket->event->title }}</h1>

  <div class="ticket border rounded-xl p-4 mt-4 bg-white shadow">
    @if($ticket->event->banner)
      <img src="{{ $ticket->event->banner }}" alt="banner" style="width:100%;max-height:260px;object-fit:cover;border-radius:10px">
    @endif

    <p class="mt-3"><strong>When:</strong>
      {{ optional($ticket->event->starts_at)->format('M d, Y g:i A') }}
      @if($ticket->event->ends_at) – {{ $ticket->event->ends_at->format('M d, Y g:i A') }} @endif
    </p>
    <p><strong>Venue:</strong> {{ $ticket->event->venue ?? $ticket->event->location }}</p>
    <p><strong>Quantity:</strong> {{ $ticket->quantity }}</p>
    @if($ticket->ticket_number)
    <p><strong>Ticket Number:</strong> {{ $ticket->ticket_number }}</p>
    @endif
    <p><strong>Payment:</strong> {{ str_replace('_',' ', $ticket->payment_option) }} — <strong>{{ ucfirst($ticket->payment_status) }}</strong></p>

    <div class="mt-4">
      <strong>QR Code:</strong><br>
      @if($ticket->qr_path && is_file(storage_path('app/public/'.$ticket->qr_path)))
        @if(str_ends_with($ticket->qr_path, '.svg'))
          {!! file_get_contents(storage_path('app/public/'.$ticket->qr_path)) !!}
        @else
          <img src="{{ asset('storage/'.$ticket->qr_path) }}" alt="QR Code" style="width:200px;height:200px;">
        @endif
      @endif
      <div style="margin-top:6px;font-size:12px">Code: {{ $ticket->ticket_code }}</div>
    </div>

    <div class="mt-6 flex gap-3">
      <a class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700"
         href="{{ route('tickets.download', $ticket) }}">
        Download PDF
      </a>
      <a class="inline-flex items-center px-4 py-2 rounded-lg bg-gray-100 text-gray-800 font-semibold hover:bg-gray-200"
         href="{{ route('events.show', $ticket->event) }}">
        Back to Event
      </a>
    </div>
  </div>
</div>
@endsection
