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
    <div style="display: flex; gap: 1rem; align-items: center;">
      <button onclick="openBulkNotification()" class="btn-primary" style="padding: 12px 20px; background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.5rem;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
          <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
        </svg>
        Send Bulk Notification
      </button>
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
                        @if(auth()->user()->isAdmin())
                          <form method="POST" action="{{ route('admin.users.updateRole', $user) }}" style="display: inline;">
                            @csrf @method('PATCH')
                            <select name="role" class="role-select" onchange="this.form.submit()" style="font-size: 0.8rem; padding: 0.25rem;">
                              <option value="user" {{ ($user->role ?? 'user') === 'user' ? 'selected' : '' }}>User</option>
                              <option value="organizer" {{ $user->role === 'organizer' ? 'selected' : '' }}>Organizer</option>
                              <option value="manager" {{ $user->role === 'manager' ? 'selected' : '' }}>Manager</option>
                              <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                          </form>
                        @else
                          <div style="font-size: 0.8rem; color: var(--text-muted); font-style: italic;">
                            Role: {{ ucfirst($user->role ?? 'user') }} (Manager cannot change)
                          </div>
                        @endif
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
                        @if(auth()->user()->isAdmin())
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
                          <span class="badge badge-secondary" title="Manager cannot delete users">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                              <circle cx="12" cy="12" r="10"/>
                              <line x1="15" y1="9" x2="9" y2="15"/>
                              <line x1="9" y1="9" x2="15" y2="15"/>
                            </svg>
                            Restricted
                          </span>
                        @endif
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

<!-- User Detail Modal -->
<div id="userModal" class="modal-overlay" style="display: none;">
  <div class="modal-container">
    <div class="modal-header">
      <h3 class="modal-title">
        <svg class="modal-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
          <circle cx="12" cy="7" r="4"/>
        </svg>
        User Details
      </h3>
      <button class="modal-close" onclick="closeUserModal()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="18" y1="6" x2="6" y2="18"/>
          <line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
      </button>
    </div>
    
    <div class="modal-body">
      <div id="modalLoading" class="modal-loading">
        <div class="loading-spinner"></div>
        <p>Loading user details...</p>
      </div>
      
      <div id="modalContent" style="display: none;">
        <!-- User Profile Section -->
        <div class="user-profile-section">
          <div class="user-avatar-large" id="userAvatar"></div>
          <div class="user-info-main">
            <h4 id="userName" class="user-name-large"></h4>
            <p id="userEmail" class="user-email-large"></p>
            <div id="userRole" class="user-role-badge"></div>
          </div>
        </div>

        <!-- User Details Grid -->
        <div class="user-details-grid">
          <div class="detail-card">
            <div class="detail-header">
              <svg class="detail-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
              <h5>Account Information</h5>
            </div>
            <div class="detail-content">
              <div class="detail-item">
                <span class="detail-label">User ID:</span>
                <span id="userId" class="detail-value"></span>
              </div>
              <div class="detail-item">
                <span class="detail-label">Phone:</span>
                <span id="userPhone" class="detail-value"></span>
              </div>
              <div class="detail-item">
                <span class="detail-label">Registration Date:</span>
                <span id="userCreated" class="detail-value"></span>
              </div>
              <div class="detail-item">
                <span class="detail-label">Last Updated:</span>
                <span id="userUpdated" class="detail-value"></span>
              </div>
            </div>
          </div>

          <div class="detail-card">
            <div class="detail-header">
              <svg class="detail-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <h5>Account Status</h5>
            </div>
            <div class="detail-content">
              <div class="detail-item">
                <span class="detail-label">Email Verified:</span>
                <span id="userEmailVerified" class="detail-value"></span>
              </div>
              <div class="detail-item">
                <span class="detail-label">Account Status:</span>
                <span class="detail-value">
                  <span class="status-badge status-active">Active</span>
                </span>
              </div>
              <div class="detail-item">
                <span class="detail-label">Last Login:</span>
                <span id="userLastLogin" class="detail-value">Not available</span>
              </div>
            </div>
          </div>
        </div>

        <!-- User Activity Section -->
        <div class="detail-card full-width">
          <div class="detail-header">
            <svg class="detail-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="22,12 18,12 15,21 9,3 6,12 2,12"/>
            </svg>
            <h5>User Activity & Statistics</h5>
          </div>
          <div class="detail-content">
            <div class="activity-stats">
              <div class="stat-item">
                <div class="stat-number" id="userEventsCount">0</div>
                <div class="stat-label">Events Created</div>
              </div>
              <div class="stat-item">
                <div class="stat-number" id="userTicketsCount">0</div>
                <div class="stat-label">Tickets Purchased</div>
              </div>
              <div class="stat-item">
                <div class="stat-number" id="userDaysActive">0</div>
                <div class="stat-label">Days Active</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Admin Actions Section -->
        <div id="adminActions" class="admin-actions-section">
          <div class="detail-header">
            <svg class="detail-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="3"/>
              <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1 1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
            </svg>
            <h5>Administrative Actions</h5>
          </div>
          <div class="detail-content">
            <div class="action-buttons">
              <button class="btn btn-outline btn-sm" onclick="editUser()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Edit User
              </button>
              <button class="btn btn-warning btn-sm" onclick="resetPassword()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                  <circle cx="12" cy="16" r="1"/>
                  <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                Reset Password
              </button>
              <button class="btn btn-info btn-sm" onclick="sendNotification()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                  <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
                Send Notification
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeUserModal()">Close</button>
      <button class="btn btn-primary" onclick="printUserDetails()">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="6,9 6,2 18,2 18,9"/>
          <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
          <rect x="6" y="14" width="12" height="8"/>
        </svg>
        Print Details
      </button>
    </div>
  </div>
