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
                <th>Location & Venue</th>
                <th>Pricing & Status</th>
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
                        <div style="font-size: 0.85rem; color: var(--text-light); line-height: 1.4; margin-bottom: 0.5rem;">
                          {{ Str::limit(strip_tags($r->description), 120) }}
                        </div>
                      @endif
                      <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                        <span class="badge badge-{{ $r->event_type === 'paid' ? 'warning' : 'success' }}">
                          {{ ucfirst($r->event_type ?? 'free') }}
                        </span>
                        @if($r->capacity)
                          <span class="badge badge-info">{{ $r->capacity }} max</span>
                        @endif
                        @if($r->featured_on_home)
                          <span class="badge badge-primary">Featured</span>
                        @endif
                        @if($r->visible_on_site)
                          <span class="badge badge-outline">Public</span>
                        @endif
                      </div>
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
                      @if($r->location)
                        <div>
                          <span class="badge badge-outline">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                              <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                              <circle cx="12" cy="10" r="3"/>
                            </svg>
                            {{ $r->location }}
                          </span>
                        </div>
                      @endif
                      @if($r->venue)
                        <div>
                          <span class="badge badge-info">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                              <path d="M3 21h18l-9-18-9 18zM12 9v4"/>
                            </svg>
                            {{ $r->venue }}
                          </span>
                        </div>
                      @endif
                      @if(!$r->location && !$r->venue)
                        <span class="badge badge-outline" style="opacity: 0.6;">No location specified</span>
                      @endif
                    </div>
                  </td>
                  <td>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                      @if($r->event_type === 'paid')
                        <div>
                          <span class="badge badge-warning">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                              <line x1="12" y1="1" x2="12" y2="23"/>
                              <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                            </svg>
                            ৳{{ number_format($r->price ?? 0, 2) }}
                          </span>
                        </div>
                      @else
                        <div>
                          <span class="badge badge-success">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                              <path d="M9 12l2 2 4-4"/>
                              <circle cx="12" cy="12" r="10"/>
                            </svg>
                            Free
                          </span>
                        </div>
                      @endif
                      <div>
                        <span class="badge badge-{{ $r->event_status === 'available' ? 'success' : ($r->event_status === 'limited_sell' ? 'warning' : 'danger') }}">
                          {{ ucwords(str_replace('_', ' ', $r->event_status ?? 'available')) }}
                        </span>
                      </div>
                      @if($r->purchase_option)
                        <div>
                          <span class="badge badge-info" style="font-size: 0.75rem;">
                            {{ ucwords(str_replace('_', ' + ', $r->purchase_option)) }}
                          </span>
                        </div>
                      @endif
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
