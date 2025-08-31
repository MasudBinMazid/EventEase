@extends('admin.layout')
@section('title','Payment Received (Manual)')
@section('content')

<div class="admin-page">
  <!-- Page Header -->
  <div class="admin-header">
    <div>
      <h1 class="admin-title">Payment Received</h1>
      <p class="admin-subtitle">Manual payment verification and processing</p>
    </div>
    <div class="admin-actions">
      <div class="badge badge-warning">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"/>
          <line x1="12" y1="8" x2="12" y2="12"/>
          <line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        {{ $rows->total() }} Pending
      </div>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-number">{{ $rows->total() }}</div>
      <div class="stat-label">Pending Payments</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">Tk {{ number_format($rows->sum('total_amount'), 2) }}</div>
      <div class="stat-label">Total Amount</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">{{ $rows->sum('quantity') }}</div>
      <div class="stat-label">Total Tickets</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">{{ $rows->whereNotNull('payment_txn_id')->count() }}</div>
      <div class="stat-label">With Transaction ID</div>
    </div>
  </div>

  <!-- Filters Card -->
  <div class="admin-card">
    <div class="card-header">
      <h3 class="card-title">Filter Payments</h3>
    </div>
    <div class="card-body">
      <form method="GET" class="filter-form">
        <div class="filter-grid" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));">
          <div class="form-group">
            <label class="form-label">Search</label>
            <input type="text" name="q" class="form-input" value="{{ $q }}" placeholder="Ticket code, name, email, transaction ID">
          </div>

          <div class="form-group">
            <label class="form-label">Event</label>
            <select name="event_id" class="form-input">
              <option value="0">All Events</option>
              @foreach($events as $e)
                <option value="{{ $e->id }}" {{ (int)$eventId === $e->id ? 'selected' : '' }}>
                  #{{ $e->id }} — {{ $e->title }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="filter-actions">
          <button type="submit" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="11" cy="11" r="8"/>
              <path d="m21 21-4.35-4.35"/>
            </svg>
            Apply Filters
          </button>
          <a href="{{ route('admin.payments.index') }}" class="btn btn-outline">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M3 6h18l-2 13H5L3 6z"/>
              <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
            </svg>
            Reset
          </a>
        </div>
      </form>
    </div>
  </div>

  <!-- Payments Table -->
  <div class="admin-card">
    <div class="card-header">
      <h3 class="card-title">Pending Verifications</h3>
      <p class="card-subtitle">{{ $rows->total() }} payment{{ $rows->total() === 1 ? '' : 's' }} awaiting verification</p>
    </div>
    <div class="card-body" style="padding: 0;">
      @forelse($rows as $r)
        <div style="overflow-x: auto;">
          <table class="admin-table">
            <thead>
              <tr>
                <th style="width: 100px;">Ticket</th>
                <th>Event & Buyer</th>
                <th>Payment Details</th>
                <th>Amount & Quantity</th>
                <th>Submitted</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($rows as $r)
                <tr>
                  <td>
                    <div>
                      <div style="font-weight: 600; color: var(--primary); margin-bottom: 0.25rem;">#{{ $r->id }}</div>
                      <span class="badge badge-outline">{{ $r->ticket_code }}</span>
                    </div>
                  </td>
                  <td>
                    <div style="margin-bottom: 1rem;">
                      <div style="font-weight: 600; color: var(--text); margin-bottom: 0.25rem;">{{ $r->event_title }}</div>
                      <div style="font-size: 0.8rem; color: var(--text-muted);">Event #{{ $r->event_id }}</div>
                    </div>
                    <div>
                      <div style="font-weight: 600; color: var(--text); margin-bottom: 0.25rem;">{{ $r->buyer_name }}</div>
                      <div style="font-size: 0.85rem; color: var(--text-light);">{{ $r->buyer_email }}</div>
                      @if($r->buyer_phone)
                        <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $r->buyer_phone }}</div>
                      @endif
                    </div>
                  </td>
                  <td>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                      @if($r->payment_txn_id)
                        <div>
                          <span style="font-weight: 600; color: var(--text-muted); font-size: 0.8rem;">TRANSACTION ID:</span>
                          <div style="font-weight: 600; color: var(--text); font-family: monospace; font-size: 0.9rem;">{{ $r->payment_txn_id }}</div>
                        </div>
                      @endif
                      
                      @if($r->payer_number)
                        <div>
                          <span style="font-weight: 600; color: var(--text-muted); font-size: 0.8rem;">PAYER NUMBER:</span>
                          <div style="font-weight: 600; color: var(--text); font-family: monospace; font-size: 0.9rem;">{{ $r->payer_number }}</div>
                        </div>
                      @endif

                      @if(!$r->payment_txn_id && !$r->payer_number)
                        <div style="color: var(--text-muted); font-style: italic;">No payment details provided</div>
                      @endif
                    </div>
                  </td>
                  <td>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                      <div style="font-weight: 600; color: var(--text); font-size: 1.1rem;">${{ number_format((float)$r->total_amount, 2) }}</div>
                      <div>
                        <span class="badge badge-info">
                          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                            <line x1="8" y1="21" x2="16" y2="21"/>
                            <line x1="12" y1="17" x2="12" y2="21"/>
                          </svg>
                          {{ (int)$r->quantity }} ticket{{ $r->quantity > 1 ? 's' : '' }}
                        </span>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div style="font-size: 0.85rem; color: var(--text-light);">
                      {{ \Carbon\Carbon::parse($r->created_at)->format('M j, Y') }}
                    </div>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">
                      {{ \Carbon\Carbon::parse($r->created_at)->format('g:i A') }}
                    </div>
                    <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">
                      {{ \Carbon\Carbon::parse($r->created_at)->diffForHumans() }}
                    </div>
                  </td>
                  <td>
                    <form method="POST" action="{{ route('admin.payments.verify', $r->id) }}" onsubmit="return confirm('Mark this payment as verified and ticket as PAID?')" style="display: inline;">
                      @csrf
                      <button type="submit" class="btn btn-success">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <path d="m9 12 2 2 4-4"/>
                          <path d="m21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9c2.12 0 4.07.74 5.61 1.97"/>
                        </svg>
                        Verify Payment
                      </button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @empty
        <div style="padding: 3rem; text-align: center; color: var(--text-light);">
          <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="margin-bottom: 1rem; opacity: 0.5;">
            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
            <line x1="8" y1="21" x2="16" y2="21"/>
            <line x1="12" y1="17" x2="12" y2="21"/>
          </svg>
          <p><strong>No pending payments</strong></p>
          <p style="color: var(--text-muted);">All manual payments have been verified.</p>
        </div>
      @endforelse
    </div>

    <!-- Pagination -->
    @if($rows->hasPages())
      <div style="padding: 1rem; border-top: 1px solid var(--border-light);">
        {{ $rows->links() }}
      </div>
    @endif
  </div>

  <!-- Action Buttons -->
  <div style="display: flex; gap: 1rem; justify-content: flex-start; margin-top: 2rem;">
    <a href="{{ route('admin.index') }}" class="btn btn-outline">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <polyline points="15,18 9,12 15,6"/>
      </svg>
      Back to Dashboard
    </a>
  </div>
</div>

<style>
  .filter-form {
    display: grid;
    gap: 1.5rem;
  }
  
  .filter-grid {
    display: grid;
    gap: 1rem;
  }
  
  .filter-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-start;
    padding-top: 1rem;
    border-top: 1px solid var(--border-light);
  }
  
  @media (max-width: 768px) {
    .filter-actions {
      flex-direction: column;
    }
    
    .filter-grid {
      grid-template-columns: 1fr !important;
    }
  }
</style>
@endsection