</div>

<style>
/* Modal Styles */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  animation: fadeIn 0.3s ease;
}

.modal-container {
  background: var(--card);
  border-radius: var(--radius-lg);
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
  max-width: 800px;
  width: 90%;
  max-height: 90vh;
  overflow: hidden;
  animation: slideIn 0.3s ease;
}

.modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1.5rem 2rem;
  border-bottom: 1px solid var(--border);
  background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

.modal-title {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin: 0;
  font-size: 1.25rem;
  font-weight: 600;
  color: var(--text);
}

.modal-icon {
  width: 1.25rem;
  height: 1.25rem;
  color: var(--primary);
}

.modal-close {
  background: none;
  border: none;
  cursor: pointer;
  padding: 0.5rem;
  border-radius: var(--radius);
  color: var(--text-muted);
  transition: all 0.2s ease;
}

.modal-close:hover {
  background: var(--border-light);
  color: var(--text);
}

.modal-close svg {
  width: 1rem;
  height: 1rem;
}

.modal-body {
  padding: 2rem;
  max-height: 60vh;
  overflow-y: auto;
}

.modal-loading {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 3rem;
  text-align: center;
}

.loading-spinner {
  width: 2rem;
  height: 2rem;
  border: 3px solid var(--border-light);
  border-top: 3px solid var(--primary);
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-bottom: 1rem;
}

/* User Profile Section */
.user-profile-section {
  display: flex;
  align-items: center;
  gap: 1.5rem;
  margin-bottom: 2rem;
  padding: 1.5rem;
  background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
  border-radius: var(--radius-lg);
  border: 1px solid var(--border);
}

.user-avatar-large {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  background: var(--primary);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 2rem;
  font-weight: 600;
  border: 3px solid white;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.user-info-main {
  flex: 1;
}

.user-name-large {
  font-size: 1.5rem;
  font-weight: 600;
  color: var(--text);
  margin: 0 0 0.5rem 0;
}

.user-email-large {
  font-size: 1rem;
  color: var(--text-light);
  margin: 0 0 0.75rem 0;
}

.user-role-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  border-radius: 9999px;
  font-size: 0.875rem;
  font-weight: 500;
}

/* Details Grid */
.user-details-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.detail-card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  overflow: hidden;
}

.detail-card.full-width {
  grid-column: 1 / -1;
}

.detail-header {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 1rem 1.5rem;
  background: var(--border-light);
  border-bottom: 1px solid var(--border);
}

.detail-icon {
  width: 1rem;
  height: 1rem;
  color: var(--primary);
}

.detail-header h5 {
  margin: 0;
  font-size: 0.875rem;
  font-weight: 600;
  color: var(--text);
}

.detail-content {
  padding: 1.5rem;
}

.detail-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.75rem 0;
  border-bottom: 1px solid var(--border-light);
}

.detail-item:last-child {
  border-bottom: none;
}

.detail-label {
  font-size: 0.875rem;
  color: var(--text-muted);
  font-weight: 500;
}

.detail-value {
  font-size: 0.875rem;
  color: var(--text);
  font-weight: 400;
}

/* Activity Stats */
.activity-stats {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.5rem;
}

.stat-item {
  text-align: center;
  padding: 1rem;
  background: var(--border-light);
  border-radius: var(--radius);
}

.stat-number {
  font-size: 2rem;
  font-weight: 700;
  color: var(--primary);
  margin-bottom: 0.25rem;
}

.stat-label {
  font-size: 0.75rem;
  color: var(--text-muted);
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

/* Status Badges */
.status-badge {
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 500;
}

.status-active {
  background: rgba(5, 150, 105, 0.1);
  color: var(--success);
  border: 1px solid rgba(5, 150, 105, 0.2);
}

.status-inactive {
  background: rgba(107, 114, 128, 0.1);
  color: var(--text-muted);
  border: 1px solid rgba(107, 114, 128, 0.2);
}

/* Admin Actions */
.admin-actions-section {
  margin-top: 1.5rem;
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  overflow: hidden;
}

.action-buttons {
  display: flex;
  gap: 0.75rem;
  flex-wrap: wrap;
}

.btn-sm {
  padding: 0.5rem 1rem;
  font-size: 0.875rem;
}

/* Modal Footer */
.modal-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem 2rem;
  border-top: 1px solid var(--border);
  background: var(--border-light);
}

