<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title','Admin') • EventEase Admin</title>
  @stack('head')
  @yield('extra-css')
  <style>
    :root{
      /* Professional Admin Colors */
      --bg:#f9fafb;                  /* Gray 50 - Ultra light background */
      --card:#ffffff;                /* Pure white cards */
      --text:#111827;                /* Gray 900 - Primary text */
      --text-light:#6b7280;          /* Gray 500 - Secondary text */
      --text-muted:#9ca3af;          /* Gray 400 - Muted text */
      --border:#e5e7eb;              /* Gray 200 - Default border */
      --border-light:#f3f4f6;        /* Gray 100 - Light border */
      
      /* Professional Brand Colors */
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
      font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
      background: var(--bg);
      color: var(--text);
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      line-height: 1.5;
    }

    .wrap{max-width:1400px; margin:0 auto; padding:0 1rem;}

    /* Enhanced Header */
    .app-header{
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1000;
      background: rgba(255, 255, 255, 0.90);
      backdrop-filter: blur(20px) saturate(120%);
      border-bottom: 1px solid rgba(0, 0, 0, 0.08);
      transition: all 0.3s ease;
    }
    
    .app-header.scrolled {
      background: rgba(255, 255, 255, 0.95);
      box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
    }
    
    .header-inner{
      display: flex;
      align-items: center;
      justify-content: space-between;
      min-height: 70px;
      padding: 0.75rem 0;
    }
    
    .brand-link{
      display: flex;
      align-items: center;
      gap: 0.75rem;
      color: var(--text);
      text-decoration: none;
      transition: all 0.2s ease;
    }
    
    .brand-link:hover {
      transform: scale(1.02);
    }
    
    .brand-title{
      font-weight: 800;
      font-size: 1.5rem;
      letter-spacing: -0.025em;
      color: var(--primary);
    }
    
    .brand-sub{
      font-weight: 400;
      color: var(--text-light);
    }

    /* Enhanced Navigation */
    .nav{
      display: flex;
      align-items: center;
      gap: 0.5rem;
      background: var(--border-light);
      padding: 0.5rem;
      border-radius: 999px;
    }
    
    .nav-link{
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      height: 42px;
      padding: 0 1rem;
      border-radius: 999px;
      color: var(--text-light);
      text-decoration: none;
      font-weight: 500;
      font-size: 0.9rem;
      transition: all 0.2s ease;
      white-space: nowrap;
    }
    
    .nav-link:hover{
      color: var(--text);
      background: rgba(255, 255, 255, 0.8);
    }
    
    .nav-link.is-active{
      background: var(--primary);
      color: white;
      box-shadow: var(--shadow-md);
    }

    /* Mobile Navigation */
    .nav-toggle{
      display: none;
      background: var(--primary);
      border: none;
      color: white;
      height: 42px;
      padding: 0 1rem;
      border-radius: var(--radius);
      cursor: pointer;
      font-weight: 600;
      font-size: 0.9rem;
      transition: all 0.2s ease;
    }
    
    .nav-toggle:hover {
      background: var(--primary-dark);
    }

    /* Logout Button */
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
    
    .footer-inner{
      text-align: center;
      padding: 1.5rem 0;
      color: var(--text-light);
      font-size: 0.9rem;
    }
    
    .footer-inner p{margin: 0.25rem 0}
    .footer-inner a{
      color: var(--primary);
      text-decoration: none;
      font-weight: 600;
    }
    .footer-inner a:hover{text-decoration: underline}

    /* Modern Admin Page Styles */
    .admin-page {
      padding: 2rem;
      max-width: 1400px;
      margin: 0 auto;
    }
    
    .admin-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 2rem;
      flex-wrap: wrap;
      gap: 1rem;
    }
    
    .admin-title {
      font-size: 2rem;
      font-weight: 700;
      color: var(--text);
      margin: 0;
      letter-spacing: -0.025em;
    }
    
    .admin-subtitle {
      font-size: 1rem;
      color: var(--text-light);
      margin: 0.5rem 0 0;
    }
    
    .admin-actions {
      display: flex;
      gap: 0.75rem;
      align-items: center;
      flex-wrap: wrap;
    }
    
    .btn {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.75rem 1.5rem;
      border-radius: var(--radius);
      font-weight: 600;
      font-size: 0.9rem;
      text-decoration: none;
      border: none;
      cursor: pointer;
      transition: all 0.2s ease;
      white-space: nowrap;
    }
    
    .btn-primary {
      background: var(--primary);
      color: white;
    }
    
    .btn-primary:hover {
      background: var(--primary-dark);
      transform: translateY(-1px);
      box-shadow: var(--shadow-md);
    }
    
    .btn-success {
      background: var(--success);
      color: white;
    }
    
    .btn-success:hover {
      background: #059669;
      transform: translateY(-1px);
    }
    
    .btn-warning {
      background: var(--warning);
      color: white;
    }
    
    .btn-danger {
      background: var(--danger);
      color: white;
    }
    
    .btn-outline {
      background: transparent;
      border: 2px solid var(--border);
      color: var(--text);
    }
    
    .btn-outline:hover {
      border-color: var(--primary);
      color: var(--primary);
    }
    
    /* Cards */
    .admin-card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      box-shadow: var(--shadow-sm);
      transition: all 0.2s ease;
    }
    
    .admin-card:hover {
      box-shadow: var(--shadow-md);
    }
    
    .card-header {
      padding: 1.5rem;
      border-bottom: 1px solid var(--border);
    }
    
    .card-body {
      padding: 1.5rem;
    }
    
    .card-title {
      font-size: 1.25rem;
      font-weight: 600;
      color: var(--text);
      margin: 0;
    }
    
    /* Tables */
    .admin-table {
      width: 100%;
      border-collapse: collapse;
      background: var(--card);
      border-radius: var(--radius-lg);
      overflow: hidden;
      box-shadow: var(--shadow-sm);
    }
    
    .admin-table th {
      background: var(--border-light);
      padding: 1rem;
      text-align: left;
      font-weight: 600;
      font-size: 0.9rem;
      color: var(--text);
      border-bottom: 1px solid var(--border);
    }
    
    .admin-table td {
      padding: 1rem;
      border-bottom: 1px solid var(--border);
      color: var(--text-light);
    }
    
    .admin-table tr:hover {
      background: var(--border-light);
    }
    
    /* Forms */
    .form-group {
      margin-bottom: 1.5rem;
    }
    
    .form-label {
      display: block;
      font-weight: 600;
      color: var(--text);
      margin-bottom: 0.5rem;
      font-size: 0.9rem;
    }
    
    .form-input {
      width: 100%;
      padding: 0.75rem;
      border: 2px solid var(--border);
      border-radius: var(--radius);
      font-size: 0.95rem;
      transition: all 0.2s ease;
      background: var(--card);
    }
    
    .form-input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .form-textarea {
      resize: vertical;
      min-height: 120px;
    }
    
    /* Status Badges */
    .badge {
      display: inline-flex;
      align-items: center;
      padding: 0.25rem 0.75rem;
      border-radius: 999px;
      font-size: 0.8rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.025em;
    }
    
    .badge-success {
      background: #dcfce7;
      color: #166534;
    }
    
    .badge-warning {
      background: #fef3c7;
      color: #92400e;
    }
    
    .badge-danger {
      background: #fee2e2;
      color: #991b1b;
    }
    
    .badge-info {
      background: #dbeafe;
      color: #1e40af;
    }
    
    /* Stats Cards */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2rem;
    }
    
    .stat-card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      padding: 1.5rem;
      box-shadow: var(--shadow-sm);
      transition: all 0.2s ease;
    }
    
    .stat-card:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-md);
    }
    
    .stat-number {
      font-size: 2.5rem;
      font-weight: 700;
      color: var(--text);
      margin-bottom: 0.5rem;
      line-height: 1;
    }
    
    .stat-label {
      font-size: 0.9rem;
      color: var(--text-light);
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
      .admin-page {
        padding: 1.5rem;
      }
      
      .admin-header {
        flex-direction: column;
        align-items: flex-start;
      }
      
      .admin-actions {
        width: 100%;
        justify-content: flex-start;
      }
    }
    
    @media (max-width: 768px) {
      .wrap {
        padding: 0 0.75rem;
      }
      
      .header-inner {
        flex-wrap: wrap;
        gap: 1rem;
        padding: 0.5rem 0;
      }
      
      .nav-toggle {
        display: inline-block;
      }
      
      .nav {
        display: none;
        width: 100%;
        flex-direction: column;
        background: var(--card);
        border-radius: var(--radius-lg);
        padding: 1rem;
        box-shadow: var(--shadow-lg);
        margin-top: 1rem;
        gap: 0.5rem;
      }
      
      .nav.open {
        display: flex;
      }
      
      .nav-link {
        width: 100%;
        justify-content: flex-start;
        height: 48px;
        padding: 0 1rem;
      }
      
      .btn-logout {
        width: 100%;
        margin: 0.5rem 0 0;
        justify-content: center;
        display: flex;
      }
      
      .admin-page {
        padding: 1rem;
      }
      
      .admin-title {
        font-size: 1.75rem;
      }
      
      .stats-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
      }
      
      .admin-table {
        font-size: 0.9rem;
      }
      
      .admin-table th,
      .admin-table td {
        padding: 0.75rem 0.5rem;
      }
    }
    
    @media (max-width: 480px) {
      .admin-page {
        padding: 0.75rem;
      }
      
      .admin-title {
        font-size: 1.5rem;
      }
      
      .btn {
        padding: 0.625rem 1rem;
        font-size: 0.85rem;
      }
      
      .admin-table {
        font-size: 0.8rem;
      }
      
      .admin-table th,
      .admin-table td {
        padding: 0.5rem 0.25rem;
      }
    }

    /* Animations */
    .fade-in {
      animation: fadeIn 0.4s ease;
    }
    
    .slide-in {
      animation: slideIn 0.4s ease;
    }
    
    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateX(-20px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }
    
    /* Loading States */
    .loading {
      opacity: 0.6;
      pointer-events: none;
    }
    
    .skeleton {
      background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
      background-size: 200% 100%;
      animation: loading 1.5s infinite;
    }
    
    @keyframes loading {
      0% {
        background-position: 200% 0;
      }
      100% {
        background-position: -200% 0;
      }
    }
  </style>
