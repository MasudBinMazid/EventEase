@extends('layouts.app')

@section('title', $event->title)

@section('extra-css')
  <link rel="stylesheet" href="{{ asset('assets/css/event-details.css') }}">
@endsection

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
  <a href="{{ route('events.index') }}">&larr; Back to Events</a>

  <h1 class="text-3xl font-bold mt-3">{{ $event->title }}</h1>

  @if($event->banner)
    <img src="{{ asset($event->banner) }}"
         alt="Banner" style="max-width:100%;border-radius:12px;margin:16px 0;">
  @endif

  <p>
    <strong>When:</strong>
    {{ optional($event->starts_at)->format('M d, Y g:i A') }}
    @if($event->ends_at) – {{ $event->ends_at->format('M d, Y g:i A') }} @endif
  </p>
  <p><strong>Where:</strong> {{ $event->location }}</p>
  <p><strong>Price:</strong> ৳{{ number_format($event->price,2) }}</p>

  @if($event->description)
    <div style="margin-top:12px;">{!! nl2br(e($event->description)) !!}</div>
  @endif

  <div style="margin-top:16px;">
    {{-- Buy ticket (POST -> tickets.start) with quantity + live total --}}
    <form method="POST" action="{{ route('tickets.start', $event) }}" class="space-y-3">
      @csrf

      <div>
        <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
        <input
          type="number"
          name="quantity"
          id="quantity"
          min="1"
          value="1"
          class="mt-1 w-28 rounded-md border-gray-300 py-1.5 px-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        />
      </div>

      <p class="text-sm">
        <strong>Price:</strong> ৳{{ number_format($event->price, 2) }}
        × <span id="qtyPreview">1</span>
        = <strong id="totalPreview">৳{{ number_format($event->price, 2) }}</strong>
      </p>

      <button class="btn register" type="submit">Buy Ticket</button>
    </form>
  </div>
</div>
@endsection

@section('extra-js')
  <script>
    (function () {
      const price = {{ (float) $event->price }};
      const qtyInput = document.getElementById('quantity');
      const qtyPrev  = document.getElementById('qtyPreview');
      const totPrev  = document.getElementById('totalPreview');

      function recalc() {
        const q = Math.max(1, parseInt(qtyInput.value || '1', 10));
        qtyPrev.textContent = q;
        totPrev.textContent = '৳' + (q * price).toFixed(2);
      }
      qtyInput.addEventListener('input', recalc);
      recalc();
    })();
  </script>
@endsection
