@extends('admin.layout')
@section('title','Pending Event Requests')
@section('content')

<div class="admin-page">
  <!-- Page Header -->
  <div class="admin-header">
    <div>
      <h1 class="admin-title">Event Requests</h1>
      <p class="admin-subtitle">Review, approve, or reject pending event submissions</p>
    </div>
    <div class="admin-actions">
      <div class="badge badge-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
          <circle cx="9" cy="7" r="4"/>
          <path d="m22 2-5 10-7-3 7-7-5-5z"/>
        </svg>
        {{ $rows->count() }} Pending
      </div>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-number">{{ $rows->count() }}</div>
      <div class="stat-label">Pending Requests</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">{{ $rows->where('creator', '!=', null)->count() }}</div>
      <div class="stat-label">With Valid Users</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">{{ $rows->whereNotNull('capacity')->count() }}</div>
      <div class="stat-label">With Capacity Set</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">{{ $rows->whereNotNull('location')->count() }}</div>
      <div class="stat-label">With Location</div>
    </div>
  </div>

  <!-- Requests Table -->
  <div class="admin-card">
    <div class="card-header">
      <h3 class="card-title">Event Requests</h3>
      <p class="card-subtitle">Review and manage pending event submissions</p>
    </div>
    <div class="card-body" style="padding: 0;">
      @if($rows->isEmpty())
        <div style="padding: 3rem; text-align: center; color: var(--text-light);">
          <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="margin-bottom: 1rem; opacity: 0.5;">
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="m22 2-5 10-7-3 7-7-5-5z"/>
          </svg>
          <p><strong>No pending requests</strong></p>
          <p style="color: var(--text-muted);">All event requests have been processed.</p>
        </div>
      @else
        <div style="overflow-x: auto;">
          <table class="admin-table">
            <thead>
              <tr>
                <th style="width: 80px;">ID</th>
                <th>Event Details</th>
                <th>Schedule</th>
                <th>Location & Capacity</th>
                <th>Requested By</th>
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
                      <div style="font-weight: 600; color: var(--text); margin-bottom: 0.5rem;">{{ $r->title }}</div>
                      @if(!empty($r->description))
                        <div style="font-size: 0.85rem; color: var(--text-light); line-height: 1.4;">
                          {{ Str::limit(strip_tags($r->description), 120) }}
                        </div>
                      @endif
                    </div>
                  </td>
                  <td>
                    <div>
                      <div style="font-weight: 600; color: var(--text); margin-bottom: 0.25rem;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; margin-right: 0.25rem; vertical-align: middle;">
                          <circle cx="12" cy="12" r="10"/>
                          <polyline points="12,6 12,12 16,14"/>
                        </svg>
                        {{ \Carbon\Carbon::parse($r->starts_at)->format('M j, Y') }}
                      </div>
                      <div style="font-size: 0.85rem; color: var(--text-light);">
                        {{ \Carbon\Carbon::parse($r->starts_at)->format('g:i A') }}
                        @if($r->ends_at)
                          – {{ \Carbon\Carbon::parse($r->ends_at)->format('g:i A') }}
                        @endif
                      </div>
                      @if($r->ends_at && \Carbon\Carbon::parse($r->starts_at)->format('Y-m-d') !== \Carbon\Carbon::parse($r->ends_at)->format('Y-m-d'))
                        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">
                          Ends: {{ \Carbon\Carbon::parse($r->ends_at)->format('M j, Y g:i A') }}
                        </div>
                      @endif
                    </div>
                  </td>
                  <td>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                      <div>
                        @php $loc = $r->location ?? 'No location specified'; @endphp
                        <span class="badge badge-outline">
                          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                          </svg>
                          {{ $loc }}
                        </span>
                      </div>
                      <div>
                        @php $cap = $r->capacity ?? 'Unlimited'; @endphp
                        <span class="badge badge-info">
                          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="m22 2-5 10-7-3 7-7-5-5z"/>
                          </svg>
                          {{ is_numeric($cap) ? $cap . ' attendees' : $cap }}
                        </span>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div>
                      <div style="font-weight: 600; color: var(--text);">{{ optional($r->creator)->name ?? 'Unknown User' }}</div>
                      <div style="font-size: 0.85rem; color: var(--text-light);">{{ optional($r->creator)->email ?? 'No email' }}</div>
                      @if(optional($r->creator)->phone)
                        <div style="font-size: 0.8rem; color: var(--text-muted);">{{ optional($r->creator)->phone }}</div>
                      @endif
                      <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">
                        Requested {{ \Carbon\Carbon::parse($r->created_at)->diffForHumans() }}
                      </div>
                    </div>
                  </td>
                  <td>
                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                      <form action="{{ route('admin.requests.approve', $r) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-success" style="padding: 0.5rem 0.75rem;" onclick="return confirm('Approve this event request?')">
                          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="m9 12 2 2 4-4"/>
                            <path d="m21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9c2.12 0 4.07.74 5.61 1.97"/>
                          </svg>
                          Approve
                        </button>
                      </form>
                      <form action="{{ route('admin.requests.reject', $r) }}" method="POST" style="display: inline;" onsubmit="return confirm('Reject this event request? This action cannot be undone.')">
                        @csrf
                        <button type="submit" class="btn btn-danger" style="padding: 0.5rem 0.75rem;">
                          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="15" y1="9" x2="9" y2="15"/>
                            <line x1="9" y1="9" x2="15" y2="15"/>
                          </svg>
                          Reject
                        </button>
                      </form>
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
@endsection