/* Animations */
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slideIn {
  from { 
    opacity: 0;
    transform: translateY(-20px) scale(0.95);
  }
  to { 
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

/* Responsive Design */
@media (max-width: 768px) {
  .modal-container {
    width: 95%;
    max-height: 95vh;
  }
  
  .modal-header,
  .modal-footer {
    padding: 1rem 1.5rem;
  }
  
  .modal-body {
    padding: 1.5rem;
  }
  
  .user-details-grid {
    grid-template-columns: 1fr;
  }
  
  .activity-stats {
    grid-template-columns: 1fr;
  }
  
  .user-profile-section {
    flex-direction: column;
    text-align: center;
  }
  
  .action-buttons {
    flex-direction: column;
  }
  
  .modal-footer {
    flex-direction: column;
    gap: 1rem;
  }
}
</style>

<script>
// User Modal JavaScript
let currentUser = null;

function viewUser(userId) {
  console.log('Opening modal for user:', userId); // Debug log
  
  // Show modal
  const modal = document.getElementById('userModal');
  modal.style.display = 'flex';
  document.getElementById('modalLoading').style.display = 'flex';
  document.getElementById('modalContent').style.display = 'none';
  
  // Store current user ID
  currentUser = { id: userId };
  
  // Fetch user details via API
  fetchUserDetails(userId);
}

function fetchUserDetails(userId) {
  // Fetch user details from API
  fetch(`/admin/users/${userId}/details`)
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        currentUser = data.user;
        populateUserModal(currentUser);
        
        // Hide loading, show content
        document.getElementById('modalLoading').style.display = 'none';
        document.getElementById('modalContent').style.display = 'block';
      } else {
        throw new Error('Failed to fetch user details');
      }
    })
    .catch(error => {
      console.error('Error fetching user details:', error);
      alert('Error loading user details. Please try again.');
      closeUserModal();
    });
}

function extractUserDataFromRow(row, userId) {
  const cells = row.querySelectorAll('td');
  const userInfoCell = cells[1];
  const roleCell = cells[2];
  const dateCell = cells[3];
  
  // Extract name - look for the bold text in user info
  const nameElement = userInfoCell.querySelector('div[style*="font-weight: 600"]');
  const name = nameElement ? nameElement.textContent.trim() : 'Unknown User';
  
  // Extract email - look for the smaller text
  const emailElement = userInfoCell.querySelector('div[style*="font-size: 0.85rem"]');
  const email = emailElement ? emailElement.textContent.trim() : 'No email';
  
  // Extract phone - look for the smallest text
  const phoneElement = userInfoCell.querySelector('div[style*="font-size: 0.8rem"]');
  const phone = phoneElement ? phoneElement.textContent.trim() : 'Not provided';
  
  // Extract role from badge
  const roleBadge = roleCell.querySelector('.badge');
  let role = 'User';
  if (roleBadge) {
    const roleText = roleBadge.textContent.trim();
    // Clean up the role text - remove any icons or extra whitespace
    role = roleText.replace(/[^\w\s]/g, '').trim();
    if (!role) role = 'User';
  }
  
  // Extract dates
  const dateElements = dateCell.querySelectorAll('div');
  const dateText = dateElements[0] ? dateElements[0].textContent.trim() : '';
  const timeText = dateElements[1] ? dateElements[1].textContent.trim() : '';
  const relativeText = dateElements[2] ? dateElements[2].textContent.trim() : '';
  
  return {
    id: userId,
    name: name,
    email: email,
    phone: phone,
    role: role,
    created_at: `${dateText} ${timeText}`,
    relative_time: relativeText,
    email_verified: Math.random() > 0.3, // Random for demo
    events_count: Math.floor(Math.random() * 15),
    tickets_count: Math.floor(Math.random() * 25),
    days_active: Math.floor(Math.random() * 365)
  };
}

function populateUserModal(user) {
  console.log('Populating modal with user data:', user); // Debug log
  
  try {
    // User avatar and basic info
    const userAvatar = document.getElementById('userAvatar');
    const userName = document.getElementById('userName');
    const userEmail = document.getElementById('userEmail');
    
    if (userAvatar) userAvatar.textContent = user.name.charAt(0).toUpperCase();
    if (userName) userName.textContent = user.name;
    if (userEmail) userEmail.textContent = user.email;
    
    // Role badge
    const roleBadge = document.getElementById('userRole');
    if (roleBadge) {
      roleBadge.className = `user-role-badge badge ${getRoleBadgeClass(user.role)}`;
      roleBadge.innerHTML = `
        ${getRoleIcon(user.role)}
        ${user.role}
      `;
    }
    
    // Account information
    const userId = document.getElementById('userId');
    const userPhone = document.getElementById('userPhone');
    const userCreated = document.getElementById('userCreated');
    const userUpdated = document.getElementById('userUpdated');
    
    if (userId) userId.textContent = `#${user.id}`;
    if (userPhone) userPhone.textContent = user.phone;
    if (userCreated) userCreated.textContent = user.created_at;
    if (userUpdated) userUpdated.textContent = user.relative_time;
    
    // Email verification status
    const emailVerified = document.getElementById('userEmailVerified');
    if (emailVerified) {
      if (user.email_verified) {
        emailVerified.innerHTML = '<span class="status-badge status-active">✓ Verified</span>';
      } else {
        emailVerified.innerHTML = '<span class="status-badge status-inactive">✗ Not Verified</span>';
      }
    }
    
    // Last login information
    const lastLoginElement = document.getElementById('userLastLogin');
    if (lastLoginElement) {
      if (user.last_login_at && user.last_login_at !== 'Never logged in') {
        lastLoginElement.innerHTML = `
          <div style="color: var(--text);">${user.last_login_at}</div>
          <div style="font-size: 0.8rem; color: var(--text-light);">${user.last_login_relative}</div>
        `;
      } else {
        lastLoginElement.innerHTML = '<span style="color: var(--text-muted); font-style: italic;">Never logged in</span>';
      }
    }
    
    // Activity stats
    const userEventsCount = document.getElementById('userEventsCount');
    const userTicketsCount = document.getElementById('userTicketsCount');
    const userDaysActive = document.getElementById('userDaysActive');
    
    if (userEventsCount) userEventsCount.textContent = user.events_count;
    if (userTicketsCount) userTicketsCount.textContent = user.tickets_count;
    if (userDaysActive) userDaysActive.textContent = user.days_active;
    
    // Show/hide admin actions based on permissions
    const isCurrentUser = user.id == {{ auth()->id() }};
    const adminActions = document.getElementById('adminActions');
    
    if (adminActions) {
      if (isCurrentUser) {
        adminActions.style.display = 'none';
      } else {
        adminActions.style.display = 'block';
      }
    }
    
    console.log('Modal populated successfully');
  } catch (error) {
    console.error('Error populating modal:', error);
    alert('Error loading user details. Please try again.');
    closeUserModal();
  }
}

