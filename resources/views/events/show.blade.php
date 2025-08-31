@extends('layouts.app')
@section('title', $event->title)

@section('extra-css')
  {{-- keep your custom styles + icons --}}
  <link rel="stylesheet" href="{{ asset('assets/css/events.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection

@section('content')
@php
  use Illuminate\Support\Carbon;
  $isUpcoming = $event->starts_at ? Carbon::now()->lt($event->starts_at) : true;
  $cover = $event->banner ? asset($event->banner) : null;
@endphp

<!-- Hero -->
<section class="relative">
  <div
    class="h-64 md:h-80 w-full bg-gradient-to-r from-indigo-600 to-purple-600"
    @if($cover)
      style="background-image:url('{{ $cover }}'); background-size:cover; background-position:center;"
    @endif
  >
    <div class="h-full w-full @if($cover) bg-black/50 @endif">
      <div class="mx-auto max-w-6xl h-full px-4 flex items-end">
        <div class="pb-6">
          <div class="flex items-center gap-3">
            <span class="inline-flex items-center rounded-full bg-yellow-400/95 px-3 py-1 text-xs font-semibold text-gray-900 shadow">
              {{ $isUpcoming ? 'Upcoming' : 'Past' }}
            </span>
            <a href="{{ route('events.index') }}"
               class="inline-flex items-center text-indigo-100 hover:text-white transition">
              <i class="bi bi-arrow-left mr-2"></i> Back to Events
            </a>
          </div>
          <h1 class="mt-3 text-3xl md:text-4xl font-bold text-white drop-shadow">{{ $event->title }}</h1>

          <div class="mt-3 grid grid-cols-1 sm:grid-cols-3 gap-3 text-indigo-50">
            <div class="flex items-center gap-2 bg-white/10 backdrop-blur rounded-lg px-3 py-2">
              <i class="bi bi-calendar3 text-white"></i>
              <span class="text-sm">
                {{ optional($event->starts_at)->format('M d, Y g:i A') }}
                @if($event->ends_at) – {{ $event->ends_at->format('M d, Y g:i A') }} @endif
              </span>
            </div>
            <div class="flex items-center gap-2 bg-white/10 backdrop-blur rounded-lg px-3 py-2">
              <i class="bi bi-geo-alt text-white"></i>
              <span class="text-sm">{{ $event->venue ?? $event->location }}</span>
            </div>
            <div class="flex items-center gap-2 bg-white/10 backdrop-blur rounded-lg px-3 py-2">
              <i class="bi bi-currency-dollar text-white"></i>
              <span class="text-sm font-semibold">${{ number_format($event->price,2) }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Content -->
<section class="py-10">
  <div class="mx-auto max-w-6xl px-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <!-- Left: Details -->
      <div class="md:col-span-2 space-y-6">
        <!-- About -->
        @if($event->description)
          <article class="rounded-2xl bg-white shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900">About this event</h2>
            <p class="mt-3 leading-relaxed text-gray-700">
              {!! nl2br(e($event->description)) !!}
            </p>
          </article>
        @endif

        <!-- Info blocks -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
          <div class="rounded-2xl bg-white shadow p-6">
            <div class="flex items-center gap-2 text-gray-500 text-sm">
              <i class="bi bi-calendar2-week text-indigo-600"></i>
              When
            </div>
            <p class="mt-2 font-medium text-gray-900">
              {{ optional($event->starts_at)->format('l, M d, Y g:i A') }}
              @if($event->ends_at)<br><span class="text-gray-600">until</span> {{ $event->ends_at->format('l, M d, Y g:i A') }}@endif
            </p>
          </div>

          <div class="rounded-2xl bg-white shadow p-6">
            <div class="flex items-center gap-2 text-gray-500 text-sm">
              <i class="bi bi-geo-alt-fill text-indigo-600"></i>
              Where
            </div>
            <p class="mt-2 font-medium text-gray-900">{{ $event->venue ?? $event->location }}</p>
          </div>
        </div>

        <!-- Price note -->
        <div class="rounded-2xl bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-100 p-6">
          <div class="flex items-center gap-3">
            <i class="bi bi-ticket-perforated text-indigo-600 text-xl"></i>
            <div>
              <p class="text-sm text-gray-500">Ticket price</p>
              <p class="text-2xl font-bold text-gray-900">${{ number_format($event->price,2) }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Right: Purchase -->
      <aside class="md:col-span-1">
        <div class="md:sticky md:top-6 rounded-2xl bg-white shadow-xl p-6">
          <h2 class="text-xl font-semibold text-gray-900">Buy Ticket</h2>

          <form method="POST" action="{{ route('tickets.start', $event) }}" class="mt-4 space-y-5">
            @csrf

            <!-- Quantity -->
            <div>
              <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
              <div class="mt-1 flex items-center gap-2">
                <button type="button" id="decQty"
                        class="h-10 w-10 rounded-lg border flex items-center justify-center hover:bg-gray-50">−</button>
                <input type="number" name="quantity" id="quantity" min="1" value="1"
                       class="h-10 w-20 text-center rounded-lg border">
                <button type="button" id="incQty"
                        class="h-10 w-10 rounded-lg border flex items-center justify-center hover:bg-gray-50">+</button>
              </div>
            </div>

            <!-- Total -->
            <div class="rounded-xl bg-gray-50 p-4">
              <p class="text-sm text-gray-500">Estimated total</p>
              <p class="mt-1 text-2xl font-bold" id="totalPreview">${{ number_format($event->price,2) }}</p>
              <p class="text-xs text-gray-500">
                ${{ number_format($event->price,2) }} × <span id="qtyPreview">1</span>
              </p>
            </div>

            <!-- CTA -->
            <button
              class="w-full inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-3 text-white font-semibold shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
              type="submit">
              <i class="bi bi-box-arrow-in-right mr-2"></i> Proceed to Checkout
            </button>
          </form>

          @guest
            <p class="mt-3 text-xs text-gray-500">
              You’ll be asked to log in before checkout.
            </p>
          @endguest
        </div>
      </aside>
    </div>
  </div>
</section>
@endsection

@section('extra-js')
<script>
  (function () {
    const price    = {{ (float) $event->price }};
    const qtyInput = document.getElementById('quantity');
    const qtyPrev  = document.getElementById('qtyPreview');
    const totPrev  = document.getElementById('totalPreview');
    const inc      = document.getElementById('incQty');
    const dec      = document.getElementById('decQty');

    function clamp(v){ return Math.max(1, parseInt(v || '1', 10)); }
    function recalc(){
      const q = clamp(qtyInput.value);
      qtyInput.value = q;
      qtyPrev.textContent = q;
      totPrev.textContent = '$' + (q * price).toFixed(2);
    }

    inc?.addEventListener('click', () => { qtyInput.value = clamp(qtyInput.value) + 1; recalc(); });
    dec?.addEventListener('click', () => { qtyInput.value = clamp(qtyInput.value) - 1; recalc(); });
    qtyInput?.addEventListener('input', recalc);

    recalc();
  })();
</script>
@endsection
