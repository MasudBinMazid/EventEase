@extends('admin.layout')
@section('title','Notice Management')

@section('content')
<div class="admin-page">
  <!-- Page Header -->
  <div class="admin-header">
    <div>
      <h1 class="admin-title">Notice Management</h1>
      <p class="admin-subtitle">Manage scrolling announcements for your events page</p>
    </div>
    <div class="admin-actions">
      <div class="badge badge-info">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
          <line x1="4" y1="22" x2="4" y2="15"/>
        </svg>
        {{ $totalNotices }} Total Notices
      </div>
      <a href="{{ route('admin.notices.create') }}" class="btn btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="12" y1="5" x2="12" y2="19"/>
          <line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Create Notice
      </a>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-number">{{ $totalNotices }}</div>
      <div class="stat-label">Total Notices</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">{{ $activeNotices }}</div>
      <div class="stat-label">Active Notices</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">{{ $notices->where('priority', '>=', 80)->count() }}</div>
      <div class="stat-label">High Priority</div>
    </div>
    <div class="stat-card">
      <div class="stat-number" style="color: {{ $settings->is_enabled ? 'var(--success)' : 'var(--danger)' }};">
        {{ $settings->is_enabled ? 'LIVE' : 'OFF' }}
      </div>
      <div class="stat-label">Notice Bar Status</div>
    </div>
  </div>

  <!-- Notice Bar Settings Card -->
  <div class="admin-card" style="margin-bottom: 2rem;">
    <div class="card-header">
      <h3 class="card-title">Notice Bar Settings</h3>
    </div>
    <div class="card-body">
      <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem; background: var(--border-light); border-radius: var(--radius); margin-bottom: 1rem;">
        <div>
          <div style="font-weight: 600; color: var(--text); margin-bottom: 0.25rem;">Enable Notice Bar Display</div>
          <div style="font-size: 0.9rem; color: var(--text-light);">Control whether the scrolling notice bar appears on your events page</div>
        </div>
        <div style="display: flex; align-items: center; gap: 1rem;">
          <span style="font-weight: 600; color: {{ $settings->is_enabled ? 'var(--success)' : 'var(--danger)' }};">
            {{ $settings->is_enabled ? '🟢 Enabled' : '🔴 Disabled' }}
          </span>
          <label style="position: relative; display: inline-block; width: 60px; height: 32px;">
            <input type="checkbox" 
                   {{ $settings->is_enabled ? 'checked' : '' }}
                   onchange="toggleNoticeBar({{ $settings->id }})"
                   style="opacity: 0; width: 0; height: 0;">
            <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background: {{ $settings->is_enabled ? 'var(--success)' : '#ccc' }}; transition: .4s; border-radius: 32px;">
              <span style="position: absolute; content: ''; height: 24px; width: 24px; left: {{ $settings->is_enabled ? '32px' : '4px' }}; bottom: 4px; background: white; transition: .4s; border-radius: 50%;"></span>
            </span>
          </label>
        </div>
      </div>
    </div>
  </div>

  <!-- Notices Table -->
  <div class="admin-card">
    <div class="card-header">
      <h3 class="card-title">All Notices</h3>
      <div style="display: flex; gap: 1rem; align-items: center; margin-top: 1rem;">
        <input type="search" placeholder="Search notices..." class="form-input" style="max-width: 300px;" id="noticeSearch">
      </div>
    </div>
    <div class="card-body" style="padding: 0;">
      @if($notices->isEmpty())
        <div style="padding: 3rem; text-align: center; color: var(--text-light);">
          <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="margin-bottom: 1rem; opacity: 0.5;">
            <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
            <line x1="4" y1="22" x2="4" y2="15"/>
          </svg>
          <p>No notices found.</p>
          <a href="{{ route('admin.notices.create') }}" class="btn btn-primary" style="margin-top: 1rem;">Create Your First Notice</a>
        </div>
      @else
        <div style="overflow-x: auto;">
          <table class="admin-table">
            <thead>
              <tr>
                <th style="width: 80px;">#</th>
                <th>Notice Details</th>
                <th>Content Preview</th>
                <th>Priority & Status</th>
                <th>Schedule</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($notices as $notice)
                <tr>
                  <td>
                    <span style="font-weight: 600; color: var(--primary);">#{{ $notice->id }}</span>
                  </td>
                  <td>
                    <div>
                      <div style="font-weight: 600; color: var(--text); margin-bottom: 0.25rem;">{{ $notice->title }}</div>
                      <div style="font-size: 0.8rem; color: var(--text-muted);">
                        Created {{ $notice->created_at->format('M j, Y') }} at {{ $notice->created_at->format('g:i A') }}
                      </div>
                      <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $notice->created_at->diffForHumans() }}</div>
                    </div>
                  </td>
                  <td>
                    <div style="max-width: 300px; font-size: 0.9rem; color: var(--text); line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                      {{ Str::limit($notice->content, 120) }}
                    </div>
                  </td>
                  <td>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                      @if($notice->priority >= 80)
                        <span class="badge badge-danger">
                          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                          </svg>
                          High ({{ $notice->priority }})
                        </span>
                      @elseif($notice->priority >= 50)
                        <span class="badge badge-warning">
                          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                          </svg>
                          Medium ({{ $notice->priority }})
                        </span>
                      @else
                        <span class="badge badge-success">
                          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22,4 12,14.01 9,11.01"/>
                          </svg>
                          Low ({{ $notice->priority }})
                        </span>
                      @endif
                      
                      @if($notice->is_active)
                        <span class="badge badge-success">
                          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22,4 12,14.01 9,11.01"/>
                          </svg>
                          Active
                        </span>
                      @else
                        <span class="badge badge-danger">
                          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="15" y1="9" x2="9" y2="15"/>
                            <line x1="9" y1="9" x2="15" y2="15"/>
                          </svg>
                          Inactive
                        </span>
                      @endif
                    </div>
                  </td>
                  <td>
                    <div style="font-size: 0.85rem;">
                      @if($notice->start_date)
                        <div style="color: var(--text); margin-bottom: 0.25rem;">
                          <strong>Start:</strong> {{ $notice->start_date->format('M j, Y g:i A') }}
                        </div>
                      @endif
                      @if($notice->end_date)
                        <div style="color: var(--text); margin-bottom: 0.25rem;">
                          <strong>End:</strong> {{ $notice->end_date->format('M j, Y g:i A') }}
                        </div>
                      @endif
                      @if(!$notice->start_date && !$notice->end_date)
                        <div style="color: var(--text-muted); font-style: italic;">No schedule set</div>
                      @endif
                    </div>
                  </td>
                  <td>
                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                      <a class="btn btn-warning" href="{{ route('admin.notices.edit', $notice) }}" title="Edit Notice" style="padding: 0.5rem;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                          <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                      </a>
                      <form method="POST" action="{{ route('admin.notices.destroy', $notice) }}" onsubmit="return confirm('Are you sure you want to delete this notice? This action cannot be undone.')" style="display: inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger" type="submit" title="Delete Notice" style="padding: 0.5rem;">
                          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3,6 5,6 21,6"/>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                            <line x1="10" y1="11" x2="10" y2="17"/>
                            <line x1="14" y1="11" x2="14" y2="17"/>
                          </svg>
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

<script>
function toggleNoticeBar(settingsId) {
  const checkbox = event.target;
  const originalChecked = checkbox.checked;
  
  fetch(`/admin/notices/toggle-settings`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      'Accept': 'application/json',
    },
    body: JSON.stringify({
      settings_id: settingsId
    })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      // Update UI immediately
      location.reload(); // Refresh to show updated status
    } else {
      // Revert checkbox if failed
      checkbox.checked = !originalChecked;
      alert('Failed to update settings. Please try again.');
    }
  })
  .catch(error => {
    console.error('Error:', error);
    checkbox.checked = !originalChecked;
    alert('Network error. Please check your connection and try again.');
  });
}

// Simple client-side search functionality
document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('noticeSearch');
  if (searchInput) {
    searchInput.addEventListener('input', function() {
      const searchTerm = this.value.toLowerCase();
      const tableRows = document.querySelectorAll('.admin-table tbody tr');
      
      tableRows.forEach(row => {
        const title = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const content = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
        
        if (title.includes(searchTerm) || content.includes(searchTerm)) {
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