function getRoleBadgeClass(role) {
  switch(role.toLowerCase()) {
    case 'admin': return 'badge-success';
    case 'organizer': return 'badge-warning';
    case 'manager': return 'badge-info';
    default: return 'badge-secondary';
  }
}

function getRoleIcon(role) {
  const icons = {
    'admin': '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    'organizer': '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
    'manager': '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>',
    'user': '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>'
  };
  return icons[role.toLowerCase()] || icons['user'];
}

function closeUserModal() {
  document.getElementById('userModal').style.display = 'none';
  currentUser = null;
}

function editUser() {
  if (currentUser) {
    alert(`Edit user functionality would open here for ${currentUser.name}. This would typically open an edit form or redirect to an edit page.`);
  }
}

function resetPassword() {
  if (currentUser) {
    if (confirm(`Are you sure you want to reset the password for ${currentUser.name}? They will receive an email with instructions.`)) {
      alert(`Password reset email would be sent to ${currentUser.email}.`);
    }
  }
}

function sendNotification() {
  if (!currentUser) {
    alert('No user selected. Please try again.');
    return;
  }

  // Create a more sophisticated notification form
  const notificationForm = `
    <div style="background: white; padding: 2rem; border-radius: 12px; max-width: 500px; width: 90%; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
      <h3 style="margin: 0 0 1.5rem 0; color: var(--text); font-size: 1.25rem;">
        📨 Send Notification to ${currentUser.name}
      </h3>
      
      <form id="notificationForm">
        <div style="margin-bottom: 1rem;">
          <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text);">Template (Optional):</label>
          <select id="notificationTemplate" style="width: 100%; padding: 0.75rem; border: 2px solid var(--border); border-radius: 8px; font-size: 1rem;" onchange="fillFromTemplate()">
            <option value="">📝 Custom Message</option>
            @foreach($notificationTemplates->groupBy('category') as $category => $templates)
              <optgroup label="{{ ucfirst($category) }}">
                @foreach($templates as $template)
                  <option value="{{ $template->id }}" 
                          data-title="{{ $template->title }}" 
                          data-message="{{ $template->message }}" 
                          data-type="{{ $template->type }}"
                          data-variables="{{ json_encode($template->variables) }}">
                    {{ $template->name }}
                  </option>
                @endforeach
              </optgroup>
            @endforeach
          </select>
          <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">Select a pre-made template or create a custom message</div>
        </div>
        
        <div style="margin-bottom: 1rem;">
          <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text);">Notification Type:</label>
          <select id="notificationType" style="width: 100%; padding: 0.75rem; border: 2px solid var(--border); border-radius: 8px; font-size: 1rem;" required>
            <option value="general">📋 General Information</option>
            <option value="urgent">🚨 Urgent Notice</option>
            <option value="announcement">📢 Announcement</option>
            <option value="reminder">⏰ Reminder</option>
          </select>
        </div>
        
        <div style="margin-bottom: 1rem;">
          <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text);">Title:</label>
          <input type="text" id="notificationTitle" placeholder="Enter notification title..." 
                 style="width: 100%; padding: 0.75rem; border: 2px solid var(--border); border-radius: 8px; font-size: 1rem;" 
                 maxlength="255" required>
        </div>
        
        <div style="margin-bottom: 1rem;" id="templateVariables" style="display: none;">
          <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text);">Template Variables:</label>
          <div id="variableInputs"></div>
        </div>
        
        <div style="margin-bottom: 1.5rem;">
          <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text);">Message:</label>
          <textarea id="notificationMessage" placeholder="Enter your message here..." 
                    style="width: 100%; padding: 0.75rem; border: 2px solid var(--border); border-radius: 8px; font-size: 1rem; min-height: 120px; resize: vertical;" 
                    maxlength="1000" required></textarea>
          <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">Maximum 1000 characters</div>
        </div>
        
        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
          <button type="button" onclick="closeNotificationModal()" 
                  style="padding: 0.75rem 1.5rem; background: var(--border); color: var(--text); border: none; border-radius: 8px; font-size: 1rem; cursor: pointer;">
            Cancel
          </button>
          <button type="submit" 
                  style="padding: 0.75rem 1.5rem; background: var(--primary); color: white; border: none; border-radius: 8px; font-size: 1rem; cursor: pointer;">
            📤 Send Notification
          </button>
        </div>
      </form>
    </div>
  `;

  // Create modal overlay
  const modalOverlay = document.createElement('div');
  modalOverlay.id = 'notificationModal';
  modalOverlay.style.cssText = `
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 10000;
    backdrop-filter: blur(4px);
  `;
  modalOverlay.innerHTML = notificationForm;
  
  document.body.appendChild(modalOverlay);
  
  // Handle form submission
  document.getElementById('notificationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const templateSelect = document.getElementById('notificationTemplate');
    const templateId = templateSelect.value;
    const type = document.getElementById('notificationType').value;
    const title = document.getElementById('notificationTitle').value.trim();
    const message = document.getElementById('notificationMessage').value.trim();
    
    // If no template is selected, require manual input
    if (!templateId && (!title || !message)) {
      alert('Please fill in all fields or select a template.');
      return;
    }
    
    // Collect template variables if template is selected
    const variables = {};
    if (templateId) {
      const variableInputs = document.querySelectorAll('#variableInputs input');
      variableInputs.forEach(input => {
        variables[input.name] = input.value;
      });
    }
    
    // Send notification via API
    const submitButton = e.target.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    submitButton.innerHTML = '⏳ Sending...';
    submitButton.disabled = true;
    
    const requestBody = {
      type: type,
      title: title,
      message: message
    };
    
    if (templateId) {
      requestBody.template_id = templateId;
      requestBody.variables = variables;
    }
    
    fetch(`/admin/users/${currentUser.id}/send-notification`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(requestBody)
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert('✅ ' + data.message);
        closeNotificationModal();
      } else {
        throw new Error(data.message || 'Failed to send notification');
      }
    })
    .catch(error => {
      console.error('Error sending notification:', error);
      alert('❌ Failed to send notification: ' + error.message);
    })
    .finally(() => {
      submitButton.innerHTML = originalText;
      submitButton.disabled = false;
    });
  });
}

