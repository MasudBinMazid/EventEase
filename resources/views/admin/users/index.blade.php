@extends('admin.layout')
@section('title','Users Management')

@section('content')
<div class="admin-page">
  <!-- Page Header -->
  <div class="admin-header">
    <div>
      <h1 class="admin-title">Users Management</h1>
      <p class="admin-subtitle">Manage all registered users and their permissions</p>
    </div>
    <div class="admin-actions">
      <div class="badge badge-info">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
          <circle cx="9" cy="7" r="4"/>
          <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
          <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
        </svg>
        {{ $users->count() }} Total Users
      </div>
      <button class="btn btn-primary" onclick="window.location.reload()">
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
      <div class="stat-number">{{ $users->where('role', 'admin')->count() }}</div>
      <div class="stat-label">Admin Users</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">{{ $users->where('role', '!=', 'admin')->count() }}</div>
      <div class="stat-label">Regular Users</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">{{ $users->where('created_at', '>=', now()->subDays(30))->count() }}</div>
      <div class="stat-label">New This Month</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">{{ $users->where('created_at', '>=', now()->subDays(7))->count() }}</div>
      <div class="stat-label">New This Week</div>
    </div>
  </div>

  <!-- Users Table -->
  <div class="admin-card">
    <div class="card-header">
      <h3 class="card-title">All Users</h3>
    </div>
    <div class="card-body" style="padding: 0;">
      @if($users->isEmpty())
        <div style="padding: 3rem; text-align: center; color: var(--text-light);">
          <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="margin-bottom: 1rem; opacity: 0.5;">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
          </svg>
          <p>No users found.</p>
        </div>
      @else
        <div style="overflow-x: auto;">
          <table class="admin-table">
            <thead>
              <tr>
                <th style="width: 80px;">#</th>
                <th>User Info</th>
                <th>Role & Status</th>
                <th>Joined Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($users as $user)
                <tr>
                  <td>
                    <span style="font-weight: 600; color: var(--primary);">#{{ $user->id }}</span>
                  </td>
                  <td>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                      <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.9rem;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                      </div>
                      <div>
                        <div style="font-weight: 600; color: var(--text);">{{ $user->name }}</div>
                        <div style="font-size: 0.85rem; color: var(--text-light);">{{ $user->email }}</div>
                        @if($user->phone)
                          <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $user->phone }}</div>
                        @endif
                      </div>
                    </div>
                  </td>
                  <td>
                    @if(($user->role ?? '') === 'admin')
                      <span class="badge badge-success">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Admin
                      </span>
                    @else
                      <span class="badge badge-info">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                          <circle cx="12" cy="7" r="4"/>
                        </svg>
                        {{ $user->role ?: 'User' }}
                      </span>
                    @endif
                  </td>
                  <td>
                    <div style="color: var(--text);">{{ $user->created_at->format('M j, Y') }}</div>
                    <div style="font-size: 0.8rem; color: var(--text-light);">{{ $user->created_at->format('g:i A') }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $user->created_at->diffForHumans() }}</div>
                  </td>
                  <td>
                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                      @if(auth()->id() !== $user->id)
                        <button class="btn btn-outline" onclick="viewUser({{ $user->id }})" title="View Details">
                          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                          </svg>
                        </button>
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')" style="display: inline;">
                          @csrf @method('DELETE')
                          <button class="btn btn-danger" type="submit" title="Delete User" style="padding: 0.5rem;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                              <polyline points="3,6 5,6 21,6"/>
                              <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                              <line x1="10" y1="11" x2="10" y2="17"/>
                              <line x1="14" y1="11" x2="14" y2="17"/>
                            </svg>
                          </button>
                        </form>
                      @else
                        <span class="badge badge-warning">
                          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                          </svg>
                          You
                        </span>
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

<!-- User Detail Modal (Optional - can be implemented later) -->
<script>
function viewUser(userId) {
  alert('User details modal would open here for user #' + userId + '. This feature can be implemented later.');
}
</script>
@endsection
