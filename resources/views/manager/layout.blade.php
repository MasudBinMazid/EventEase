<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title','Manager') • EventEase Admin</title>
  @stack('head')
  @yield('extra-css')
  <style>
    :root{
      /* Professional Admin Colors - Same as Admin Panel */
      --bg:#f9fafb;                  /* Gray 50 - Ultra light background */
      --card:#ffffff;                /* Pure white cards */
      --text:#111827;                /* Gray 900 - Primary text */
      --text-light:#6b7280;          /* Gray 500 - Secondary text */
      --text-muted:#9ca3af;          /* Gray 400 - Muted text */
      --border:#e5e7eb;              /* Gray 200 - Default border */
      --border-light:#f3f4f6;        /* Gray 100 - Light border */
      
      /* Professional Brand Colors - Same as Admin */
      --primary:#0891b2;             /* Cyan 600 - Professional primary */
      --primary-dark:#0e7490;        /* Cyan 700 - Darker primary */
      --success:#059669;             /* Emerald 600 - Success */
      --warning:#d97706;             /* Amber 600 - Warning */
      --danger:#dc2626;              /* Red 600 - Danger */
      --info:#0891b2;                /* Cyan 600 - Info */
      --purple:#8b5cf6;              /* Purple 500 - Purple accent */
      --indigo:#6366f1;              /* Indigo 500 - Indigo accent */
      --teal:#0d9488;                /* Teal 600 - Teal accent */
      
      /* Professional Shadows & Effects */
      --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
      --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
      --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
      --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
      --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
      
      /* Professional Radius */
      --radius: 0.5rem;              /* 8px - More subtle radius */
      --radius-lg: 0.75rem;          /* 12px - Larger radius */
      
      /* Interactive States */
      --hover-bg:#f3f4f6;            /* Gray 100 - Hover background */
      --focus-ring: 0 0 0 3px rgba(8, 145, 178, 0.1); /* Professional focus ring */
    }
    
    *{box-sizing:border-box}
    html,body{height:100%; margin:0; padding:0;}
    
    body{
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: var(--bg);
      color: var(--text);
      line-height: 1.6;
      font-size: 0.95rem;
      min-height: 100vh;
    }

    /* Header */
    .app-header{
      background: var(--card);
      border-bottom: 1px solid var(--border);
      padding: 0 2rem;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      height: 70px;
      z-index: 1000;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: var(--shadow-sm);
    }

    .app-logo{
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--primary);
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .app-logo .role-badge{
      background: var(--primary);
      color: white;
      padding: 0.25rem 0.5rem;
      border-radius: 999px;
      font-size: 0.75rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .nav-menu{
      display: flex;
      align-items: center;
      gap: 2rem;
      list-style: none;
      margin: 0;
      padding: 0;
    }

    .nav-link{
      color: var(--text-light);
      text-decoration: none;
      font-weight: 500;
      padding: 0.5rem 1rem;
      border-radius: var(--radius);
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      white-space: nowrap;
    }

    .nav-link:hover,
    .nav-link.active{
      color: var(--primary);
      background: rgba(8, 145, 178, 0.1);
    }

    .user-menu{
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .btn-logout{
      background: var(--danger);
      border: none;
      color: white;
      height: 42px;
      padding: 0 1rem;
      border-radius: 999px;
      cursor: pointer;
      font-weight: 600;
      font-size: 0.9rem;
      transition: all 0.2s ease;
      margin-left: 0.5rem;
    }
    
    .btn-logout:hover{
      background: #dc2626;
      transform: translateY(-1px);
    }

    /* Main Content */
    main.page{
      min-height: calc(100vh - 140px);
      padding-top: 90px;
      animation: fadeIn 0.4s ease;
    }
    
    @keyframes fadeIn{
      from{opacity:0; transform:translateY(10px)}
      to{opacity:1; transform:none}
    }

    /* Footer */
    .app-footer{
      background: var(--card);
      border-top: 1px solid var(--border);
      margin-top: 2rem;
    }

    /* Admin Page Styles - Same as Admin Panel */
    .admin-page{
      max-width: 1400px;
      margin: 0 auto;
      padding: 2rem;
    }

    .admin-header{
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 2rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid var(--border-light);
    }

    .admin-title{
      font-size: 2rem;
      font-weight: 700;
      color: var(--text);
      margin: 0 0 0.5rem 0;
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .admin-subtitle{
      color: var(--text-light);
      margin: 0;
      font-size: 1rem;
    }

    .admin-actions{
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    /* Cards */
    .admin-card{
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      overflow: hidden;
      box-shadow: var(--shadow-sm);
      transition: all 0.3s ease;
    }

    .admin-card:hover{
      box-shadow: var(--shadow-md);
      transform: translateY(-1px);
    }

    .card-header{
      padding: 1.5rem 2rem;
      border-bottom: 1px solid var(--border-light);
      background: rgba(8, 145, 178, 0.02);
    }

    .card-title{
      font-size: 1.25rem;
      font-weight: 600;
      color: var(--text);
      margin: 0;
    }

    .card-body{
      padding: 2rem;
    }

    /* Tables */
    .manager-table{
      width: 100%;
      border-collapse: collapse;
      font-size: 0.9rem;
    }

    .manager-table th,
    .manager-table td{
      padding: 1rem;
      text-align: left;
      border-bottom: 1px solid var(--border-light);
    }

    .manager-table th{
      background: var(--border-light);
      font-weight: 600;
      color: var(--text);
      font-size: 0.85rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .manager-table tbody tr:hover{
      background: rgba(8, 145, 178, 0.03);
    }

    /* Buttons */
    .btn{
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.75rem 1.5rem;
      border: 1px solid transparent;
      border-radius: var(--radius);
      font-weight: 500;
      font-size: 0.9rem;
      text-decoration: none;
      cursor: pointer;
      transition: all 0.2s ease;
      white-space: nowrap;
    }

    .btn-primary{
      background: var(--primary);
      color: white;
      border-color: var(--primary);
    }

    .btn-primary:hover{
      background: var(--primary-dark);
      border-color: var(--primary-dark);
      transform: translateY(-1px);
      box-shadow: var(--shadow-md);
    }

    .btn-outline{
      background: transparent;
      color: var(--text-light);
      border-color: var(--border);
    }

    .btn-outline:hover{
      background: var(--hover-bg);
      color: var(--text);
    }

    .btn-danger{
      background: var(--danger);
      color: white;
      border-color: var(--danger);
    }

    .btn-danger:hover{
      background: #dc2626;
      transform: translateY(-1px);
    }

    /* Badges */
    .badge{
      display: inline-flex;
      align-items: center;
      gap: 0.25rem;
      padding: 0.25rem 0.75rem;
      border-radius: 999px;
      font-size: 0.75rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .badge-success{
      background: rgba(5, 150, 105, 0.1);
      color: var(--success);
    }

    .badge-warning{
      background: rgba(217, 119, 6, 0.1);
      color: var(--warning);
    }

    .badge-info{
      background: rgba(8, 145, 178, 0.1);
      color: var(--info);
    }

    .badge-secondary{
      background: var(--border-light);
      color: var(--text-light);
    }

    /* Forms */
    .form-group{
      margin-bottom: 1.5rem;
    }

    .form-label{
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 600;
      color: var(--text);
    }

    .form-input,
    .form-select,
    .form-textarea{
      width: 100%;
      padding: 0.75rem 1rem;
      border: 1px solid var(--border);
      border-radius: var(--radius);
      font-size: 0.9rem;
      transition: all 0.2s ease;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus{
      outline: none;
      border-color: var(--primary);
      box-shadow: var(--focus-ring);
    }

    /* Stats Grid */
    .stats-grid{
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    .stat-card{
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      padding: 1.5rem;
      text-align: center;
      box-shadow: var(--shadow-sm);
      transition: all 0.3s ease;
    }

    .stat-card:hover{
      transform: translateY(-2px);
      box-shadow: var(--shadow-md);
    }

    .stat-number{
      font-size: 2rem;
      font-weight: 700;
      color: var(--primary);
      margin-bottom: 0.5rem;
    }

    .stat-label{
      color: var(--text-light);
      font-size: 0.9rem;
      font-weight: 500;
    }

    /* Alerts */
    .alert{
      padding: 1rem 1.5rem;
      border-radius: var(--radius);
      margin-bottom: 1.5rem;
      font-weight: 500;
    }

    .alert-success{
      background: rgba(5, 150, 105, 0.1);
      color: var(--success);
      border: 1px solid rgba(5, 150, 105, 0.2);
    }

    .alert-error{
      background: rgba(220, 38, 38, 0.1);
      color: var(--danger);
      border: 1px solid rgba(220, 38, 38, 0.2);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .app-header{
        padding: 0 1rem;
      }

      .nav-menu{
        gap: 1rem;
      }

      .nav-link{
        padding: 0.5rem;
        font-size: 0.8rem;
      }

      .manager-page{
        padding: 1rem;
      }

      .manager-header{
        flex-direction: column;
        gap: 1rem;
      }

      .stats-grid{
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
      }

      .manager-table{
        font-size: 0.8rem;
      }

      .manager-table th,
      .manager-table td{
        padding: 0.75rem 0.5rem;
      }
    }
  </style>
</head>

<body>
  <!-- Header -->
  <header class="app-header">
    <a href="{{ route('manager.index') }}" class="app-logo">
      <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
        <path d="M12 2L2 7L12 12L22 7L12 2Z"/>
        <path d="M2 17L12 22L22 17"/>
        <path d="M2 12L12 17L22 12"/>
      </svg>
      EventEase
      <span class="role-badge">Manager</span>
    </a>

    <nav>
      <ul class="nav-menu">
        <li><a href="{{ route('manager.index') }}" class="nav-link {{ request()->routeIs('manager.index') ? 'active' : '' }}">Dashboard</a></li>
        <li><a href="{{ route('manager.users.index') }}" class="nav-link {{ request()->routeIs('manager.users.*') ? 'active' : '' }}">Users</a></li>
        <li><a href="{{ route('admin.events.index') }}" class="nav-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">Events</a></li>
        <li><a href="{{ route('admin.blogs.index') }}" class="nav-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}">Blogs</a></li>
        <li><a href="{{ route('admin.sales.index') }}" class="nav-link {{ request()->routeIs('admin.sales.*') ? 'active' : '' }}">Sales</a></li>
        <li><a href="{{ route('admin.messages.index') }}" class="nav-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">Messages</a></li>
      </ul>
    </nav>

    <div class="user-menu">
      <span style="color: var(--text-light); font-size: 0.9rem;">{{ auth()->user()->name }}</span>
      <form method="POST" action="{{ route('logout') }}" style="display: inline;">
        @csrf
        <button type="submit" class="btn-logout">Logout</button>
      </form>
    </div>
  </header>

  <!-- Main Content -->
  <main class="page">
    @yield('content')
  </main>

  <!-- Footer -->
  <footer class="app-footer">
    <div style="padding: 2rem; text-align: center;">
      <p style="margin: 0; color: var(--text-light); font-size: 0.9rem;">
        © {{ date('Y') }} EventEase Manager Panel. All rights reserved.
      </p>
    </div>
  </footer>

  @stack('scripts')
  @yield('extra-js')
</body>
</html>
