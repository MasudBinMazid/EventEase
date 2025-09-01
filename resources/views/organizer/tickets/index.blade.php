@extends('organizer.layout')
@section('title', 'Tickets - ' . $event->title)

@section('content')
<div class="organizer-page">
  <!-- Page Header -->
  <div class="organizer-header">
    <div>
      <h1 class="organizer-title">Tickets for {{ $event->title }}</h1>
      <p class="organizer-subtitle">All ticket purchases for your event</p>
    </div>
    <div style="display: flex; gap: 0.5rem;">
      <a href="{{ route('organizer.events.show', $event) }}" class="btn btn-outline">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="15,18 9,12 15,6"/>
        </svg>
        Back to Event
      </a>
    </div>
  </div>

  <!-- Event Quick Info -->
  <div style="background: var(--card); padding: 1rem; border-radius: 8px; border: 1px solid var(--border); margin-bottom: 2rem;">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: center;">
      <div>
        <strong style="color: var(--text-light);">Event Date:</strong>
        <div>{{ $event->starts_at ? $event->starts_at->format('M j, Y g:i A') : 'TBA' }}</div>
      </div>
      <div>
        <strong style="color: var(--text-light);">Location:</strong>
        <div>{{ $event->location ?: 'Not specified' }}</div>
      </div>
      <div>
        <strong style="color: var(--text-light);">Ticket Price:</strong>
        <div>
          @if($event->price > 0)
            ৳{{ number_format($event->price, 2) }}
          @else
            Free
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Tickets List -->
  <div class="organizer-card">
    <div class="card-header">
      <h3 class="card-title">All Tickets</h3>
      <p class="card-subtitle">{{ $tickets->total() }} tickets found</p>
    </div>
    
    <div class="card-body" style="padding: 0;">
      @if($tickets->isEmpty())
        <div style="padding: 3rem; text-align: center; color: var(--text-light);">
          <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="margin-bottom: 1rem; opacity: 0.5;">
            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
          </svg>
          <p>No tickets have been purchased for this event yet.</p>
        </div>
      @else
        <div style="overflow-x: auto;">
          <table class="organizer-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Ticket Code</th>
                <th>Buyer Information</th>
                <th>Quantity</th>
                <th>Amount</th>
                <th>Payment Status</th>
                <th>Purchase Date</th>
                <th>Payment Method</th>
              </tr>
            </thead>
            <tbody>
              @foreach($tickets as $ticket)
                <tr>
                  <td>
                    <span style="font-weight: 600; color: var(--primary);">#{{ $ticket->id }}</span>
                  </td>
                  <td>
                    <strong style="font-family: monospace; color: var(--text);">{{ $ticket->ticket_code }}</strong>
                    @if($ticket->ticket_number)
                      <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $ticket->ticket_number }}</div>
                    @endif
                  </td>
                  <td>
                    <div>
                      <div style="font-weight: 600;">{{ $ticket->user->name }}</div>
                      <div style="font-size: 0.875rem; color: var(--text-light);">{{ $ticket->user->email }}</div>
                      @if($ticket->user->phone)
                        <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $ticket->user->phone }}</div>
                      @endif
                    </div>
                  </td>
                  <td>
                    <span style="font-weight: 600;">{{ $ticket->quantity }}</span>
                    @if($ticket->quantity > 1)
                      <span style="font-size: 0.8rem; color: var(--text-light);">tickets</span>
                    @else
                      <span style="font-size: 0.8rem; color: var(--text-light);">ticket</span>
                    @endif
                  </td>
                  <td>
                    <span style="font-weight: 600; color: var(--success);">৳{{ number_format($ticket->total_amount, 2) }}</span>
                  </td>
                  <td>
                    @if($ticket->payment_status === 'paid')
                      <span class="badge badge-success">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <polyline points="20,6 9,17 4,12"/>
                        </svg>
                        Paid
                      </span>
                    @else
                      <span class="badge badge-warning">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <circle cx="12" cy="12" r="10"/>
                          <path d="M12 6v6M12 16h0"/>
                        </svg>
                        Unpaid
                      </span>
                    @endif
                    @if($ticket->payment_verified_at)
                      <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">
                        Verified: {{ $ticket->payment_verified_at->format('M j, Y') }}
                      </div>
                    @endif
                  </td>
                  <td>
                    <div style="color: var(--text);">{{ $ticket->created_at->format('M j, Y') }}</div>
                    <div style="font-size: 0.8rem; color: var(--text-light);">{{ $ticket->created_at->format('g:i A') }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $ticket->created_at->diffForHumans() }}</div>
                  </td>
                  <td>
                    @if($ticket->payment_method)
                      <span style="font-size: 0.875rem;">{{ ucfirst($ticket->payment_method) }}</span>
                    @elseif($ticket->payment_option === 'pay_now')
                      <span style="font-size: 0.875rem; color: var(--text-light);">Online</span>
                    @elseif($ticket->payment_option === 'pay_later')
                      <span style="font-size: 0.875rem; color: var(--text-light);">Manual</span>
                    @else
                      <span style="font-size: 0.875rem; color: var(--text-muted);">-</span>
                    @endif
                    
                    @if($ticket->sslcommerz_val_id)
                      <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">
                        ID: {{ $ticket->sslcommerz_val_id }}
                      </div>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        
        <!-- Pagination -->
        @if($tickets->hasPages())
          <div style="padding: 1.25rem; border-top: 1px solid var(--border-light);">
            {{ $tickets->links() }}
          </div>
        @endif
      @endif
    </div>
  </div>

  @if($tickets->isNotEmpty())
    <!-- Summary Card -->
    <div class="organizer-card">
      <div class="card-header">
        <h3 class="card-title">Sales Summary</h3>
        <p class="card-subtitle">Overview of ticket sales for this event</p>
      </div>
      <div class="card-body">
        @php
          $totalTickets = $tickets->sum('quantity');
          $paidTickets = $tickets->where('payment_status', 'paid')->sum('quantity');
          $unpaidTickets = $tickets->where('payment_status', 'unpaid')->sum('quantity');
          $totalRevenue = $tickets->where('payment_status', 'paid')->sum('total_amount');
          $pendingRevenue = $tickets->where('payment_status', 'unpaid')->sum('total_amount');
          $uniqueBuyers = $tickets->where('payment_status', 'paid')->unique('user_id')->count();
        @endphp
        
        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-number">{{ $paidTickets }}</div>
            <div class="stat-label">Tickets Sold</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">{{ $unpaidTickets }}</div>
            <div class="stat-label">Pending Tickets</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">৳{{ number_format($totalRevenue, 2) }}</div>
            <div class="stat-label">Confirmed Revenue</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">৳{{ number_format($pendingRevenue, 2) }}</div>
            <div class="stat-label">Pending Revenue</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">{{ $uniqueBuyers }}</div>
            <div class="stat-label">Unique Buyers</div>
          </div>
        </div>
      </div>
    </div>
  @endif
</div>
@endsection
