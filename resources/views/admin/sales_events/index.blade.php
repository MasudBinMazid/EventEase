@extends('admin.layout')
@section('title','Sales by Event')

@section('content')

<div class="admin-page">
  <!-- Page Header -->
  <div class="admin-header">
    <div>
      <h1 class="admin-title">Sales by Event</h1>
      <p class="admin-subtitle">View sales performance for each event</p>
    </div>
    <div class="admin-actions">
      <a href="{{ route('admin.sales.export', ['group'=>'event','status'=>request('status','paid')]) }}" class="btn btn-success">
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
      <div class="stat-number">{{ count($rows ?? []) }}</div>
      <div class="stat-label">Events</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">{{ (int)($totals['tickets'] ?? 0) }}</div>
      <div class="stat-label">Total Tickets</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">{{ (int)($totals['qty'] ?? 0) }}</div>
      <div class="stat-label">Quantity Sold</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">Tk {{ number_format((float)($totals['amount'] ?? 0), 2) }}</div>
      <div class="stat-label">Total Revenue</div>
    </div>
  </div>

  <!-- Status Filter -->
  <div class="admin-card">
    <div class="card-header">
      <h3 class="card-title">Filter by Status</h3>
    </div>
    <div class="card-body">
      @php $status = request('status','paid'); @endphp
      <div class="filter-tabs">
        @foreach (['paid'=>'Paid','unpaid'=>'Unpaid','cancelled'=>'Cancelled','all'=>'All Status'] as $key=>$label)
          <a href="{{ route('admin.sales.events', ['status'=>$key]) }}" 
             class="filter-tab {{ $status===$key ? 'active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              @if($key === 'paid')
                <path d="m9 12 2 2 4-4"/>
                <path d="m21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9c2.12 0 4.07.74 5.61 1.97"/>
              @elseif($key === 'unpaid')
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
              @elseif($key === 'cancelled')
                <circle cx="12" cy="12" r="10"/>
                <line x1="15" y1="9" x2="9" y2="15"/>
                <line x1="9" y1="9" x2="15" y2="15"/>
              @else
                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                <line x1="8" y1="21" x2="16" y2="21"/>
                <line x1="12" y1="17" x2="12" y2="21"/>
              @endif
            </svg>
            {{ $label }}
          </a>
        @endforeach
      </div>
    </div>
  </div>

  <!-- Sales Table -->
  <div class="admin-card">
    <div class="card-header">
      <h3 class="card-title">Event Sales Summary</h3>
      <p class="card-subtitle">{{ $status === 'all' ? 'All statuses' : ucfirst($status) }} sales data</p>
    </div>
    <div class="card-body" style="padding: 0;">
      @if(empty($rows) || (is_countable($rows) && count($rows)===0))
        <div style="padding: 3rem; text-align: center; color: var(--text-light);">
          <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="margin-bottom: 1rem; opacity: 0.5;">
            <circle cx="12" cy="12" r="10"/>
            <path d="m16 12-4-4-4 4"/>
            <path d="M12 16V8"/>
          </svg>
          <p><strong>No events found</strong></p>
          <p style="color: var(--text-muted);">Try selecting a different status filter.</p>
        </div>
      @else
        <div style="overflow-x: auto;">
          <table class="admin-table">
            <thead>
              <tr>
                <th>Event Details</th>
                <th>Schedule</th>
                <th>Location</th>
                <th style="text-align: right;">Tickets</th>
                <th style="text-align: right;">Quantity</th>
                <th style="text-align: right;">Total Amount</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($rows as $r)
                <tr>
                  <td>
                    <div>
                      <div style="font-weight: 600; color: var(--text); margin-bottom: 0.25rem;">{{ $r->event_title }}</div>
                      <div style="font-size: 0.8rem; color: var(--text-muted);">Event #{{ $r->event_id }}</div>
                    </div>
                  </td>
                  <td>
                    <div style="font-weight: 500; color: var(--text); margin-bottom: 0.25rem;">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; margin-right: 0.25rem; vertical-align: middle;">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12,6 12,12 16,14"/>
                      </svg>
                      {{ \Carbon\Carbon::parse($r->starts_at)->format('M j, Y') }}
                    </div>
                    <div style="font-size: 0.85rem; color: var(--text-light);">
                      {{ \Carbon\Carbon::parse($r->starts_at)->format('g:i A') }}
                    </div>
                  </td>
                  <td>
                    <span class="badge badge-outline">
                      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                      </svg>
                      {{ $r->location ?? 'No location' }}
                    </span>
                  </td>
                  <td style="text-align: right;">
                    <span style="font-weight: 600; color: var(--primary);">{{ (int) $r->tickets_count }}</span>
                  </td>
                  <td style="text-align: right;">
                    <span style="font-weight: 600; color: var(--text);">{{ (int) $r->qty_sum }}</span>
                  </td>
                  <td style="text-align: right;">
                    <span style="font-weight: 600; color: var(--success); font-size: 1.05rem;">Tk {{ number_format((float) $r->amount_sum, 2) }}</span>
                  </td>
                  <td>
                    <a href="{{ route('admin.sales.index', [
                        'event_id' => $r->event_id,
                        'status'   => request('status') === 'all' ? '' : request('status')
                     ]) }}" class="btn btn-outline" style="padding: 0.5rem 0.75rem;">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                      </svg>
                      View Details
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
            @if(count($rows) > 0)
              <tfoot>
                <tr style="background: var(--surface-light); border-top: 2px solid var(--border);">
                  <th colspan="3" style="text-align: right; color: var(--text); padding: 1rem;">
                    <strong>Totals:</strong>
                  </th>
                  <th style="text-align: right; color: var(--primary); font-weight: 700;">{{ (int)($totals['tickets'] ?? 0) }}</th>
                  <th style="text-align: right; color: var(--text); font-weight: 700;">{{ (int)($totals['qty'] ?? 0) }}</th>
                  <th style="text-align: right; color: var(--success); font-weight: 700; font-size: 1.1rem;">Tk {{ number_format((float)($totals['amount'] ?? 0), 2) }}</th>
                  <th></th>
                </tr>
              </tfoot>
            @endif
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
  .filter-tabs {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
  }
  
  .filter-tab {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    border-radius: 999px;
    border: 1px solid var(--border);
    background: var(--surface);
    color: var(--text);
    text-decoration: none;
    font-weight: 600;
    transition: all 0.2s ease;
  }
  
  .filter-tab:hover {
    transform: translateY(-1px);
    box-shadow: var(--shadow-sm);
    background: var(--surface-light);
  }
  
  .filter-tab.active {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
    box-shadow: var(--shadow);
  }
  
  @media (max-width: 768px) {
    .filter-tabs {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 0.5rem;
    }
    
    .filter-tab {
      justify-content: center;
    }
  }
</style>
@endsection
