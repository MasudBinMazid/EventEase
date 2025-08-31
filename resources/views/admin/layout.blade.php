<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title','Admin') • EventEase Admin</title>
  @stack('head')
  @yield('extra-css')
  <style>
    :root{
      --bg:#f6f7fb;            /* off-white page bg */
      --card:#ffffff;          /* surfaces */
      --text:#1f2937;          /* primary text */
      --muted:#6b7280;         /* secondary text */
      --brand:#4a6cf7;         /* brand */
      --brand-700:#3f5fe0;
      --border:#e5e7eb;        /* separators */
      --ring:#c7d2fe;          /* focus ring */
      --shadow: 0 10px 25px rgba(2,8,23,.08);
    }
    @media (prefers-color-scheme: dark){
      :root{
        --bg:#0b1220;
        --card:#0e172a;
        --text:#e5e7eb;
        --muted:#94a3b8;
        --brand:#6d8bff;
        --brand-700:#5f7cf0;
        --border:#1f2a44;
        --ring:#384bff;
        --shadow: 0 10px 25px rgba(0,0,0,.35);
      }
    }
    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0;
      font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      background:radial-gradient(1200px 800px at 20% -10%, rgba(74,108,247,.08), transparent 60%),
                 radial-gradient(1000px 700px at 120% 10%, rgba(109,139,255,.06), transparent 60%),
                 var(--bg);
      color:var(--text);
      -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale;
    }

    .wrap{max-width:1200px;margin:0 auto;padding:0 1rem}

    /* Header (glass + gradient) */
    .app-header{
      position:sticky; top:0; z-index:50;
      background:linear-gradient(135deg, rgba(74,108,247,.95), rgba(99,127,255,.92));
      backdrop-filter: saturate(140%) blur(8px);
      color:#fff; box-shadow:var(--shadow);
    }
    .header-inner{display:flex; align-items:center; justify-content:space-between; min-height:64px}
    .brand-link{display:flex; align-items:center; gap:.5rem; color:#fff; text-decoration:none}
    .brand-title{font-weight:800; letter-spacing:.2px; font-size:1.25rem}
    .brand-sub{font-weight:300; opacity:.95}

    /* Nav */
    .nav{display:flex; align-items:center; gap:.25rem}
    .nav-link{
      display:inline-flex; align-items:center; gap:.5rem;
      height:40px; padding:0 .9rem; border-radius:999px;
      color:#fff; text-decoration:none; font-weight:600; font-size:.95rem;
      opacity:.95; transition:opacity .2s ease, transform .06s ease, background .2s ease;
    }
    .nav-link:hover{opacity:1; background:rgba(255,255,255,.14)}
    .nav-link:active{transform:translateY(1px)}
    .nav-link.is-active{
      background:#fff; color:var(--brand);
      box-shadow:0 2px 10px rgba(0,0,0,.08);
    }

    /* Mobile toggle */
    .nav-toggle{
      display:none; background:transparent; border:1px solid rgba(255,255,255,.7);
      color:#fff; height:38px; padding:0 .75rem; border-radius:999px; cursor:pointer; font-weight:700;
    }
    .nav-toggle:focus{outline:3px solid var(--ring); outline-offset:2px}

    /* Logout button */
    .btn-logout{
      background:transparent; border:1px solid rgba(255,255,255,.9); color:#fff;
      height:38px; padding:0 .8rem; border-radius:999px; cursor:pointer; font-weight:700;
      transition:background .2s ease, border-color .2s ease;
    }
    .btn-logout:hover{background:rgba(255,255,255,.14)}
    .btn-logout:focus{outline:3px solid var(--ring); outline-offset:2px}

    /* Page */
    main.page{min-height:70vh; padding:0; animation:fadeIn .35s ease}
    @keyframes fadeIn{from{opacity:0; transform:translateY(6px)} to{opacity:1; transform:none}}

    /* Footer (centered) */
    .app-footer{
      background:var(--card);
      border-top:1px solid var(--border);
      box-shadow:0 -1px 0 rgba(255,255,255,.06) inset;
    }
    .footer-inner{
      text-align:center;           /* center content */
      padding:1.25rem 0;
      color:var(--muted);
      font-size:.95rem;
    }
    .footer-inner p{margin:.2rem 0}
    .footer-inner a{color:var(--brand); text-decoration:none; font-weight:600}
    .footer-inner a:hover{text-decoration:underline}

    /* Utilities */
    .hidden{display:none}

    /* Responsive */
    @media (max-width: 980px){
      .header-inner{flex-wrap:wrap; gap:.75rem; padding:.5rem 0}
      .nav-toggle{display:inline-block}
      .nav{display:none; width:100%; flex-wrap:wrap; gap:.5rem}
      .nav.open{display:flex}
      .nav-link{flex:1 1 auto; justify-content:center}
      .btn-logout{width:100%}
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
        <path d="M12 6l5 2.8v6.4L12 18l-5-2.8V8.8L12 6z" fill="#4a6cf7"/>
      </svg>
      <span class="brand-title">EventEase <span class="brand-sub">Admin</span></span>
    </a>

    <button class="nav-toggle" id="navToggle" aria-expanded="false" aria-controls="primaryNav">Menu</button>

<nav class="nav" id="primaryNav">
  <a href="{{ route('admin.users.index') }}"    class="nav-link {{ request()->routeIs('admin.users.*') ? 'is-active' : '' }}">Users</a>
  <a href="{{ route('admin.events.index') }}"   class="nav-link {{ request()->routeIs('admin.events.*') ? 'is-active' : '' }}">Events</a>
  <a href="{{ route('admin.requests.index') }}" class="nav-link {{ request()->routeIs('admin.requests.*') ? 'is-active' : '' }}">Event Requests</a>
  <a href="{{ route('admin.blogs.index') }}"    class="nav-link {{ request()->routeIs('admin.blogs.*') ? 'is-active' : '' }}">Blogs</a>
  <a href="{{ route('admin.messages.index') }}" class="nav-link {{ request()->routeIs('admin.messages.*') ? 'is-active' : '' }}">Messages</a>


  {{-- NEW: Payment Received --}}
  <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments.*') ? 'is-active' : '' }}">Payment Received</a>

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
      <a href="https://your-link-here.com" target="_blank" rel="noopener">Masud</a>
    </p>
  </div>
</footer>

@push('scripts')
<script>
  (function(){
    const btn = document.getElementById('navToggle');
    const nav = document.getElementById('primaryNav');
    if(!btn || !nav) return;
    btn.addEventListener('click', function(){
      const open = nav.classList.toggle('open');
      btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  })();
</script>
@endpush

@stack('scripts')
</body>
</html>
