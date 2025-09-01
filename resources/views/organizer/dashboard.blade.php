@extends('organizer.layout')
@section('title','Organizer Dashboard')

@section('content')
<div class="organizer-page">
  <!-- Page Header -->
  <div class="organizer-header">
    <div>
      <h1 class="organizer-title">Organizer Dashboard</h1>
      <p class="organizer-subtitle">Manage your events and track their performance</p>
    </div>
    <div>
      <a href="{{ route('events.request.create') }}" class="btn btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M12 5v14M5 12h14"/>
        </svg>
        Create New Event
      </a>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-number">{{ $totalEvents }}</div>
      <div class="stat-label">Total Events</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">{{ $approvedEvents }}</div>
      <div class="stat-label">Approved Events</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">{{ $pendingEvents }}</div>
      <div class="stat-label">Pending Approval</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">{{ $totalTickets }}</div>
      <div class="stat-label">Tickets Sold</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">৳{{ number_format($totalRevenue, 2) }}</div>
      <div class="stat-label">Total Revenue</div>
    </div>
  </div>

  <!-- Events List -->
  <div class="organizer-card">
    <div class="card-header">
      <h3 class="card-title">Your Events</h3>
      <p class="card-subtitle">All events created by you</p>
    </div>
    
    <div class="card-body" style="padding: 0;">
      @if($events->isEmpty())
        <div style="padding: 3rem; text-align: center; color: var(--text-light);">
          <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="margin-bottom: 1rem; opacity: 0.5;">
            <path d="M7 2v4M17 2v4M3 9h18M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5A2 2 0 0 0 3 7v12a2 2 0 0 0 2 2Z"/>
          </svg>
          <p>You haven't created any events yet.</p>
          <div style="margin-top: 1rem;">
            <a href="{{ route('events.request.create') }}" class="btn btn-primary">Create Your First Event</a>
          </div>
        </div>
      @else
        <div style="overflow-x: auto;">
          <table class="organizer-table">
            <thead>
              <tr>
                <th>Event</th>
                <th>Date</th>
                <th>Status</th>
                <th>Tickets</th>
                <th>Revenue</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($events as $event)
                @php
                  $ticketsSold = $event->tickets()->where('payment_status', 'paid')->sum('quantity');
                  $revenue = $event->tickets()->where('payment_status', 'paid')->sum('total_amount');
                @endphp
                <tr>
                  <td>
                    <div>
                      <div style="font-weight: 600;">{{ $event->title }}</div>
                      <div style="font-size: 0.875rem; color: var(--text-light);">{{ $event->location }}</div>
                    </div>
                  </td>
                  <td>
                    <div style="color: var(--text);">{{ $event->starts_at ? $event->starts_at->format('M j, Y') : 'TBA' }}</div>
                    <div style="font-size: 0.8rem; color: var(--text-light);">{{ $event->starts_at ? $event->starts_at->format('g:i A') : '' }}</div>
                  </td>
                  <td>
                    @if($event->status === 'approved')
                      <span class="badge badge-success">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <polyline points="20,6 9,17 4,12"/>
                        </svg>
                        Approved
                      </span>
                    @elseif($event->status === 'pending')
                      <span class="badge badge-warning">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <circle cx="12" cy="12" r="10"/>
                          <path d="M12 6v6M12 16h0"/>
                        </svg>
                        Pending
                      </span>
                    @else
                      <span class="badge badge-danger">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <circle cx="12" cy="12" r="10"/>
                          <line x1="15" y1="9" x2="9" y2="15"/>
                          <line x1="9" y1="9" x2="15" y2="15"/>
                        </svg>
                        Rejected
                      </span>
                    @endif
                  </td>
                  <td>
                    <div style="font-weight: 600;">{{ $ticketsSold }}</div>
                    @if($event->capacity)
                      <div style="font-size: 0.8rem; color: var(--text-light);">of {{ $event->capacity }}</div>
                    @endif
                  </td>
                  <td>
                    <div style="font-weight: 600; color: var(--success);">৳{{ number_format($revenue, 2) }}</div>
                  </td>
                  <td>
                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                      <a href="{{ route('organizer.events.show', $event) }}" class="btn btn-outline" title="View Details">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                          <circle cx="12" cy="12" r="3"/>
                        </svg>
                      </a>
                      @if($event->status === 'approved')
                        <a href="{{ route('organizer.tickets', $event) }}" class="btn btn-outline" title="View Tickets">
                          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                          </svg>
                        </a>
                        <a href="{{ route('events.show', $event) }}" class="btn btn-outline" title="View Public Page" target="_blank">
                          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                            <polyline points="15,3 21,3 21,9"/>
                            <line x1="10" y1="14" x2="21" y2="3"/>
                          </svg>
                        </a>
                      @endif
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
