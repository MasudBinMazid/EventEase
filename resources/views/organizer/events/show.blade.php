@extends('organizer.layout')
@section('title', 'Event Details - ' . $event->title)

@section('content')
<div class="organizer-page">
  <!-- Page Header -->
  <div class="organizer-header">
    <div>
      <h1 class="organizer-title">{{ $event->title }}</h1>
      <p class="organizer-subtitle">Event details and statistics</p>
    </div>
    <div style="display: flex; gap: 0.5rem;">
      <a href="{{ route('organizer.dashboard') }}" class="btn btn-outline">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="15,18 9,12 15,6"/>
        </svg>
        Back to Dashboard
      </a>
      @if($event->status === 'approved')
        <a href="{{ route('events.show', $event) }}" class="btn btn-primary" target="_blank">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
            <polyline points="15,3 21,3 21,9"/>
            <line x1="10" y1="14" x2="21" y2="3"/>
          </svg>
          View Public Page
        </a>
      @endif
    </div>
  </div>

  <!-- Event Status Alert -->
  @if($event->status === 'pending')
    <div style="background: rgba(245, 158, 11, 0.1); color: #d97706; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid rgba(245, 158, 11, 0.2);">
      <div style="display: flex; align-items: center; gap: 0.5rem;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"/>
          <path d="M12 6v6M12 16h0"/>
        </svg>
        <strong>Pending Approval</strong>
      </div>
      <p style="margin-top: 0.5rem;">Your event is currently under review by our admin team. You'll be notified once it's approved.</p>
    </div>
  @elseif($event->status === 'rejected')
    <div style="background: rgba(239, 68, 68, 0.1); color: #dc2626; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid rgba(239, 68, 68, 0.2);">
      <div style="display: flex; align-items: center; gap: 0.5rem;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"/>
          <line x1="15" y1="9" x2="9" y2="15"/>
          <line x1="9" y1="9" x2="15" y2="15"/>
        </svg>
        <strong>Event Rejected</strong>
      </div>
      <p style="margin-top: 0.5rem;">Unfortunately, your event was not approved. Please contact support for more details.</p>
    </div>
  @elseif($event->status === 'approved')
    <div style="background: rgba(16, 185, 129, 0.1); color: #059669; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid rgba(16, 185, 129, 0.2);">
      <div style="display: flex; align-items: center; gap: 0.5rem;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="20,6 9,17 4,12"/>
        </svg>
        <strong>Event Approved</strong>
      </div>
      <p style="margin-top: 0.5rem;">Your event is now live and accepting ticket sales!</p>
    </div>
  @endif

  <!-- Ticket Stats -->
  @if($event->status === 'approved')
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-number">{{ $ticketStats['total_sold'] }}</div>
        <div class="stat-label">Tickets Sold</div>
      </div>
      <div class="stat-card">
        <div class="stat-number">৳{{ number_format($ticketStats['total_revenue'], 2) }}</div>
        <div class="stat-label">Total Revenue</div>
      </div>
      <div class="stat-card">
        <div class="stat-number">{{ $ticketStats['unique_buyers'] }}</div>
        <div class="stat-label">Unique Buyers</div>
      </div>
      <div class="stat-card">
        <div class="stat-number">{{ $ticketStats['pending_payments'] }}</div>
        <div class="stat-label">Pending Payments</div>
      </div>
    </div>
  @endif

  <!-- Event Details -->
  <div class="organizer-card">
    <div class="card-header">
      <h3 class="card-title">Event Information</h3>
      <p class="card-subtitle">Complete details about your event</p>
    </div>
    <div class="card-body">
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
        <div>
          <h4 style="font-weight: 600; margin-bottom: 1rem; color: var(--text);">Basic Details</h4>
          <div style="display: grid; gap: 1rem;">
            <div>
              <strong style="color: var(--text-light);">Title:</strong>
              <div>{{ $event->title }}</div>
            </div>
            @if($event->description)
              <div>
                <strong style="color: var(--text-light);">Description:</strong>
                <div style="white-space: pre-line;">{{ $event->description }}</div>
              </div>
            @endif
            <div>
              <strong style="color: var(--text-light);">Location:</strong>
              <div>{{ $event->location ?: 'Not specified' }}</div>
            </div>
            @if($event->venue)
              <div>
                <strong style="color: var(--text-light);">Venue:</strong>
                <div>{{ $event->venue }}</div>
              </div>
            @endif
            <div>
              <strong style="color: var(--text-light);">Capacity:</strong>
              <div>{{ $event->capacity ? number_format($event->capacity) : 'Unlimited' }}</div>
            </div>
          </div>
        </div>

        <div>
          <h4 style="font-weight: 600; margin-bottom: 1rem; color: var(--text);">Schedule & Pricing</h4>
          <div style="display: grid; gap: 1rem;">
            <div>
              <strong style="color: var(--text-light);">Start Date:</strong>
              <div>{{ $event->starts_at ? $event->starts_at->format('l, F j, Y g:i A') : 'TBA' }}</div>
            </div>
            @if($event->ends_at)
              <div>
                <strong style="color: var(--text-light);">End Date:</strong>
                <div>{{ $event->ends_at->format('l, F j, Y g:i A') }}</div>
              </div>
            @endif
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
            @if($event->allow_pay_later)
              <div>
                <strong style="color: var(--text-light);">Pay Later:</strong>
                <div>
                  <span class="badge badge-success" style="font-size: 0.75rem;">Allowed</span>
                </div>
              </div>
            @endif
            <div>
              <strong style="color: var(--text-light);">Status:</strong>
              <div>
                @if($event->status === 'approved')
                  <span class="badge badge-success">Approved</span>
                @elseif($event->status === 'pending')
                  <span class="badge badge-warning">Pending</span>
                @else
                  <span class="badge badge-danger">Rejected</span>
                @endif
              </div>
            </div>
            <div>
              <strong style="color: var(--text-light);">Created:</strong>
              <div>{{ $event->created_at->format('M j, Y g:i A') }}</div>
            </div>
            @if($event->approved_at)
              <div>
                <strong style="color: var(--text-light);">Approved:</strong>
                <div>{{ $event->approved_at->format('M j, Y g:i A') }}</div>
              </div>
            @endif
          </div>
        </div>
      </div>

      @if($event->banner_url)
        <div style="margin-top: 2rem;">
          <h4 style="font-weight: 600; margin-bottom: 1rem; color: var(--text);">Event Banner</h4>
          <img src="{{ $event->banner_url }}" alt="{{ $event->title }}" style="max-width: 100%; max-height: 300px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border);">
        </div>
      @endif
    </div>
  </div>

  @if($event->status === 'approved' && $event->tickets->isNotEmpty())
    <!-- Recent Tickets -->
    <div class="organizer-card">
      <div class="card-header">
        <h3 class="card-title">Recent Tickets</h3>
        <p class="card-subtitle">Latest ticket purchases for this event</p>
        <div style="margin-top: 0.5rem;">
          <a href="{{ route('organizer.tickets', $event) }}" class="btn btn-primary">View All Tickets</a>
        </div>
      </div>
      <div class="card-body" style="padding: 0;">
        <div style="overflow-x: auto;">
          <table class="organizer-table">
            <thead>
              <tr>
                <th>Ticket Code</th>
                <th>Buyer</th>
                <th>Quantity</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              @foreach($event->tickets->take(5) as $ticket)
                <tr>
                  <td>
                    <strong style="font-family: monospace;">{{ $ticket->ticket_code }}</strong>
                  </td>
                  <td>
                    <div>
                      <div style="font-weight: 600;">{{ $ticket->user->name }}</div>
                      <div style="font-size: 0.875rem; color: var(--text-light);">{{ $ticket->user->email }}</div>
                    </div>
                  </td>
                  <td>{{ $ticket->quantity }}</td>
                  <td>৳{{ number_format($ticket->total_amount, 2) }}</td>
                  <td>
                    @if($ticket->payment_status === 'paid')
                      <span class="badge badge-success">Paid</span>
                    @else
                      <span class="badge badge-warning">Unpaid</span>
                    @endif
                  </td>
                  <td>
                    <div>{{ $ticket->created_at->format('M j, Y') }}</div>
                    <div style="font-size: 0.8rem; color: var(--text-light);">{{ $ticket->created_at->format('g:i A') }}</div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  @endif
</div>
@endsection
