@extends('admin.layout')
@section('title','Contact Messages')

@section('content')
<div class="admin-page">
  <!-- Page Header -->
  <div class="admin-header">
    <div>
      <h1 class="admin-title">Contact Messages</h1>
      <p class="admin-subtitle">View and respond to messages from website visitors</p>
    </div>
    <div class="admin-actions">
      <div class="badge badge-info">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
          <polyline points="22,6 12,13 2,6"/>
        </svg>
        {{ $rows->count() }} Messages
      </div>
      <button class="btn btn-outline" onclick="window.location.reload()">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="23,4 23,10 17,10"/>
          <polyline points="1,20 1,14 7,14"/>
          <path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"/>
        </svg>
        Refresh
      </button>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-number">{{ $rows->count() }}</div>
      <div class="stat-label">Total Messages</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">{{ $rows->where('created_at', '>=', now()->subDays(7))->count() }}</div>
      <div class="stat-label">This Week</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">{{ $rows->where('created_at', '>=', now()->subDays(1))->count() }}</div>
      <div class="stat-label">Today</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">{{ $rows->unique('email')->count() }}</div>
      <div class="stat-label">Unique Contacts</div>
    </div>
  </div>

  <!-- Messages Table -->
  <div class="admin-card">
    <div class="card-header">
      <h3 class="card-title">All Messages</h3>
      <div style="display: flex; gap: 1rem; align-items: center; margin-top: 1rem;">
        <input type="search" placeholder="Search by name or email..." class="form-input" style="max-width: 300px;" id="messageSearch">
      </div>
    </div>
    <div class="card-body" style="padding: 0;">
      @if($rows->isEmpty())
        <div style="padding: 3rem; text-align: center; color: var(--text-light);">
          <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="margin-bottom: 1rem; opacity: 0.5;">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
            <polyline points="22,6 12,13 2,6"/>
          </svg>
          <p>No messages found.</p>
        </div>
      @else
        <div style="overflow-x: auto;">
          <table class="admin-table">
            <thead>
              <tr>
                <th style="width: 80px;">#</th>
                <th>Contact Info</th>
                <th>Message Preview</th>
                <th>Received</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($rows as $message)
                <tr>
                  <td>
                    <span style="font-weight: 600; color: var(--primary);">#{{ $message->id }}</span>
                  </td>
                  <td>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                      <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--success); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.9rem;">
                        {{ strtoupper(substr($message->name, 0, 1)) }}
                      </div>
                      <div>
                        <div style="font-weight: 600; color: var(--text);">{{ $message->name }}</div>
                        <div style="font-size: 0.85rem;">
                          <a href="mailto:{{ $message->email }}" style="color: var(--primary); text-decoration: none;">
                            {{ $message->email }}
                          </a>
                        </div>
                        @if($message->subject)
                          <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">
                            <strong>Subject:</strong> {{ Str::limit($message->subject, 30) }}
                          </div>
                        @endif
                      </div>
                    </div>
                  </td>
                  <td>
                    <div style="max-width: 300px;">
                      @if($message->message)
                        <div style="font-size: 0.9rem; color: var(--text); line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                          {{ Str::limit($message->message, 120) }}
                        </div>
                      @else
                        <span style="color: var(--text-muted); font-style: italic;">No message content</span>
                      @endif
                    </div>
                  </td>
                  <td>
                    <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                      <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <path d="M7 2v4M17 2v4M3 9h18M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5A2 2 0 0 0 3 7v12a2 2 0 0 0 2 2Z"/>
                        </svg>
                        <span style="color: var(--text); font-size: 0.85rem;">{{ $message->created_at->format('M j, Y') }}</span>
                      </div>
                      <div style="font-size: 0.8rem; color: var(--text-light); padding-left: 1.25rem;">{{ $message->created_at->format('g:i A') }}</div>
                      <div style="font-size: 0.75rem; color: var(--text-muted); padding-left: 1.25rem;">{{ $message->created_at->diffForHumans() }}</div>
                    </div>
                  </td>
                  <td>
                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                      <a class="btn btn-outline" href="{{ route('admin.messages.show', $message) }}" title="View Full Message">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                          <circle cx="12" cy="12" r="3"/>
                        </svg>
                        View
                      </a>
                      <a class="btn btn-success" href="mailto:{{ $message->email }}?subject=Re: {{ $message->subject ?? 'Your message' }}" title="Reply via Email">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <path d="m3 7 9 6 9-6v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7z"/>
                          <polyline points="22,7 12,13 2,7 2,5 12,10 22,5 22,7"/>
                        </svg>
                        Reply
                      </a>
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

  <!-- Pagination -->
  @if(method_exists($rows, 'links'))
    <div style="margin-top: 2rem; display: flex; justify-content: center;">
      {{ $rows->links() }}
    </div>
  @endif

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

<script>
  // Simple client-side search functionality
  document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('messageSearch');
    if (searchInput) {
      searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('.admin-table tbody tr');
        
        tableRows.forEach(row => {
          const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
          const email = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
          const message = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
          
          if (name.includes(searchTerm) || email.includes(searchTerm) || message.includes(searchTerm)) {
            row.style.display = '';
          } else {
            row.style.display = 'none';
          }
        });
      });
    }
  });
</script>
@endsection
