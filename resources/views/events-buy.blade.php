@extends('layouts.app')
@section('title', 'Buy Ticket')

@section('extra-css')
  <link rel="stylesheet" href="{{ asset('assets/css/events-buy.css') }}">
@endsection

@section('content')
<div class="max-w-xl mx-auto px-4 py-8">
  <h2 class="text-2xl font-bold">Buy Ticket — {{ $event->title }}</h2>

  <form method="POST" action="{{ route('events.checkout', $event) }}" style="margin-top:16px;">
    @csrf
    <div style="margin-bottom:12px;">
      <label>Quantity</label>
      <input type="number" name="quantity" min="1" max="100" value="1" required>
      @error('quantity')<div style="color:red">{{ $message }}</div>@enderror
    </div>

    <div style="margin-bottom:12px;">
      <label>Payment option</label><br>
      <label><input type="radio" name="payment_option" value="pay_now" checked> Pay now</label><br>
      @if($event->allow_pay_later)
        <label><input type="radio" name="payment_option" value="pay_later"> Pay later</label>
      @endif
      @error('payment_option')<div style="color:red">{{ $message }}</div>@enderror
    </div>

    <p><strong>Price:</strong> ৳{{ number_format($event->price,2) }} × <span id="qtyPreview">1</span>
      = <strong id="totalPreview">৳{{ number_format($event->price,2) }}</strong></p>

    <button type="submit" class="btn register">Proceed Checkout</button>
  </form>
</div>

<script>
  const price = {{ (float) $event->price }};
  const qty = document.querySelector('input[name="quantity"]');
  const qtyPrev = document.getElementById('qtyPreview');
  const totPrev = document.getElementById('totalPreview');
  qty.addEventListener('input', () => {
    const q = Math.max(1, parseInt(qty.value || '1',10));
    qtyPrev.textContent = q;
    totPrev.textContent = '৳' + (q*price).toFixed(2);
  });
</script>
@endsection
