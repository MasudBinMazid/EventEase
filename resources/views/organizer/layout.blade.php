<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title','Organizer Panel') • EventEase Organizer</title>
  @stack('head')
  @yield('extra-css')
  <style>
    :root{
      /* Modern Organizer Panel Colors */
      --bg:#f8fafc;
      --card:#ffffff;
      --text:#1f2937;
      --text-light:#6b7280;
      --text-muted:#9ca3af;
      --border:#e5e7eb;
      --border-light:#f3f4f6;
      
      /* Brand Colors */
      --primary:#6366f1;
      --primary-dark:#4f46e5;
      --success:#10b981;
      --warning:#f59e0b;
      --danger:#ef4444;
      --purple:#8b5cf6;
      --indigo:#6366f1;
      
      /* Shadows */
      --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
      --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
      --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body { 
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
      background: var(--bg);
      color: var(--text);
      line-height: 1.6;
    }

    /* Layout */
    .organizer-layout { display: flex; min-height: 100vh; }
    
    .sidebar { 
      width: 250px; 
      background: var(--card); 
      border-right: 1px solid var(--border);
      padding: 1.5rem 0;
    }
    
    .main-content { 
      flex: 1; 
      padding: 2rem;
      overflow-x: auto;
    }

    /* Sidebar */
    .sidebar-header {
      padding: 0 1.5rem 1.5rem;
      border-bottom: 1px solid var(--border-light);
      margin-bottom: 1.5rem;
    }

    .sidebar-brand {
      font-size: 1.25rem;
      font-weight: 700;
      color: var(--primary);
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .sidebar-nav {
      list-style: none;
      padding: 0;
    }

    .nav-link {
      display: block;
      padding: 0.75rem 1.5rem;
      color: var(--text-light);
      text-decoration: none;
      transition: all 0.2s;
      border-right: 3px solid transparent;
    }

    .nav-link:hover,
    .nav-link.active {
      background: var(--border-light);
      color: var(--primary);
      border-right-color: var(--primary);
    }

    .nav-link svg {
      width: 18px;
      height: 18px;
      margin-right: 0.75rem;
      vertical-align: -0.25em;
    }

    /* Cards */
    .organizer-card {
      background: var(--card);
      border-radius: 8px;
      border: 1px solid var(--border);
      overflow: hidden;
      box-shadow: var(--shadow-sm);
      margin-bottom: 1.5rem;
    }

    .card-header {
      padding: 1.25rem;
      background: var(--border-light);
      border-bottom: 1px solid var(--border);
    }

    .card-title {
      font-size: 1.125rem;
      font-weight: 600;
      margin-bottom: 0.25rem;
    }

    .card-subtitle {
      font-size: 0.875rem;
      color: var(--text-light);
    }

    .card-body {
      padding: 1.25rem;
    }

    /* Page Header */
    .organizer-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 2rem;
    }

    .organizer-title {
      font-size: 1.875rem;
      font-weight: 700;
      margin-bottom: 0.25rem;
    }

    .organizer-subtitle {
      color: var(--text-light);
      font-size: 1rem;
    }

    /* Stats Grid */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    .stat-card {
      background: var(--card);
      padding: 1.25rem;
      border-radius: 8px;
      border: 1px solid var(--border);
      box-shadow: var(--shadow-sm);
      text-align: center;
    }

    .stat-number {
      font-size: 2rem;
      font-weight: 700;
      color: var(--primary);
      margin-bottom: 0.25rem;
    }

    .stat-label {
      color: var(--text-light);
      font-size: 0.875rem;
      font-weight: 500;
    }

    /* Table */
    .organizer-table {
      width: 100%;
      border-collapse: collapse;
      background: var(--card);
      border-radius: 8px;
      overflow: hidden;
      box-shadow: var(--shadow-sm);
    }

    .organizer-table th,
    .organizer-table td {
      padding: 0.875rem 1rem;
      text-align: left;
      border-bottom: 1px solid var(--border-light);
    }

    .organizer-table th {
      background: var(--border-light);
      font-weight: 600;
      font-size: 0.875rem;
      color: var(--text);
    }

    .organizer-table tbody tr:hover {
      background: var(--border-light);
    }

    /* Buttons */
    .btn {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.5rem 1rem;
      border: 1px solid var(--border);
      border-radius: 6px;
      background: var(--card);
      color: var(--text);
      text-decoration: none;
      font-size: 0.875rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.2s;
    }

    .btn:hover {
      background: var(--border-light);
    }

    .btn-primary {
      background: var(--primary);
      color: white;
      border-color: var(--primary);
    }

    .btn-primary:hover {
      background: var(--primary-dark);
      border-color: var(--primary-dark);
    }

    .btn-outline {
      border-color: var(--border);
      color: var(--text-light);
    }

    .btn-outline:hover {
      border-color: var(--primary);
      color: var(--primary);
    }

    /* Badges */
    .badge {
      display: inline-flex;
      align-items: center;
      gap: 0.25rem;
      padding: 0.25rem 0.75rem;
      font-size: 0.75rem;
      font-weight: 600;
      border-radius: 9999px;
      text-transform: uppercase;
      letter-spacing: 0.025em;
    }

    .badge-success {
      background: rgba(16, 185, 129, 0.1);
      color: #059669;
    }

    .badge-warning {
      background: rgba(245, 158, 11, 0.1);
      color: #d97706;
    }

    .badge-danger {
      background: rgba(239, 68, 68, 0.1);
      color: #dc2626;
    }

    .badge-info {
      background: rgba(99, 102, 241, 0.1);
      color: #4f46e5;
    }

    /* User Info */
    .user-info {
      padding: 1rem 1.5rem;
      margin-bottom: 1.5rem;
      background: var(--card);
      border-radius: 8px;
      border: 1px solid var(--border);
    }

    .user-name {
      font-weight: 600;
      margin-bottom: 0.25rem;
    }

    .user-role {
      font-size: 0.875rem;
      color: var(--text-light);
    }

    /* Responsive */
    @media (max-width: 768px) {
      .organizer-layout { flex-direction: column; }
      .sidebar { width: 100%; }
      .main-content { padding: 1rem; }
      .organizer-header { flex-direction: column; gap: 1rem; }
    }
  </style>
</head>

<body>
  <div class="organizer-layout">
    <!-- Sidebar -->
    <div class="sidebar">
      <div class="sidebar-header">
        <a href="{{ route('organizer.dashboard') }}" class="sidebar-brand">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M7 2v4M17 2v4M3 9h18M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5A2 2 0 0 0 3 7v12a2 2 0 0 0 2 2Z"/>
          </svg>
          EventEase
          <span style="font-size: 0.75rem; opacity: 0.7;">Organizer</span>
        </a>
      </div>

      <div class="user-info">
        <div class="user-name">{{ auth()->user()->name }}</div>
        <div class="user-role">{{ ucfirst(auth()->user()->role) }}</div>
      </div>

      <nav>
        <ul class="sidebar-nav">
          <li>
            <a href="{{ route('organizer.dashboard') }}" class="nav-link {{ request()->routeIs('organizer.dashboard') ? 'active' : '' }}">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v10z"/>
              </svg>
              Dashboard
            </a>
          </li>
          <li>
            <a href="{{ url('/') }}" class="nav-link">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
              </svg>
              Back to Site
            </a>
          </li>
          <li>
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
              @csrf
              <button type="submit" class="nav-link" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Logout
              </button>
            </form>
          </li>
        </ul>
      </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      @yield('content')
    </div>
  </div>

  @yield('scripts')
</body>
</html>
