@extends('admin.layout')
@section('title','Admin Dashboard')

@section('extra-css')
<style>
:root {
  /* Clean Professional Palette */
  --admin-bg: #f8fafc;
  --admin-white: #ffffff;
  --admin-gray-50: #f9fafb;
  --admin-gray-100: #f3f4f6;
  --admin-gray-200: #e5e7eb;
  --admin-gray-300: #d1d5db;
  --admin-gray-600: #4b5563;
  --admin-gray-700: #374151;
  --admin-gray-900: #111827;
  
  /* Modern Brand Colors */
  --admin-primary: #3b82f6;
  --admin-primary-dark: #2563eb;
  --admin-success: #10b981;
  --admin-warning: #f59e0b;
  --admin-danger: #ef4444;
  --admin-purple: #8b5cf6;
  --admin-indigo: #6366f1;
  --admin-pink: #ec4899;
  
  /* Shadows & Effects */
  --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
  --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
  --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
  --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
  --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
  
  --radius: 0.75rem;
  --radius-lg: 1rem;
}

.admin-container {
  background: var(--admin-bg);
  min-height: 100vh;
  padding: 2rem;
}

.admin-header {
  background: var(--admin-white);
  border-radius: var(--radius-lg);
  padding: 2rem;
  margin-bottom: 2rem;
  box-shadow: var(--shadow);
  border: 1px solid var(--admin-gray-200);
}

.admin-welcome {
  margin-bottom: 0.5rem;
}

.admin-welcome h1 {
  font-size: 2rem;
  font-weight: 700;
  color: var(--admin-gray-900);
  margin: 0;
  letter-spacing: -0.025em;
}

.admin-welcome p {
  color: var(--admin-gray-600);
  font-size: 1.1rem;
  margin: 0.5rem 0 0;
  font-weight: 400;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.stat-card {
  background: var(--admin-white);
  border-radius: var(--radius);
  padding: 1.5rem;
  box-shadow: var(--shadow);
  border: 1px solid var(--admin-gray-200);
  transition: all 0.2s ease;
  position: relative;
}

.stat-card:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow-lg);
}

.stat-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  border-radius: var(--radius) var(--radius) 0 0;
  background: var(--color);
}

.stat-card.users { --color: var(--admin-primary); }
.stat-card.events { --color: var(--admin-success); }
.stat-card.tickets { --color: var(--admin-purple); }
.stat-card.revenue { --color: var(--admin-warning); }

.stat-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1rem;
}

.stat-icon {
  width: 3rem;
  height: 3rem;
  border-radius: var(--radius);
  background: var(--color);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
}

.stat-trend {
  font-size: 0.875rem;
  font-weight: 500;
  padding: 0.25rem 0.5rem;
  border-radius: 0.375rem;
  background: var(--admin-gray-100);
  color: var(--admin-gray-600);
}

.stat-number {
  font-size: 2.5rem;
  font-weight: 700;
  color: var(--admin-gray-900);
  line-height: 1;
  margin-bottom: 0.25rem;
}

.stat-label {
  font-size: 0.875rem;
  font-weight: 500;
  color: var(--admin-gray-600);
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.actions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 1.5rem;
}

.action-card {
  background: var(--admin-white);
  border-radius: var(--radius-lg);
  padding: 2rem;
  box-shadow: var(--shadow);
  border: 1px solid var(--admin-gray-200);
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.action-card:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow-xl);
}

.action-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(135deg, var(--color-light) 0%, transparent 50%);
  opacity: 0;
  transition: opacity 0.3s ease;
}

.action-card:hover::before {
  opacity: 1;
}

.action-card.users { 
  --color: var(--admin-primary); 
  --color-light: rgba(59, 130, 246, 0.05);
}
.action-card.events { 
  --color: var(--admin-success); 
  --color-light: rgba(16, 185, 129, 0.05);
}
.action-card.settings { 
  --color: var(--admin-purple); 
  --color-light: rgba(139, 92, 246, 0.05);
}
.action-card.reports { 
  --color: var(--admin-pink); 
  --color-light: rgba(236, 72, 153, 0.05);
}
.action-card.maintenance { 
  --color: var(--admin-warning); 
  --color-light: rgba(245, 158, 11, 0.05);
}

.action-header {
  display: flex;
  align-items: flex-start;
  gap: 1rem;
  margin-bottom: 1.5rem;
  position: relative;
  z-index: 2;
}

.action-icon {
  width: 3.5rem;
  height: 3.5rem;
  border-radius: var(--radius);
  background: var(--color);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  flex-shrink: 0;
}

.action-content h3 {
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--admin-gray-900);
  margin: 0 0 0.5rem;
  letter-spacing: -0.025em;
}

.action-content p {
  color: var(--admin-gray-600);
  font-size: 0.95rem;
  line-height: 1.6;
  margin: 0;
}

.action-footer {
  position: relative;
  z-index: 2;
}

.admin-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  background: var(--color);
  color: white;
  text-decoration: none;
  border-radius: var(--radius);
  font-weight: 600;
  font-size: 0.95rem;
  transition: all 0.2s ease;
  box-shadow: var(--shadow-sm);
}

.admin-btn:hover {
  background: var(--color);
  filter: brightness(0.95);
  transform: translateY(-1px);
  box-shadow: var(--shadow-md);
}

.admin-btn:active {
  transform: translateY(0);
}

.admin-btn svg {
  width: 1rem;
  height: 1rem;
}

