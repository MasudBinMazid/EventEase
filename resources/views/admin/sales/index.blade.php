@extends('admin.layout')
@section('title','Sales & Tickets')

@section('content')
@php
  $tot = $tot ?? ['tickets_count'=>0,'qty_sum'=>0,'amount_sum'=>0,'amount_paid'=>0];
@endphp

<div class="admin-page">
  <!-- Page Header -->
  <div class="admin-header">
    <div>
      <h1 class="admin-title">Sales & Tickets</h1>
      <p class="admin-subtitle">Monitor ticket sales and payment statuses</p>
    </div>
    <div class="admin-actions">
      <div class="badge badge-info">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
          <line x1="8" y1="21" x2="16" y2="21"/>
          <line x1="12" y1="17" x2="12" y2="21"/>
        </svg>
        {{ (int)($tot['tickets_count'] ?? 0) }} Tickets
      </div>
      <a href="{{ route('admin.sales.export', request()->query()) }}" class="btn btn-success">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
          <polyline points="7,10 12,15 17,10"/>
          <line x1="12" y1="15" x2="12" y2="3"/>
        </svg>
        Export CSV
      </a>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-number">{{ (int)($tot['tickets_count'] ?? 0) }}</div>
      <div class="stat-label">Total Tickets</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">{{ (int)($tot['qty_sum'] ?? 0) }}</div>
      <div class="stat-label">Quantity Sold</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">Tk {{ number_format((float)($tot['amount_sum'] ?? 0), 2) }}</div>
      <div class="stat-label">Gross Revenue</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">Tk {{ number_format((float)($tot['amount_paid'] ?? 0), 2) }}</div>
      <div class="stat-label">Paid Amount</div>
    </div>
  </div>

  <!-- Filters Card -->
  <div class="admin-card">
    <div class="card-header">
      <h3 class="card-title">Filter Sales</h3>
    </div>
    <div class="card-body">
      <form method="GET" class="filter-form">
        <div class="filter-grid">
          <div class="form-group">
            <label class="form-label">Status</label>
            <select name="status" class="form-input">
              @php $status = request('status',''); @endphp
              <option value="">All Statuses</option>
              <option value="unpaid" {{ $status === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
              <option value="paid" {{ $status === 'paid' ? 'selected' : '' }}>Paid</option>
              <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label">Payment Option</label>
            <select name="payment_option" class="form-input">
              @php $option = request('payment_option',''); @endphp
              <option value="">All Options</option>
              <option value="pay_now" {{ $option === 'pay_now' ? 'selected' : '' }}>Pay Now</option>
              <option value="pay_later" {{ $option === 'pay_later' ? 'selected' : '' }}>Pay Later</option>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label">Event</label>
            <select name="event_id" class="form-input">
              @php $eventId = (int)request('event_id',0); @endphp
              <option value="0">All Events</option>
              @foreach ($events as $e)
                <option value="{{ $e->id }}" {{ $eventId === $e->id ? 'selected' : '' }}>#{{ $e->id }} — {{ $e->title }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label class="form-label">Search</label>
            <input type="text" name="q" class="form-input" value="{{ request('q','') }}" placeholder="Ticket code, name, email">
          </div>

          <div class="form-group">
            <label class="form-label">Date From</label>
            <input type="date" name="from" class="form-input" value="{{ request('from','') }}">
          </div>

          <div class="form-group">
            <label class="form-label">Date To</label>
            <input type="date" name="to" class="form-input" value="{{ request('to','') }}">
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
          <a href="{{ route('admin.sales.index') }}" class="btn btn-outline">
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

  <!-- Sales Table -->
  <div class="admin-card">
    <div class="card-header">
      <h3 class="card-title">Sales Records</h3>
      <p class="card-subtitle">{{ (int)($tot['tickets_count'] ?? 0) }} matching record{{ ((int)($tot['tickets_count'] ?? 0) === 1 ? '' : 's') }}</p>
    </div>
    <div class="card-body" style="padding: 0;">
      @if (empty($rows) || (is_countable($rows) && count($rows)===0))
        <div style="padding: 3rem; text-align: center; color: var(--text-light);">
          <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="margin-bottom: 1rem; opacity: 0.5;">
            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
            <line x1="8" y1="21" x2="16" y2="21"/>
            <line x1="12" y1="17" x2="12" y2="21"/>
          </svg>
          <p><strong>No tickets found</strong></p>
          <p style="color: var(--text-muted);">Try adjusting your filters or date range.</p>
        </div>
      @else
        <div style="overflow-x: auto;">
          <table class="admin-table">
            <thead>
              <tr>
                <th style="width: 80px;">ID</th>
                <th>Ticket Details</th>
                <th>Event</th>
                <th>Buyer Information</th>
                <th>Purchase Info</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($rows as $r)
                <tr>
                  <td>
                    <span style="font-weight: 600; color: var(--primary);">#{{ $r->id }}</span>
                  </td>
                  <td>
                    <div>
                      <div style="font-weight: 600; color: var(--text); margin-bottom: 0.25rem;">{{ $r->ticket_code }}</div>
                      <div style="font-size: 0.85rem; color: var(--text-light);">
                        <span style="display: inline-flex; align-items: center; gap: 0.25rem;">
                          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                          </svg>
                          Qty: {{ $r->quantity }}
                        </span>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div>
                      <div style="font-weight: 600; color: var(--text);">{{ $r->event_title }}</div>
                      <div style="font-size: 0.8rem; color: var(--text-muted);">Event #{{ $r->event_id }}</div>
                    </div>
                  </td>
                  <td>
                    <div>
                      <div style="font-weight: 600; color: var(--text);">{{ $r->buyer_name }}</div>
                      <div style="font-size: 0.85rem; color: var(--text-light);">{{ $r->buyer_email }}</div>
                      @if(!empty($r->buyer_phone))
                        <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $r->buyer_phone }}</div>
                      @endif
                    </div>
                  </td>
                  <td>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                      <div style="font-weight: 600; color: var(--text); font-size: 1rem;">Tk {{ number_format((float) $r->total_amount, 2) }}</div>
                      <div>
                        @if($r->payment_option === 'pay_later')
                          <span class="badge badge-warning">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                              <circle cx="12" cy="12" r="10"/>
                              <polyline points="12,6 12,12 16,14"/>
                            </svg>
                            Pay Later
                          </span>
                        @else
                          <span class="badge badge-info">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                              <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                              <line x1="8" y1="21" x2="16" y2="21"/>
                              <line x1="12" y1="17" x2="12" y2="21"/>
                            </svg>
                            Pay Now
                          </span>
                        @endif
                      </div>
                      <div style="font-size: 0.8rem; color: var(--text-muted);">{{ \Carbon\Carbon::parse($r->created_at)->format('M j, Y g:i A') }}</div>
                    </div>
                  </td>
                  <td>
                    @if($r->payment_status === 'paid')
                      <span class="badge badge-success">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <path d="m9 12 2 2 4-4"/>
                          <path d="m21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9c2.12 0 4.07.74 5.61 1.97"/>
                        </svg>
                        Paid
                      </span>
                    @elseif($r->payment_status === 'cancelled')
                      <span class="badge badge-danger">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <circle cx="12" cy="12" r="10"/>
                          <line x1="15" y1="9" x2="9" y2="15"/>
                          <line x1="9" y1="9" x2="15" y2="15"/>
                        </svg>
                        Cancelled
                      </span>
                    @else
                      <span class="badge badge-warning">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <circle cx="12" cy="12" r="10"/>
                          <line x1="12" y1="8" x2="12" y2="12"/>
                          <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        Unpaid
                      </span>
                    @endif
                  </td>
                  <td>
                    @if ($r->payment_option === 'pay_now' && $r->payment_status === 'unpaid')
                      <form method="POST" action="{{ route('admin.sales.verify', $r->id) }}" onsubmit="return confirm('Mark this ticket as paid?')" style="display: inline;">
                        @csrf
                        <button class="btn btn-success" type="submit" style="padding: 0.5rem 0.75rem;">
                          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="m9 12 2 2 4-4"/>
                          </svg>
                          Mark Paid
                        </button>
                      </form>
                    @else
                      <span style="color: var(--text-muted); font-size: 0.9rem;">—</span>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
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
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
      grid-template-columns: 1fr;
    }
  }
</style>
@endsection