function closeNotificationModal() {
  const modal = document.getElementById('notificationModal');
  if (modal) {
    modal.remove();
  }
}

function fillFromTemplate() {
  const templateSelect = document.getElementById('notificationTemplate');
  const selectedOption = templateSelect.options[templateSelect.selectedIndex];
  
  if (templateSelect.value) {
    // Fill form fields from template
    const title = selectedOption.getAttribute('data-title');
    const message = selectedOption.getAttribute('data-message');
    const type = selectedOption.getAttribute('data-type');
    const variables = JSON.parse(selectedOption.getAttribute('data-variables') || '[]');
    
    document.getElementById('notificationTitle').value = title;
    document.getElementById('notificationMessage').value = message;
    document.getElementById('notificationType').value = type;
    
    // Show variable inputs if template has variables
    const variablesContainer = document.getElementById('templateVariables');
    const variableInputs = document.getElementById('variableInputs');
    
    if (variables.length > 0) {
      let variableHTML = '';
      variables.forEach(variable => {
        if (!['user_name', 'user_email'].includes(variable)) { // Skip auto-filled variables
          variableHTML += `
            <div style="margin-bottom: 0.5rem;">
              <label style="display: block; margin-bottom: 0.25rem; font-size: 0.9rem; color: var(--text-muted);">${variable.replace('_', ' ').toUpperCase()}:</label>
              <input type="text" name="${variable}" placeholder="Enter ${variable.replace('_', ' ')}" 
                     style="width: 100%; padding: 0.5rem; border: 1px solid var(--border); border-radius: 4px; font-size: 0.9rem;">
            </div>
          `;
        }
      });
      
      if (variableHTML) {
        variableInputs.innerHTML = variableHTML;
        variablesContainer.style.display = 'block';
      } else {
        variablesContainer.style.display = 'none';
      }
    } else {
      variablesContainer.style.display = 'none';
    }
    
    // Make title and message readonly when template is selected
    document.getElementById('notificationTitle').readOnly = true;
    document.getElementById('notificationMessage').readOnly = true;
    document.getElementById('notificationType').disabled = true;
  } else {
    // Clear fields and make them editable
    document.getElementById('notificationTitle').value = '';
    document.getElementById('notificationMessage').value = '';
    document.getElementById('notificationType').value = 'general';
    document.getElementById('templateVariables').style.display = 'none';
    
    document.getElementById('notificationTitle').readOnly = false;
    document.getElementById('notificationMessage').readOnly = false;
    document.getElementById('notificationType').disabled = false;
  }
}

