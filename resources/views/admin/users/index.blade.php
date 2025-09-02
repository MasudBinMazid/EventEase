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
    </div>
  </div>

  <!-- Search and Filter Section -->
  <div class="admin-card" style="margin-bottom: 1.5rem;">
    <div class="card-body">
      <form method="GET" action="{{ route('admin.users.index') }}" class="search-form">
        <div style="display: grid; grid-template-columns: 1fr auto auto auto; gap: 1rem; align-items: end;">
          <div>
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text);">Search Users</label>
            <input 
              type="text" 
              name="search" 
              value="{{ request('search') }}" 
              placeholder="Search by name, email, or ID..." 
              class="form-input"
              style="width: 100%;"
            >
          </div>
          <div>
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text);">Filter by Role</label>
            <select name="role" class="form-select">
              <option value="">All Roles</option>
              <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
              <option value="organizer" {{ request('role') === 'organizer' ? 'selected' : '' }}>Organizer</option>
              <option value="manager" {{ request('role') === 'manager' ? 'selected' : '' }}>Manager</option>
              <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="11" cy="11" r="8"/>
              <path d="m21 21-4.35-4.35"/>
            </svg>
            Search
          </button>
          @if(request()->anyFilled(['search', 'role']))
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="1,4 1,10 7,10"/>
                <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>
              </svg>
              Clear
            </a>
          @endif
        </div>
      </form>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-number">{{ $users->where('role', 'admin')->count() }}</div>
      <div class="stat-label">Admin Users</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">{{ $users->where('role', 'organizer')->count() }}</div>
      <div class="stat-label">Organizers</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">{{ $users->where('role', '!=', 'admin')->count() }}</div>
      <div class="stat-label">Regular Users</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">{{ $users->where('created_at', '>=', now()->subDays(7))->count() }}</div>
      <div class="stat-label">New This Week</div>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
  @endif

  <!-- Users Table -->
  <div class="admin-card">
    <div class="card-header">
      <h3 class="card-title">
        @if(request()->anyFilled(['search', 'role']))
          Search Results ({{ $users->count() }} users found)
        @else
          All Users
        @endif
      </h3>
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
          <p>
            @if(request()->anyFilled(['search', 'role']))
              No users found matching your search criteria.
            @else
              No users found.
            @endif
          </p>
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
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                      @if(($user->role ?? '') === 'admin')
                        <span class="badge badge-success">
                          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                          </svg>
                          Admin
                        </span>
                      @elseif(($user->role ?? '') === 'organizer')
                        <span class="badge badge-warning">
                          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                          </svg>
                          Organizer
                        </span>
                      @elseif(($user->role ?? '') === 'manager')
                        <span class="badge badge-info">
                          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                          </svg>
                          Manager
                        </span>
                      @else
                        <span class="badge badge-secondary">
                          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                          </svg>
                          User
                        </span>
                      @endif
                      
                      @if(auth()->id() !== $user->id)
                        <form method="POST" action="{{ route('admin.users.updateRole', $user) }}" style="display: inline;">
                          @csrf @method('PATCH')
                          <select name="role" class="role-select" onchange="this.form.submit()" style="font-size: 0.8rem; padding: 0.25rem;">
                            <option value="user" {{ ($user->role ?? 'user') === 'user' ? 'selected' : '' }}>User</option>
                            <option value="organizer" {{ $user->role === 'organizer' ? 'selected' : '' }}>Organizer</option>
                            <option value="manager" {{ $user->role === 'manager' ? 'selected' : '' }}>Manager</option>
                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                          </select>
                        </form>
                      @endif
                    </div>
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

<style>
.form-input, .form-select {
  background: #fff; 
  border: 1px solid var(--border); 
  border-radius: 8px; 
  padding: 0.75rem; 
  font-size: 0.9rem;
  transition: border-color 0.2s ease;
}

.form-input:focus, .form-select:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.role-select {
  background: #fff;
  border: 1px solid var(--border);
  border-radius: 4px;
  padding: 0.25rem;
  font-size: 0.8rem;
  cursor: pointer;
  transition: border-color 0.2s ease;
}

.role-select:hover {
  border-color: var(--primary);
}

.alert {
  padding: 1rem;
  border-radius: 8px;
  margin-bottom: 1rem;
  font-weight: 500;
}

.alert-success {
  background: #dcfce7;
  border: 1px solid #bbf7d0;
  color: #166534;
}

.alert-error {
  background: #fef2f2;
  border: 1px solid #fecaca;
  color: #dc2626;
}

.badge-secondary {
  background: #f3f4f6;
  color: #374151;
  border: 1px solid #d1d5db;
}

.badge-warning {
  background: #fef3c7;
  color: #92400e;
  border: 1px solid #fde68a;
}
</style>

<!-- User Detail Modal (Optional - can be implemented later) -->
<script>
function viewUser(userId) {
  alert('User details modal would open here for user #' + userId + '. This feature can be implemented later.');
}
</script>
@endsection
