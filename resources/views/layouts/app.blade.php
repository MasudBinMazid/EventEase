<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
<title>EventEase - @yield('title')</title>


  {{-- Global site CSS (yours) --}}
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/modal.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">

  {{-- Page-level CSS (per-view) --}}
  @yield('extra-css')

  {{-- ✅ Tailwind CDN so utility classes work without Vite --}}
  <script src="https://cdn.tailwindcss.com"></script>
  {{-- Fallback if the CDN above is blocked --}}
  <script>
    if (!window.tailwind) {
      var s = document.createElement('script');
      s.src = 'https://unpkg.com/tailwindcss-cdn@3.4.0/tailwindcss.js';
      document.head.appendChild(s);
    }
  </script>

  {{-- Minimal base in case both CDNs are blocked --}}
  <style>
    body{background:#f8f9fc;color:#111827;margin:0}
    /* Fixed header with dark indigo bg */
    header.site-header{
      position:fixed;
      top:0;left:0;right:0;
      z-index:50;
      background:#3f3f7a; /* updated color */
      color:#fff;         /* text white */
      border-bottom:1px solid #2c2c54;
    }
    header.site-header a{color:#fff;text-decoration:none}
    header.site-header a:hover{color:#e5e7eb}
    .header-spacer{height:64px} /* matches ~h-16 */
  </style>
</head>
<body class="bg-gray-50 text-gray-900">

  <header class="site-header">
    <a href="{{ url('/') }}" class="logo"><span>EventEase</span></a>

    <!-- Desktop Navigation -->
    <nav class="desktop-nav">
      <a href="{{ url('/') }}">Home</a>
      <a href="{{ url('/events') }}">Events</a>
      <a href="{{ url('/gallery') }}">Gallery</a>
      <a href="{{ url('/blog') }}">Blog</a>
      <a href="{{ url('/contact') }}">Contact</a>
    </nav>

    <!-- Desktop Login Section -->
    <div class="desktop-login-section">
      @guest
        <span>👲🏻 Guest</span>
        <button onclick="openAuthModal()">Login</button>
      @endguest

      @auth
        <div class="dropdown">
          <button class="dropdown-toggle">{{ Auth::user()->name }}</button>
          <div class="dropdown-menu">
            <a href="{{ route('dashboard') }}" style="color: black;">Dashboard</a>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" style="color: black; background: none; border: none; cursor: pointer;">
                Logout
              </button>
            </form>
          </div>
        </div>
      @endauth
    </div>

    <!-- Mobile Hamburger Button -->
    <div class="mobile-hamburger" onclick="toggleSidebar()">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </header>

  <!-- Mobile Sidebar Overlay -->
  <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

  <!-- Mobile Sidebar -->
  <div class="mobile-sidebar" id="mobileSidebar">
    <div class="sidebar-header">
      <div class="sidebar-logo">
        <span>📅 EventEase</span>
      </div>
      <button class="sidebar-close" onclick="closeSidebar()">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </button>
    </div>

    <div class="sidebar-content">
      <!-- User Profile Section -->
      <div class="sidebar-profile">
        @guest
          <div class="guest-profile">
            <div class="guest-avatar">👲🏻</div>
            <div class="guest-info">
              <h3>Welcome, Guest!</h3>
              <p>Please login to access all features</p>
            </div>
          </div>
          <button class="sidebar-login-btn" onclick="openAuthModal(); closeSidebar();">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M15 3H19C19.5304 3 20.0391 3.21071 20.4142 3.58579C20.7893 3.96086 21 4.46957 21 5V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H15M10 17L15 12L10 7M15 12H3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Login
          </button>
        @endguest

        @auth
          <div class="user-profile">
            <div class="user-avatar">
              @if(Auth::user()->profile_picture)
                <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="{{ Auth::user()->name }}">
              @else
                <div class="avatar-placeholder">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
              @endif
            </div>
            <div class="user-info">
              <h3>{{ Auth::user()->name }}</h3>
              <p>{{ Auth::user()->email }}</p>
            </div>
          </div>
        @endauth
      </div>

      <!-- Navigation Links -->
      <nav class="sidebar-nav">
        <a href="{{ url('/') }}" class="sidebar-link">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M3 9L12 2L21 9V20C21 20.5304 20.7893 21.0391 20.4142 21.4142C20.0391 21.7893 19.5304 22 19 22H5C4.46957 22 3.96086 21.7893 3.58579 21.4142C3.21071 21.0391 3 20.5304 3 20V9Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M9 22V12H15V22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <span>Home</span>
        </a>

        <a href="{{ url('/events') }}" class="sidebar-link">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke="currentColor" stroke-width="2" fill="none"/>
            <line x1="16" y1="2" x2="16" y2="6" stroke="currentColor" stroke-width="2"/>
            <line x1="8" y1="2" x2="8" y2="6" stroke="currentColor" stroke-width="2"/>
            <line x1="3" y1="10" x2="21" y2="10" stroke="currentColor" stroke-width="2"/>
          </svg>
          <span>Events</span>
        </a>

        <a href="{{ url('/gallery') }}" class="sidebar-link">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2" stroke="currentColor" stroke-width="2" fill="none"/>
            <circle cx="8.5" cy="8.5" r="1.5" stroke="currentColor" stroke-width="2" fill="none"/>
            <path d="M21 15L16 10L5 21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <span>Gallery</span>
        </a>

        <a href="{{ url('/blog') }}" class="sidebar-link">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M14 2V8H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <line x1="16" y1="13" x2="8" y2="13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <line x1="16" y1="17" x2="8" y2="17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <line x1="10" y1="9" x2="8" y2="9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <span>Blog</span>
        </a>

        <a href="{{ url('/contact') }}" class="sidebar-link">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4 4H20C21.1 4 22 4.9 22 6V18C22 19.1 21.1 20 20 20H4C2.9 20 2 19.1 2 18V6C2 4.9 2.9 4 4 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M22 6L12 13L2 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <span>Contact</span>
        </a>

        @auth
          <div class="sidebar-divider"></div>
          
          <a href="{{ route('dashboard') }}" class="sidebar-link">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect x="3" y="3" width="18" height="18" rx="2" ry="2" stroke="currentColor" stroke-width="2" fill="none"/>
              <path d="M9 9H15V15H9V9Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>Dashboard</span>
          </a>

          <form method="POST" action="{{ route('logout') }}" class="sidebar-logout-form">
            @csrf
            <button type="submit" class="sidebar-link logout-link">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H9M16 17L21 12L16 7M21 12H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span>Logout</span>
            </button>
          </form>
        @endauth
      </nav>
    </div>
  </div>

  <!-- Spacer so content isn't hidden behind the fixed navbar -->
  <div class="header-spacer h-16"></div>

  <main>
    @yield('content')
    @include('components.auth-modal')
  </main>

  <footer class="site-footer">
    <div class="footer-container">
      <div class="footer-column brand">
        <h3>📅 EventEase</h3>
        <p>Your trusted partner for event discovery and ticket booking. Join our community and never miss out again!</p>
      </div>

      <div class="footer-column">
        <h4>Quick Links</h4>
        <ul>
          <li><a href="{{ url('/') }}">Home</a></li>
          <li><a href="{{ url('/events') }}">Events</a></li>
          <li><a href="{{ url('/gallery') }}">Gallery</a></li>
          <li><a href="{{ url('/blog') }}">Blog</a></li>
          <li><a href="{{ url('/contact') }}">Contact</a></li>
          <li><a href="#" id="openTermsModal">Terms & Conditions</a></li>
        </ul>
      </div>

      <div class="footer-column">
        <h4>Follow Us</h4>
        <div class="social-icons">
          <a href="#"><img src="{{ asset('assets/icons/facebook.svg') }}" alt="Facebook"></a>
          <a href="#"><img src="{{ asset('assets/icons/twitter.svg') }}" alt="Twitter"></a>
          <a href="#"><img src="{{ asset('assets/icons/instagram.svg') }}" alt="Instagram"></a>
          <a href="#"><img src="{{ asset('assets/icons/youtube.svg') }}" alt="YouTube"></a>
        </div>
      </div>

      <div class="footer-column">
        <h4>Subscribe</h4>
        <p>Get event updates directly to your inbox.</p>
        <form class="subscribe-form">
          <input type="email" placeholder="Enter your email" />
          <button type="submit">Subscribe</button>
        </form>
      </div>
    </div>

    <div class="footer-bottom">
      <p>&copy; {{ date('Y') }} EventEase. All rights reserved.</p>
    </div>
  </footer>

  {{-- Terms Modal --}}
  <div id="termsModal" class="terms-modal-overlay">
    <div class="terms-modal-content">
      <span class="close-modal" id="closeTermsModal">&times;</span>
      <h2>Terms & Conditions</h2>
      <div class="terms-text">
        <p>Welcome to EventEase. By accessing our platform, you agree to the following terms:</p>

        <h4>1. Use of Service</h4>
        <p>You may use the service for personal, non-commercial purposes only.</p>

        <h4>2. Ticketing Policy</h4>
        <p>Tickets are non-refundable unless the event is cancelled. Always check event details before booking.</p>

        <h4>3. User Responsibilities</h4>
        <p>Keep your account details secure and do not share your password with others.</p>

        <h4>4. Content Ownership</h4>
        <p>All content on this site is owned or licensed by EventEase. Reproduction without permission is prohibited.</p>

        <h4>5. Changes to Terms</h4>
        <p>We reserve the right to update our terms. Please review this section regularly for updates.</p>
      </div>
    </div>
  </div>

  {{-- Global JS --}}
  <script src="{{ asset('assets/js/script.js') }}"></script>
  <script src="{{ asset('assets/js/auth.js') }}"></script>
  <script src="{{ asset('assets/js/modal.js') }}"></script>

  {{-- Page-level JS --}}
  @yield('extra-js')

  <script>
    @if ($errors->any())
      window.onload = function () {
        openAuthModal();
        @if (old('name'))
          switchAuthTab('register');
        @else
          switchAuthTab('login');
        @endif
      }
    @endif
  </script>
</body>
</html>
