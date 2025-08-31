@extends('layouts.app')
@section('title','Checkout')

{{-- Add Tailwind for a modern look (safe to keep alongside your existing CSS) --}}
@section('extra-css')
  <script src="https://cdn.tailwindcss.com"></script>
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