function openBulkNotification() {
  const bulkNotificationForm = `
    <div style="background: white; padding: 2rem; border-radius: 12px; max-width: 600px; width: 90%; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
      <h3 style="margin: 0 0 1.5rem 0; color: var(--text); font-size: 1.25rem;">
        📢 Send Bulk Notification
      </h3>
      
      <form id="bulkNotificationForm">
        <div style="margin-bottom: 1rem;">
          <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text);">Send to:</label>
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 0.75rem; margin-bottom: 1rem;">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
              <input type="checkbox" name="roles[]" value="user" checked style="margin: 0;">
              <span>👤 Users</span>
            </label>
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
              <input type="checkbox" name="roles[]" value="organizer" checked style="margin: 0;">
              <span>📅 Organizers</span>
            </label>
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
              <input type="checkbox" name="roles[]" value="manager" checked style="margin: 0;">
              <span>⭐ Managers</span>
            </label>
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
              <input type="checkbox" name="roles[]" value="admin" style="margin: 0;">
              <span>🔧 Admins</span>
            </label>
          </div>
        </div>
        
        <div style="margin-bottom: 1rem;">
          <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text);">Template (Optional):</label>
          <select id="bulkNotificationTemplate" style="width: 100%; padding: 0.75rem; border: 2px solid var(--border); border-radius: 8px; font-size: 1rem;" onchange="fillFromBulkTemplate()">
            <option value="">📝 Custom Message</option>
            @foreach($notificationTemplates->groupBy('category') as $category => $templates)
              <optgroup label="{{ ucfirst($category) }}">
                @foreach($templates as $template)
                  <option value="{{ $template->id }}" 
                          data-title="{{ $template->title }}" 
                          data-message="{{ $template->message }}" 
                          data-type="{{ $template->type }}"
                          data-variables="{{ json_encode($template->variables) }}">
                    {{ $template->name }}
                  </option>
                @endforeach
              </optgroup>
            @endforeach
          </select>
          <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">Select a pre-made template or create a custom message</div>
        </div>
        
        <div style="margin-bottom: 1rem;">
          <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text);">Notification Type:</label>
          <select id="bulkNotificationType" style="width: 100%; padding: 0.75rem; border: 2px solid var(--border); border-radius: 8px; font-size: 1rem;" required>
            <option value="announcement">📢 Announcement</option>
            <option value="general">📋 General Information</option>
            <option value="urgent">🚨 Urgent Notice</option>
            <option value="reminder">⏰ Reminder</option>
          </select>
        </div>
        
        <div style="margin-bottom: 1rem;">
          <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text);">Title:</label>
          <input type="text" id="bulkNotificationTitle" placeholder="Enter notification title..." 
                 style="width: 100%; padding: 0.75rem; border: 2px solid var(--border); border-radius: 8px; font-size: 1rem;" 
                 maxlength="255" required>
        </div>
        
        <div style="margin-bottom: 1rem;" id="bulkTemplateVariables" style="display: none;">
          <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text);">Template Variables:</label>
          <div id="bulkVariableInputs"></div>
        </div>
        
        <div style="margin-bottom: 1.5rem;">
          <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text);">Message:</label>
          <textarea id="bulkNotificationMessage" placeholder="Enter your message here..." 
                    style="width: 100%; padding: 0.75rem; border: 2px solid var(--border); border-radius: 8px; font-size: 1rem; min-height: 120px; resize: vertical;" 
                    maxlength="1000" required></textarea>
          <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">Maximum 1000 characters</div>
        </div>
        
        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
          <button type="button" onclick="closeBulkNotificationModal()" 
                  style="padding: 0.75rem 1.5rem; background: var(--border); color: var(--text); border: none; border-radius: 8px; font-size: 1rem; cursor: pointer;">
            Cancel
          </button>
          <button type="submit" 
                  style="padding: 0.75rem 1.5rem; background: var(--primary); color: white; border: none; border-radius: 8px; font-size: 1rem; cursor: pointer;">
            📤 Send to All Selected
          </button>
        </div>
      </form>
    </div>
  `;

  // Create modal overlay
  const modalOverlay = document.createElement('div');
  modalOverlay.id = 'bulkNotificationModal';
  modalOverlay.style.cssText = `
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 10000;
    backdrop-filter: blur(4px);
  `;
  modalOverlay.innerHTML = bulkNotificationForm;
  
  document.body.appendChild(modalOverlay);
  
  // Handle form submission
  document.getElementById('bulkNotificationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const selectedRoles = Array.from(document.querySelectorAll('input[name="roles[]"]:checked')).map(cb => cb.value);
    const templateSelect = document.getElementById('bulkNotificationTemplate');
    const templateId = templateSelect.value;
    const type = document.getElementById('bulkNotificationType').value;
    const title = document.getElementById('bulkNotificationTitle').value.trim();
    const message = document.getElementById('bulkNotificationMessage').value.trim();
    
    if (selectedRoles.length === 0) {
      alert('Please select at least one user role to send notification to.');
      return;
    }
    
    // If no template is selected, require manual input
    if (!templateId && (!title || !message)) {
      alert('Please fill in all fields or select a template.');
      return;
    }
    
    // Collect template variables if template is selected
    const variables = {};
    if (templateId) {
      const variableInputs = document.querySelectorAll('#bulkVariableInputs input');
      variableInputs.forEach(input => {
        variables[input.name] = input.value;
      });
    }
    
    // Confirm action
    const roleNames = selectedRoles.map(role => {
      const roleMap = { user: 'Users', organizer: 'Organizers', manager: 'Managers', admin: 'Admins' };
      return roleMap[role];
    }).join(', ');
    
    if (!confirm(`Are you sure you want to send this notification to all ${roleNames}?`)) {
      return;
    }
    
    // Send bulk notification via API
    const submitButton = e.target.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    submitButton.innerHTML = '⏳ Sending...';
    submitButton.disabled = true;
    
    const requestBody = {
      roles: selectedRoles,
      type: type,
      title: title,
      message: message
    };
    
    if (templateId) {
      requestBody.template_id = templateId;
      requestBody.variables = variables;
    }
    
    fetch('/admin/users/send-bulk-notification', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(requestBody)
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert('✅ ' + data.message);
        closeBulkNotificationModal();
      } else {
        throw new Error(data.message || 'Failed to send bulk notification');
      }
    })
    .catch(error => {
      console.error('Error sending bulk notification:', error);
      alert('❌ Failed to send bulk notification: ' + error.message);
    })
    .finally(() => {
      submitButton.innerHTML = originalText;
      submitButton.disabled = false;
    });
  });
}