/* Responsive Design */
@media (max-width: 768px) {
  .admin-container {
    padding: 1rem;
  }
  
  .admin-header {
    padding: 1.5rem;
  }
  
  .admin-welcome h1 {
    font-size: 1.75rem;
  }
  
  .stats-grid,
  .actions-grid {
    grid-template-columns: 1fr;
  }
  
  .stat-card,
  .action-card {
    padding: 1.25rem;
  }
}

@media (max-width: 480px) {
  .admin-container {
    padding: 0.75rem;
  }
  
  .admin-header {
    padding: 1.25rem;
  }
  
  .admin-welcome h1 {
    font-size: 1.5rem;
  }
  
  .admin-welcome p {
    font-size: 1rem;
  }
  
  .action-header {
    flex-direction: column;
    text-align: center;
  }
  
  .action-icon {
    align-self: center;
  }
}

/* Focus styles for accessibility */
.admin-btn:focus {
  outline: 2px solid var(--color);
  outline-offset: 2px;
}

/* Loading state */
.loading {
  opacity: 0.7;
  pointer-events: none;
}

/* Smooth transitions */
* {
  scroll-behavior: smooth;
}
</style>
@endsection

@section('content')
<div class="admin-container">
  <!-- Welcome Header -->
  <div class="admin-header">
    <div class="admin-welcome">
      <h1>Welcome back, {{ auth()->user()->name }}</h1>
      <p>Here's an overview of your EventEase platform today.</p>
    </div>
  </div>

  <!-- Statistics Cards -->
  <div class="stats-grid">
    <div class="stat-card users">
      <div class="stat-header">
        <div class="stat-icon">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
          </svg>
        </div>
        <div class="stat-trend">+12%</div>
      </div>
      <div class="stat-number">{{ \App\Models\User::count() ?? '0' }}</div>
      <div class="stat-label">Total Users</div>
    </div>

    <div class="stat-card events">
      <div class="stat-header">
        <div class="stat-icon">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
        </div>
        <div class="stat-trend">+8%</div>
      </div>
      <div class="stat-number">{{ \App\Models\Event::count() ?? '0' }}</div>
      <div class="stat-label">Total Events</div>
    </div>

    <div class="stat-card tickets">
      <div class="stat-header">
        <div class="stat-icon">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
          </svg>
        </div>
        <div class="stat-trend">+23%</div>
      </div>
      <div class="stat-number">{{ \App\Models\Ticket::count() ?? '0' }}</div>
      <div class="stat-label">Total Tickets</div>
    </div>

    <div class="stat-card revenue">
      <div class="stat-header">
        <div class="stat-icon">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
          </svg>
        </div>
        <div class="stat-trend">+15%</div>
      </div>
      <div class="stat-number">{{ \App\Models\Event::where('status', 'approved')->count() ?? '0' }}</div>
      <div class="stat-label">Active Events</div>
    </div>
  </div>

  <!-- Action Cards -->
  <div class="actions-grid">
    <div class="action-card users">
      <div class="action-header">
        <div class="action-icon">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
          </svg>
        </div>
        <div class="action-content">
          <h3>User Management</h3>
          <p>Manage user accounts, view profiles, handle permissions, and monitor user activity across the platform.</p>
        </div>
      </div>
      <div class="action-footer">
        <a href="{{ route('admin.users.index') }}" class="admin-btn">
          <span>Manage Users</span>
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </a>
      </div>
    </div>

    <div class="action-card events">
      <div class="action-header">
        <div class="action-icon">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
        </div>
        <div class="action-content">
          <h3>Event Management</h3>
          <p>Create, edit, approve events. Handle event requests, manage scheduling, and oversee event configurations.</p>
        </div>
      </div>
      <div class="action-footer">
        <a href="{{ route('admin.events.index') }}" class="admin-btn">
          <span>Manage Events</span>
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </a>
      </div>
    </div>

    <div class="action-card settings">
      <div class="action-header">
        <div class="action-icon">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
          </svg>
        </div>
        <div class="action-content">
          <h3>System Settings</h3>
          <p>Configure application settings, manage system preferences, and handle administrative configurations.</p>
        </div>
      </div>
      <div class="action-footer">
        <a href="#" class="admin-btn" onclick="alert('Settings panel coming soon!')">
          <span>System Settings</span>
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </a>
      </div>
    </div>

    <div class="action-card reports">
      <div class="action-header">
        <div class="action-icon">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
          </svg>
        </div>
        <div class="action-content">
          <h3>Reports & Analytics</h3>
          <p>View detailed reports, analytics, and insights about platform performance and user engagement metrics.</p>
        </div>
      </div>
      <div class="action-footer">
        <a href="#" class="admin-btn" onclick="alert('Analytics dashboard coming soon!')">
          <span>View Reports</span>
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </a>
      </div>
    </div>

    @if(Auth::user()->isAdmin())
    <div class="action-card maintenance">
      <div class="action-header">
        <div class="action-icon">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
          </svg>
        </div>
        <div class="action-content">
          <h3>Maintenance Mode</h3>
          <p>Control site accessibility during maintenance periods. Put the site into maintenance mode when performing updates.</p>
        </div>
      </div>
      <div class="action-footer">
        <a href="{{ route('admin.maintenance.index') }}" class="admin-btn">
          <span>Manage Maintenance</span>
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </a>
      </div>
    </div>
    @endif
  </div>
</div>
@endsection