</head>
<body>
<header class="app-header">
  <div class="wrap header-inner">
    <a href="{{ route('admin.index') }}" class="brand-link">
      <!-- Tiny logo shape -->
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <path d="M12 3l8 4.5v9L12 21l-8-4.5v-9L12 3z" fill="white" opacity=".92"/>
        <path d="M12 6l5 2.8v6.4L12 18l-5-2.8V8.8L12 6z" fill="{{ auth()->user()->role === 'manager' ? '#8b5cf6' : '#4a6cf7' }}"/>
      </svg>
      <span class="brand-title">EventEase 
        <span class="brand-sub">
          {{ auth()->user()->role === 'manager' ? 'Manager' : 'Admin' }}
          @if(auth()->user()->role === 'manager')
            <small style="font-size: 0.7em; color: var(--purple); font-weight: 600;">(Limited Access)</small>
          @endif
        </span>
      </span>
    </a>

    <button class="nav-toggle" id="navToggle" aria-expanded="false" aria-controls="primaryNav">Menu</button>

<nav class="nav" id="primaryNav">
  <a href="{{ route('admin.users.index') }}"    class="nav-link {{ request()->routeIs('admin.users.*') ? 'is-active' : '' }}">Users</a>
  <a href="{{ route('admin.events.index') }}"   class="nav-link {{ request()->routeIs('admin.events.*') ? 'is-active' : '' }}">Events</a>
  <a href="{{ route('admin.requests.index') }}" class="nav-link {{ request()->routeIs('admin.requests.*') ? 'is-active' : '' }}">Event Requests</a>
  <a href="{{ route('admin.blogs.index') }}"    class="nav-link {{ request()->routeIs('admin.blogs.*') ? 'is-active' : '' }}">Blogs</a>
  <a href="{{ route('admin.messages.index') }}" class="nav-link {{ request()->routeIs('admin.messages.*') ? 'is-active' : '' }}">Messages</a>

  {{-- Feature Banner Management --}}
  <a href="{{ route('admin.banners.index') }}" class="nav-link {{ request()->routeIs('admin.banners.*') ? 'is-active' : '' }}">🎯 Feature Banners</a>

  {{-- Notice Management --}}
  <a href="{{ route('admin.notices.index') }}" class="nav-link {{ request()->routeIs('admin.notices.*') ? 'is-active' : '' }}">📢 Notices</a>

  {{-- Maintenance Mode (Admin Only) --}}
  @if(Auth::user()->isAdmin())
  <a href="{{ route('admin.maintenance.index') }}" class="nav-link {{ request()->routeIs('admin.maintenance.*') ? 'is-active' : '' }}">⚙️ Maintenance</a>
  @endif

  

  <a href="{{ route('admin.sales.index') }}"    class="nav-link {{ request()->routeIs('admin.sales.index') ? 'is-active' : '' }}">Sales</a>
  <a href="{{ route('admin.sales.events') }}"   class="nav-link {{ request()->routeIs('admin.sales.events') ? 'is-active' : '' }}">Sales by Event</a>

  <form method="POST" action="{{ route('logout') }}" style="display:inline;"> @csrf
    <button class="btn-logout">Logout</button>
  </form>