function closeBulkNotificationModal() {
  const modal = document.getElementById('bulkNotificationModal');
  if (modal) {
    modal.remove();
  }
}

function fillFromBulkTemplate() {
  const templateSelect = document.getElementById('bulkNotificationTemplate');
  const selectedOption = templateSelect.options[templateSelect.selectedIndex];
  
  if (templateSelect.value) {
    // Fill form fields from template
    const title = selectedOption.getAttribute('data-title');
    const message = selectedOption.getAttribute('data-message');
    const type = selectedOption.getAttribute('data-type');
    const variables = JSON.parse(selectedOption.getAttribute('data-variables') || '[]');
    
    document.getElementById('bulkNotificationTitle').value = title;
    document.getElementById('bulkNotificationMessage').value = message;
    document.getElementById('bulkNotificationType').value = type;
    
    // Show variable inputs if template has variables
    const variablesContainer = document.getElementById('bulkTemplateVariables');
    const variableInputs = document.getElementById('bulkVariableInputs');
    
    if (variables.length > 0) {
      let variableHTML = '';
      variables.forEach(variable => {
        if (!['user_name', 'user_email'].includes(variable)) { // Skip auto-filled variables
          variableHTML += `
            <div style="margin-bottom: 0.5rem;">
              <label style="display: block; margin-bottom: 0.25rem; font-size: 0.9rem; color: var(--text-muted);">${variable.replace('_', ' ').toUpperCase()}:</label>
              <input type="text" name="${variable}" placeholder="Enter ${variable.replace('_', ' ')}" 
                     style="width: 100%; padding: 0.5rem; border: 1px solid var(--border); border-radius: 4px; font-size: 0.9rem;">
            </div>
          `;
        }
      });
      
      if (variableHTML) {
        variableInputs.innerHTML = variableHTML;
        variablesContainer.style.display = 'block';
      } else {
        variablesContainer.style.display = 'none';
      }
    } else {
      variablesContainer.style.display = 'none';
    }
    
    // Make title and message readonly when template is selected
    document.getElementById('bulkNotificationTitle').readOnly = true;
    document.getElementById('bulkNotificationMessage').readOnly = true;
    document.getElementById('bulkNotificationType').disabled = true;
  } else {
    // Clear fields and make them editable
    document.getElementById('bulkNotificationTitle').value = '';
    document.getElementById('bulkNotificationMessage').value = '';
    document.getElementById('bulkNotificationType').value = 'announcement';
    document.getElementById('bulkTemplateVariables').style.display = 'none';
    
    document.getElementById('bulkNotificationTitle').readOnly = false;
    document.getElementById('bulkNotificationMessage').readOnly = false;
    document.getElementById('bulkNotificationType').disabled = false;
  }
}

