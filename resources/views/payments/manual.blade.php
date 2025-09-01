@extends('layouts.app')
@section('title','Manual Payment')

@section('extra-css')
  <script src="https://cdn.tailwindcss.com"></script>
@endsection

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
  <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
    <div class="px-6 py-5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
      <h1 class="text-xl md:text-2xl font-semibold">Complete Payment</h1>
      <p class="opacity-90">Event: <strong>{{ $event->title }}</strong></p>
    </div>

    <div class="p-6 space-y-6">
      <div class="rounded-xl border p-4">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div>
            <p class="text-sm text-gray-500">Quantity</p>
            <p class="text-lg font-semibold">{{ (int) $checkout['qty'] }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Price per ticket</p>
            <p class="text-lg font-semibold">{{ number_format((float)$event->price, 2) }} BDT</p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Total to pay</p>
            <p class="text-2xl font-bold">{{ number_format((float)$checkout['total'], 2) }} BDT</p>
          </div>
        </div>
      </div>

      <!-- SSLCommerz Payment Gateway -->
      <div class="rounded-xl border-2 border-indigo-200 bg-indigo-50 p-4">
        <h2 class="text-lg font-semibold mb-3 text-indigo-800">💳 Pay with Card/Mobile Banking</h2>
        <p class="text-sm text-indigo-600 mb-4">Secure payment via SSLCommerz - Supports all major cards and mobile banking</p>
        
        <form method="POST" action="{{ route('sslcommerz.initiate') }}">
          @csrf
          <input type="hidden" name="event_id" value="{{ $checkout['event_id'] }}">
          <input type="hidden" name="qty" value="{{ $checkout['qty'] }}">
          
          <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
            </svg>
            Pay Now with SSLCommerz
          </button>
        </form>
      </div>

      <!-- OR Divider -->
      <div class="flex items-center gap-4">
        <div class="flex-1 h-px bg-gray-300"></div>
        <span class="text-gray-500 font-medium">OR</span>
        <div class="flex-1 h-px bg-gray-300"></div>
      </div>

      <div class="rounded-xl border p-4">
        <h2 class="text-lg font-semibold mb-3">Send money manually to one of these numbers</h2>

        <div class="space-y-3">
          <div class="flex items-center justify-between rounded-lg border p-3">
            <div>
              <p class="font-medium">bKash (Personal)</p>
              <p class="text-gray-600">{{ $bkash }}</p>
            </div>
            <button type="button" class="px-3 py-2 rounded-lg border text-sm"
                    onclick="navigator.clipboard.writeText('{{ $bkash }}')">Copy</button>
          </div>

          <div class="flex items-center justify-between rounded-lg border p-3">
            <div>
              <p class="font-medium">Nagad (Personal)</p>
              <p class="text-gray-600">{{ $nagad }}</p>
            </div>
            <button type="button" class="px-3 py-2 rounded-lg border text-sm"
                    onclick="navigator.clipboard.writeText('{{ $nagad }}')">Copy</button>
          </div>

          <div class="flex items-center justify-between rounded-lg border p-3">
            <div>
              <p class="font-medium">Rocket (DBBL)</p>
              <p class="text-gray-600">{{ $rocket }}</p>
            </div>
            <button type="button" class="px-3 py-2 rounded-lg border text-sm"
                    onclick="navigator.clipboard.writeText('{{ $rocket }}')">Copy</button>
          </div>
        </div>

        <div class="mt-4 text-sm text-gray-600">
          <ul class="list-disc ml-5 space-y-1">
            <li>Send exactly <strong>{{ number_format((float)$checkout['total'], 2) }} BDT</strong>.</li>
            <li>Use <strong>reference: {{ 'EVT'.$event->id }}</strong> if available in your app.</li>
            <li>Keep a screenshot for your records!</li>
          </ul>
        </div>
      </div>

      <!-- Manual Payment Confirmation -->
      <div class="rounded-xl border p-4">
        <h2 class="text-lg font-semibold mb-4 text-gray-800">📝 Manual Payment Confirmation</h2>
        <p class="text-sm text-gray-600 mb-4">If you paid manually using mobile banking, please provide the transaction details below:</p>

        {{-- Payment details form --}}
        <form method="POST" action="{{ route('payments.manual.confirm') }}" class="space-y-4" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <label class="block">
            <span class="text-sm text-gray-700">Transaction Number <span class="text-red-500">*</span></span>
            <input type="text" name="txn_id" value="{{ old('txn_id') }}"
                   class="mt-1 block w-full rounded-lg border px-3 py-2" required maxlength="100">
            @error('txn_id') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
          </label>

          <label class="block">
            <span class="text-sm text-gray-700">Paid From Number (bKash/Nagad/Rocket) <span class="text-red-500">*</span></span>
            <input type="text" name="payer_number" value="{{ old('payer_number') }}"
                   class="mt-1 block w-full rounded-lg border px-3 py-2" required maxlength="30">
            @error('payer_number') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
          </label>
        </div>

        <label class="block">
          <span class="text-sm text-gray-700">Upload Screenshot <span class="text-red-500">*</span></span>
          <input type="file" name="proof" accept=".jpg,.jpeg,.png,.webp"
                 class="mt-1 block w-full rounded-lg border px-3 py-2 bg-white" required>
          <p class="text-xs text-gray-500 mt-1">JPEG/PNG/WEBP, max 2MB.</p>
          @error('proof') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
        </label>

        <button type="submit"
          class="w-full inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-3 text-white font-semibold shadow hover:bg-indigo-700">
          Yes, I Paid — Generate My Ticket
        </button>

        <div class="text-center">
          <a href="{{ route('tickets.checkout', ['event_id' => $event->id, 'qty' => $checkout['qty']]) }}"
             class="text-sm text-gray-600 hover:underline">Go back to checkout</a>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