</nav>
  </div>
</header>

<main class="page">
  @yield('content')
</main>

<footer class="app-footer">
  <div class="wrap footer-inner">
    <p>© {{ date('Y') }} EventEase Admin</p>
    <p>Developed by
      <a href="https://bymasud.online" target="_blank" rel="noopener">Masud</a>
    </p>
  </div>
</footer>

@push('scripts')
<script>
  // Mobile navigation toggle
  (function(){
    const btn = document.getElementById('navToggle');
    const nav = document.getElementById('primaryNav');
    if(!btn || !nav) return;
    btn.addEventListener('click', function(){
      const open = nav.classList.toggle('open');
      btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  })();

  // Sticky header scroll effect
  (function(){
    const header = document.querySelector('.app-header');
    let lastScrollY = window.scrollY;
    
    function updateHeader() {
      const currentScrollY = window.scrollY;
      
      if (currentScrollY > 50) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
      
      lastScrollY = currentScrollY;
    }
    
    window.addEventListener('scroll', updateHeader, { passive: true });
    updateHeader(); // Initial call
  })();

  // Close mobile menu when clicking outside
  document.addEventListener('click', function(event) {
    const nav = document.getElementById('primaryNav');
    const toggle = document.getElementById('navToggle');
    
    if (nav && toggle && nav.classList.contains('open')) {
      if (!nav.contains(event.target) && !toggle.contains(event.target)) {
        nav.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
      }
    }
  });

  // Enhanced table interactions
  document.addEventListener('DOMContentLoaded', function() {
    // Add loading states to buttons
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(btn => {
      btn.addEventListener('click', function() {
        if (this.type === 'submit' || this.href) {
          this.classList.add('loading');
          setTimeout(() => {
            this.classList.remove('loading');
          }, 2000);
        }
      });
    });

    // Auto-resize textareas
    const textareas = document.querySelectorAll('.form-textarea');
    textareas.forEach(textarea => {
      textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
      });
    });

    // Enhanced form validation feedback
    const inputs = document.querySelectorAll('.form-input');
    inputs.forEach(input => {
      input.addEventListener('blur', function() {
        if (this.checkValidity()) {
          this.style.borderColor = 'var(--success)';
        } else if (this.value) {
          this.style.borderColor = 'var(--danger)';
        }
      });
      
      input.addEventListener('focus', function() {
        this.style.borderColor = 'var(--primary)';
      });
    });
  });
</script>
@endpush

@stack('scripts')
</body>
</html>