function printUserDetails() {
  if (!currentUser) {
    alert('No user data available to print.');
    return;
  }
  
  // Create a comprehensive printable report
  const printContent = `
    <!DOCTYPE html>
    <html>
    <head>
      <title>User Details Report - ${currentUser.name}</title>
      <style>
        body {
          font-family: Arial, sans-serif;
          line-height: 1.6;
          margin: 0;
          padding: 20px;
          color: #333;
        }
        .header {
          text-align: center;
          border-bottom: 2px solid #007bff;
          padding-bottom: 20px;
          margin-bottom: 30px;
        }
        .header h1 {
          color: #007bff;
          margin: 0;
          font-size: 28px;
        }
        .header p {
          color: #666;
          margin: 5px 0 0 0;
          font-size: 14px;
        }
        .user-profile {
          background: #f8f9fa;
          padding: 20px;
          border-radius: 8px;
          margin-bottom: 30px;
          text-align: center;
        }
        .user-profile h2 {
          margin: 0 0 10px 0;
          color: #007bff;
          font-size: 24px;
        }
        .user-profile .role {
          display: inline-block;
          background: #007bff;
          color: white;
          padding: 5px 15px;
          border-radius: 20px;
          font-size: 12px;
          text-transform: uppercase;
          letter-spacing: 1px;
          margin-top: 10px;
        }
        .section {
          margin-bottom: 25px;
        }
        .section h3 {
          color: #495057;
          border-bottom: 1px solid #dee2e6;
          padding-bottom: 8px;
          margin-bottom: 15px;
        }
        .detail-grid {
          display: grid;
          grid-template-columns: 1fr 1fr;
          gap: 20px;
          margin-bottom: 20px;
        }
        .detail-item {
          display: flex;
          justify-content: space-between;
          padding: 8px 0;
          border-bottom: 1px dotted #dee2e6;
        }
        .detail-label {
          font-weight: 600;
          color: #495057;
        }
        .detail-value {
          color: #212529;
        }
        .stats-section {
          background: #e3f2fd;
          padding: 20px;
          border-radius: 8px;
          margin: 20px 0;
        }
        .stats-grid {
          display: grid;
          grid-template-columns: repeat(3, 1fr);
          gap: 20px;
          text-align: center;
        }
        .stat-item {
          background: white;
          padding: 15px;
          border-radius: 8px;
          box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .stat-number {
          font-size: 32px;
          font-weight: bold;
          color: #007bff;
          margin-bottom: 5px;
        }
        .stat-label {
          font-size: 12px;
          color: #666;
          text-transform: uppercase;
          letter-spacing: 1px;
        }
        .footer {
          margin-top: 40px;
          padding-top: 20px;
          border-top: 1px solid #dee2e6;
          text-align: center;
          color: #666;
          font-size: 12px;
        }
        @media print {
          body { margin: 0; }
          .header, .user-profile, .stats-section { break-inside: avoid; }
        }
      </style>
    </head>
    <body>
      <div class="header">
        <h1>EventEase User Report</h1>
        <p>Detailed User Information & Activity Summary</p>
      </div>
      
      <div class="user-profile">
        <h2>${currentUser.name}</h2>
        <p>${currentUser.email}</p>
        <div class="role">${currentUser.role}</div>
      </div>
      
      <div class="section">
        <h3>Account Information</h3>
        <div class="detail-grid">
          <div>
            <div class="detail-item">
              <span class="detail-label">User ID:</span>
              <span class="detail-value">#${currentUser.id}</span>
            </div>
            <div class="detail-item">
              <span class="detail-label">Email Address:</span>
              <span class="detail-value">${currentUser.email}</span>
            </div>
            <div class="detail-item">
              <span class="detail-label">Phone Number:</span>
              <span class="detail-value">${currentUser.phone}</span>
            </div>
          </div>
          <div>
            <div class="detail-item">
              <span class="detail-label">Account Role:</span>
              <span class="detail-value">${currentUser.role}</span>
            </div>
            <div class="detail-item">
              <span class="detail-label">Registration Date:</span>
              <span class="detail-value">${currentUser.created_at}</span>
            </div>
            <div class="detail-item">
              <span class="detail-label">Email Status:</span>
              <span class="detail-value">${currentUser.email_verified ? 'Verified' : 'Not Verified'}</span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="stats-section">
        <h3>Activity Statistics</h3>
        <div class="stats-grid">
          <div class="stat-item">
            <div class="stat-number">${currentUser.events_count}</div>
            <div class="stat-label">Events Created</div>
          </div>
          <div class="stat-item">
            <div class="stat-number">${currentUser.tickets_count}</div>
            <div class="stat-label">Tickets Purchased</div>
          </div>
          <div class="stat-item">
            <div class="stat-number">${currentUser.days_active}</div>
            <div class="stat-label">Days Active</div>
          </div>
        </div>
      </div>
      
      <div class="section">
        <h3>Account Summary</h3>
        <p><strong>Account Status:</strong> Active</p>
        <p><strong>Last Activity:</strong> ${currentUser.relative_time}</p>
        <p><strong>Account Type:</strong> ${currentUser.role} User</p>
        <p><strong>Platform Access:</strong> Full Access Granted</p>
      </div>
      
      <div class="footer">
        <p>Report generated on ${new Date().toLocaleString()}</p>
        <p>EventEase Admin Panel - User Management System</p>
        <p>This document contains confidential user information</p>
      </div>
    </body>
    </html>
  `;
  
  // Create a new window for printing
  const printWindow = window.open('', '_blank', 'width=800,height=600');
  if (printWindow) {
    printWindow.document.write(printContent);
    printWindow.document.close();
    
    // Wait for content to load then print
    printWindow.onload = function() {
      setTimeout(() => {
        printWindow.print();
        // Close window after printing (optional)
        setTimeout(() => {
          printWindow.close();
        }, 1000);
      }, 500);
    };
  } else {
    alert('Please allow popups to enable printing functionality.');
  }
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
  console.log('User modal script loaded'); // Debug log
  
  const modal = document.getElementById('userModal');
  if (modal) {
    modal.addEventListener('click', function(e) {
      if (e.target === modal) {
        closeUserModal();
      }
    });
  }
  
  // Close modal with Escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeUserModal();
    }
  });
  
  // Test modal elements exist
  const requiredElements = [
    'userModal', 'modalLoading', 'modalContent', 'userAvatar', 
    'userName', 'userEmail', 'userRole', 'userId', 'userPhone'
  ];
  
  requiredElements.forEach(id => {
    const element = document.getElementById(id);
    if (!element) {
      console.warn(`Required modal element not found: ${id}`);
    }
  });
});
</script>
@endsection
