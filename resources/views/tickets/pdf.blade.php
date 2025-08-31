<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>{{ $ticket->ticket_code }}</title>
  <style>
    /* Use a PDF-safe font with good glyph coverage */
    @font-face {
      font-family: 'DejaVu Sans';
      font-style: normal;
      font-weight: normal;
      src: url("{{ public_path('fonts/DejaVuSans.ttf') }}") format('truetype');
    }
    body { font-family: "DejaVu Sans", sans-serif; color:#111; font-size:12px; line-height:1.45; }
    .wrap   { border:1px solid #e5e7eb; border-radius:10px; overflow:hidden; }
    .header { padding:16px 18px; background:#f3f4f6; border-bottom:1px solid #e5e7eb; text-align:center; }
    .ticket-code { margin:0; font-size:16px; color:#374151; font-weight:700; }
    .title  { margin:4px 0 0 0; font-size:20px; }
    .meta   { margin-top:4px; color:#6b7280; }

    .banner { width:100%; height:210px; object-fit:cover; display:block; }
    .section{ padding:16px 18px; }
    .grid   { display:table; width:100%; table-layout:fixed; }
    .col    { display:table-cell; vertical-align:top; padding-right:12px; }
    .col:last-child{ padding-right:0; }

    .block  { border:1px solid #e5e7eb; border-radius:8px; padding:12px; margin-top:10px; }
    .label  { font-size:11px; color:#6b7280; text-transform:uppercase; letter-spacing:.04em; margin:0 0 4px 0; }
    .value  { margin:0; font-weight:600; color:#111827; }
    .muted  { color:#6b7280; }

    .qrwrap { text-align:center; padding:12px; border:1px dashed #d1d5db; border-radius:8px; }
    .qr     { width:200px; height:200px; display:block; margin:0 auto; }
    .code   { margin-top:6px; font-weight:700; letter-spacing:.03em; }

    .footer { margin-top:14px; padding:10px 18px; text-align:center; font-size:10px; color:#6b7280; border-top:1px solid #e5e7eb; }
    .row    { margin-top:6px; }
    .desc   { white-space:pre-wrap; }
  </style>
</head>
<body>
  <div class="wrap">
    {{-- Header --}}
    <div class="header">
      <h2 class="ticket-code">{{ $ticket->ticket_code }}</h2>
      <h1 class="title">{{ $ticket->event->title }}</h1>
      <div class="meta">Official Ticket · {{ $ticket->created_at?->format('M d, Y') }}</div>
    </div>

    {{-- Banner --}}
    @php
      // Prefer local public file; if not available and remote is enabled, allow external URL
      $bannerPath = null;
      $banner = $ticket->event->banner ?? $ticket->event->banner_path ?? null;
      if ($banner) {
          $candidate = ltrim($banner, '/');
          $local = public_path($candidate);
          if (is_file($local)) { $bannerPath = $local; }
      }
    @endphp
    @if($bannerPath)
      <img class="banner" src="{{ $bannerPath }}" alt="Event Banner">
    @elseif(!empty($banner))
      {{-- If dompdf 'isRemoteEnabled' is true, this will render from URL --}}
      <img class="banner" src="{{ $banner }}" alt="Event Banner">
    @endif

    {{-- Main sections --}}
    <div class="section">
      <div class="grid">
        <div class="col" style="width:60%;">
          <div class="block">
            <p class="label">When</p>
            <p class="value">
              {{ optional($ticket->event->starts_at)->format('l, M d, Y g:i A') }}
              @if($ticket->event->ends_at)
                <span class="muted"><br>until</span> {{ $ticket->event->ends_at->format('l, M d, Y g:i A') }}
              @endif
            </p>
          </div>

          <div class="block">
            <p class="label">Where</p>
            <p class="value">{{ $ticket->event->venue ?? $ticket->event->location }}</p>
          </div>

          <div class="block">
            <p class="label">Purchaser</p>
            <p class="value">
              {{ $ticket->user->name ?? 'Guest' }}
              <span class="muted">&nbsp;·&nbsp;{{ $ticket->user->email ?? '' }}</span>
            </p>
            <div class="row muted">Purchased: {{ $ticket->created_at?->format('M d, Y g:i A') }}</div>
          </div>

          <div class="block">
            <p class="label">Ticket Details</p>
            <p class="value">Quantity: {{ $ticket->quantity }}</p>
            <div class="row">Price per ticket: ${{ number_format($ticket->event->price, 2) }}</div>
            <div class="row">Payment: {{ str_replace('_',' ', $ticket->payment_option) }} ({{ $ticket->payment_status }})</div>
            <div class="row" style="font-weight:700;">Total Paid: ${{ number_format($ticket->total_amount, 2) }}</div>
          </div>

        </div>

        <div class="col" style="width:40%;">
          <div class="qrwrap">
            {{-- Generate QR code as base64 data URL for PDF using pure PHP library --}}
            @php
              try {
                // Create compact QR data for size optimization: ticket_code|user_id|event_id|payment_status
                $qrData = $ticket->ticket_code . '|' . $ticket->user_id . '|' . $ticket->event_id . '|' . $ticket->payment_status;
                
                $qrDataUrl = \App\Services\QrCodeService::generateBase64Png($qrData);
              } catch (\Exception $e) {
                $qrDataUrl = null;
              }
            @endphp
            
            @if($qrDataUrl)
              <img src="{{ $qrDataUrl }}" alt="QR Code" class="qr" style="width:200px;height:200px;">
            @else
              {{-- Fallback text-based representation --}}
              <div style="width:200px;height:200px;border:2px solid #666;display:flex;align-items:center;justify-content:center;margin:0 auto;background:#f9f9f9;">
                <div style="text-align:center;font-family:monospace;font-size:10px;line-height:1.2;">
                  QR CODE<br>
                  <strong>{{ $ticket->ticket_code }}</strong><br>
                  <small>Scan at venue</small>
                </div>
              </div>
            @endif
            <div class="code">{{ $ticket->ticket_code }}</div>
          </div>

          <div class="block">
            <p class="label">Event</p>
            <p class="value">{{ $ticket->event->title }}</p>
          </div>

          <div class="block">
            <p class="label">Ticket #</p>
            <p class="value">{{ $ticket->ticket_number ?: $ticket->ticket_code }}</p>
          </div>
        </div>
      </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
      This Event Hosted by EventEase.<br>
      <strong>Terms &amp; Conditions:</strong> No refund · Late attend not allowed
    </div>
  </div>
</body>
</html>
