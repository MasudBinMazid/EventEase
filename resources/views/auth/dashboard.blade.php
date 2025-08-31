@extends('layouts.app')
@section('title', 'Dashboard')

@section('extra-css')
  <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
  <!-- Inline design tokens + components (scoped) -->
  <style>
:root {
  /* Professional Modern Color Palette */
  --bg: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
  --card: rgba(255, 255, 255, 0.85);
  --surface: rgba(255, 255, 255, 0.7);
  
  /* Subtle borders and elegant shadows */
  --border: rgba(0, 0, 0, 0.08);
  --shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
  --shadow-hover: 0 15px 35px rgba(0, 0, 0, 0.12);
  --radius: 16px;
  --glass: blur(20px) saturate(120%);
  --glass-strong: blur(25px) saturate(140%);

  /* Clean Typography Colors */
  --text: #2d3748;
  --text-secondary: #4a5568;
  --muted: #718096;

  /* Harmonious Accent Colors */
  --accent: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
  --accent-2: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
  --accent-3: linear-gradient(135deg, #10b981 0%, #059669 100%);
  --success: linear-gradient(135deg, #10b981 0%, #34d399 100%);
  --warning: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
  --danger: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
  
  /* Additional Soft Colors */
  --primary: #6366f1;
  --primary-light: #a5b4fc;
  --secondary: #64748b;
  --accent-soft: #e0e7ff;
}

    .dash-shell{
      --gap: 1.5rem;
      padding: clamp(20px, 3vw, 40px);
      background: var(--bg);
      min-height: 100vh;
      color: var(--text);
      position: relative;
    }
    
    .dash-shell::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: 
        radial-gradient(circle at 10% 20%, rgba(99, 102, 241, 0.1), transparent 50%),
        radial-gradient(circle at 90% 80%, rgba(16, 185, 129, 0.08), transparent 50%),
        radial-gradient(circle at 50% 50%, rgba(59, 130, 246, 0.06), transparent 50%);
      pointer-events: none;
      z-index: 0;
    }
    
    .dash-shell > * {
      position: relative;
      z-index: 1;
    }
    .dash-topbar{
      display: flex; 
      align-items: center; 
      justify-content: space-between;
      gap: 1.5rem; 
      margin-bottom: var(--gap);
      padding: 20px 24px;
      border: 1px solid var(--border);
      border-radius: var(--radius);
      background: var(--card);
      backdrop-filter: var(--glass);
      box-shadow: var(--shadow);
      transition: all 0.3s ease;
    }
    
    .dash-topbar:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-hover);
    }
    .dash-welcome{
      font-size: clamp(1.4rem, 3vw, 2rem);
      font-weight: 800;
      letter-spacing: -0.5px;
      text-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .brand-accent{
      background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #06b6d4 100%);
      -webkit-background-clip: text; 
      background-clip: text; 
      color: transparent;
      animation: shimmer 4s ease-in-out infinite alternate;
      font-weight: 900;
    }
    
    @keyframes shimmer {
      0% { filter: hue-rotate(0deg) brightness(1); }
      50% { filter: hue-rotate(15deg) brightness(1.1); }
      100% { filter: hue-rotate(0deg) brightness(1); }
    }
    .btn{
      display: inline-flex; 
      align-items: center; 
      justify-content: center; 
      gap: .6rem;
      padding: 12px 20px; 
      border-radius: 12px; 
      font-weight: 600; 
      text-decoration: none;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      border: 1px solid var(--border);
      backdrop-filter: var(--glass);
      white-space: nowrap;
      position: relative;
      overflow: hidden;
    }
    
    .btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s;
    }
    
    .btn:hover::before {
      left: 100%;
    }
    
    .btn.solid{
      background: var(--accent);
      color: white; 
      border-color: rgba(99, 102, 241, 0.3);
      box-shadow: 0 4px 15px rgba(99, 102, 241, 0.25);
    }
    
    .btn.ghost{
      background: var(--card); 
      color: var(--text);
      border-color: var(--border);
    }
    
    .btn:hover{ 
      transform: translateY(-3px) scale(1.05); 
      box-shadow: var(--shadow-hover);
    }
    
    .btn.solid:hover {
      box-shadow: 0 8px 25px rgba(99, 102, 241, 0.35);
    }
    
    .btn:active{ 
      transform: translateY(-1px) scale(1.02); 
    }

    .dash-grid{
      display: grid; 
      gap: var(--gap);
      grid-template-columns: 360px 1fr;
    }
    
    @media (max-width: 1200px){
      .dash-grid{ grid-template-columns: 320px 1fr; }
    }
    
    @media (max-width: 980px){
      .dash-grid{ grid-template-columns: 1fr; }
    }

    /* Enhanced Cards */
    .profile-card, .tickets-panel{
      border: 1px solid var(--border);
      border-radius: var(--radius);
      background: var(--card);
      backdrop-filter: var(--glass);
      box-shadow: var(--shadow);
      overflow: hidden;
      transition: all 0.3s ease;
      position: relative;
    }
    
    .profile-card::before, .tickets-panel::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: var(--accent);
      opacity: 0;
      transition: opacity 0.3s ease;
    }
    
    .profile-card:hover::before, .tickets-panel:hover::before {
      opacity: 1;
    }
    
    .profile-card:hover, .tickets-panel:hover {
      transform: translateY(-5px);
      box-shadow: var(--shadow-hover);
    }
    
    .profile-card{ 
      padding: 24px; 
    }
    
    .tickets-panel{ 
      padding: 0; 
    }

    /* Enhanced Avatar */
    .avatar{ 
      width: 100px; 
      height: 100px; 
      border-radius: 50%; 
      overflow: hidden; 
      margin-bottom: 16px; 
      border: 3px solid rgba(255,255,255,0.3);
      box-shadow: var(--shadow);
      transition: all 0.3s ease;
    }
    
    .avatar:hover {
      transform: scale(1.05);
      border-color: rgba(255,255,255,0.5);
    }
    
    .avatar img{ 
      width: 100%; 
      height: 100%; 
      object-fit: cover; 
      display: block; 
    }
    
    .avatar-fallback{
      width: 100px; 
      height: 100px; 
      border-radius: 50%;
      display: grid; 
      place-items: center; 
      font-weight: 800; 
      font-size: 2rem;
      background: var(--accent);
      color: #fff; 
      margin-bottom: 16px; 
      box-shadow: var(--shadow);
      border: 3px solid rgba(99, 102, 241, 0.2);
      transition: all 0.3s ease;
    }
    
    .avatar-fallback:hover {
      transform: scale(1.05);
      border-color: rgba(99, 102, 241, 0.4);
      box-shadow: 0 8px 25px rgba(99, 102, 241, 0.3);
    }

    .profile-meta{ 
      display: grid; 
      gap: 12px; 
      margin: 16px 0 20px; 
    }
    
    .meta-line{ 
      display: flex; 
      align-items: center; 
      justify-content: space-between; 
      gap: 12px; 
      padding: 12px 16px; 
      border: 1px solid var(--border); 
      border-radius: var(--radius); 
      background: rgba(255,255,255,0.1); 
      transition: all 0.3s ease;
    }
    
    .meta-line:hover {
      background: rgba(99, 102, 241, 0.08);
      transform: translateX(4px);
      border-color: rgba(99, 102, 241, 0.15);
    }
    
    .meta-label{ 
      color: var(--muted); 
      font-size: .9rem; 
      font-weight: 500;
    }
    
    .meta-value{ 
      font-weight: 600; 
      overflow: hidden; 
      text-overflow: ellipsis;
      color: var(--text);
    }

    .profile-btn{
      display: inline-flex; 
      align-items: center; 
      gap: .6rem;
      padding: 12px 16px; 
      border-radius: var(--radius); 
      text-decoration: none; 
      font-weight: 600;
      background: var(--card); 
      border: 1px solid var(--border); 
      color: var(--text);
      transition: all 0.3s ease;
      width: 100%;
      justify-content: center;
    }
    
    .profile-btn:hover{ 
      transform: translateY(-2px); 
      background: rgba(99, 102, 241, 0.08);
      box-shadow: var(--shadow);
      border-color: rgba(99, 102, 241, 0.2);
    }

    /* Enhanced Tickets Section */
    .tickets-head{ 
      padding: 20px 24px; 
      border-bottom: 1px solid var(--border); 
      display: flex; 
      align-items: center; 
      justify-content: space-between; 
      gap: 1rem;
      background: rgba(255,255,255,0.05);
    }
    
    .tickets-title{ 
      font-size: 1.2rem; 
      font-weight: 700; 
      letter-spacing: -0.3px;
      background: var(--accent);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
    }
    
    .tickets-list{ 
      list-style: none; 
      margin: 0; 
      padding: 16px; 
      display: grid; 
      gap: 16px; 
    }
    
    .ticket-card{
      display: grid; 
      gap: 16px; 
      grid-template-columns: 1fr auto;
      padding: 20px; 
      border: 1px solid var(--border); 
      border-radius: var(--radius);
      background: rgba(255,255,255,0.08);
      backdrop-filter: var(--glass);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    
    .ticket-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 4px;
      height: 100%;
      background: var(--accent);
      transform: scaleY(0);
      transition: transform 0.3s ease;
    }
    
    .ticket-card:hover::before {
      transform: scaleY(1);
    }
    
    .ticket-card:hover{ 
      transform: translateY(-3px); 
      background: rgba(99, 102, 241, 0.05); 
      border-color: rgba(99, 102, 241, 0.15);
      box-shadow: var(--shadow);
    }
    
    .ticket-main{ 
      display: grid; 
      gap: 12px; 
    }
    
    .t-line{ 
      display: flex; 
      align-items: center; 
      gap: 12px; 
      flex-wrap: wrap; 
    }
    
    .t-label{ 
      color: var(--muted); 
      width: 90px; 
      font-size: .9rem;
      font-weight: 500;
    }
    
    .t-value{ 
      font-weight: 600; 
      letter-spacing: 0.1px;
      color: var(--text);
    }

    .t-status{
      padding: 6px 12px; 
      border-radius: 20px; 
      font-size: .8rem; 
      letter-spacing: 0.2px;
      border: 1px solid var(--border); 
      background: rgba(255,255,255,0.1);
      font-weight: 600;
      backdrop-filter: var(--glass);
    }
    
    /* Enhanced Status Colors */
    .status-paid{ 
      background: var(--success); 
      border-color: rgba(16, 185, 129, 0.4);
      color: #fff;
      box-shadow: 0 4px 15px rgba(16, 185, 129, 0.25);
    }
    
    .status-pending{ 
      background: var(--warning); 
      border-color: rgba(245, 158, 11, 0.4);
      color: #fff;
      box-shadow: 0 4px 15px rgba(245, 158, 11, 0.25);
    }
    
    .status-failed, .status-cancelled{ 
      background: var(--danger); 
      border-color: rgba(239, 68, 68, 0.4);
      color: #fff;
      box-shadow: 0 4px 15px rgba(239, 68, 68, 0.25);
    }

    .ticket-actions{ 
      display: flex; 
      align-items: center; 
      gap: 12px; 
      flex-direction: column;
    }
    
    @media (min-width: 640px) {
      .ticket-actions {
        flex-direction: row;
      }
    }
    
    .tickets-empty{
      padding: 30px; 
      text-align: center; 
      color: var(--muted);
      border: 2px dashed var(--border); 
      border-radius: var(--radius); 
      background: rgba(99, 102, 241, 0.03);
      font-size: 1.1rem;
    }

    /* Enhanced Subsection headings */
    .subhead{
      font-weight: 700; 
      font-size: 1.1rem; 
      color: var(--text);
      display: flex; 
      align-items: center; 
      gap: .8rem; 
      margin-bottom: 1rem;
      padding-bottom: 8px;
      border-bottom: 2px solid var(--border);
    }

    /* Enhanced Footer card */
    .section-card{ 
      margin-top: 2rem; 
      padding: 24px; 
    }
    
    /* Additional animations and effects */
    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
    }
    
    .floating {
      animation: float 6s ease-in-out infinite;
    }
    
    /* Pulse effect for status indicators */
    @keyframes pulse {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.7; }
    }
    
    .status-paid, .status-pending, .status-failed, .status-cancelled {
      animation: pulse 2s ease-in-out infinite;
    }
    
    /* Smooth scrolling */
    * {
      scroll-behavior: smooth;
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
    }
    
    ::-webkit-scrollbar-track {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb {
      background: var(--accent);
      border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
      background: var(--primary);
    }
    
    /* Loading states */
    .loading {
      position: relative;
      overflow: hidden;
    }
    
    .loading::after {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      animation: loading 1.5s infinite;
    }
    
    @keyframes loading {
      0% { left: -100%; }
      100% { left: 100%; }
    }
  </style>
@endsection

@section('content')
<section class="dash-shell">
  <!-- Top bar with enhanced interaction -->
  <div class="dash-topbar" role="banner">
    <h2 class="dash-welcome">
      Welcome back, <span class="brand-accent">{{ $user->name }}</span> 👋
    </h2>
    <div style="display: flex; gap: 12px; align-items: center;">
      <div class="floating">
        <a href="{{ route('events.request.create') }}" class="btn solid">
          <!-- plus icon with enhanced styling -->
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M12 5v14M5 12h14" stroke="white" stroke-width="2.5" stroke-linecap="round"/>
          </svg>
          Request Event
        </a>
      </div>
      <button class="btn ghost" onclick="toggleNotifications()" title="Notifications">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" stroke="currentColor" stroke-width="2"/>
          <path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="2"/>
        </svg>
      </button>
    </div>
  </div>

  <div class="dash-grid">
    <!-- Left: Profile Card -->
    <aside class="profile-card" aria-label="Profile">
      @if ($user->profile_picture)
        <div class="avatar">
          <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}'s profile picture">
        </div>
      @else
        <div class="avatar-fallback" aria-hidden="true">{{ strtoupper(substr($user->name,0,1)) }}</div>
      @endif

      <div class="subhead">
        <!-- user icon -->
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Zm7 8a7 7 0 0 0-14 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
        Profile
      </div>

      <div class="profile-meta">
        <p class="meta-line" title="{{ $user->email }}">
          <span class="meta-label">Email</span>
          <span class="meta-value">{{ $user->email }}</span>
        </p>
        <p class="meta-line">
          <span class="meta-label">Phone</span>
          <span class="meta-value">{{ $user->phone ?? 'N/A' }}</span>
        </p>
      </div>

      <a href="{{ route('profile.edit') }}" class="profile-btn">
        <!-- settings icon -->
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 15.5A3.5 3.5 0 1 0 8.5 12 3.5 3.5 0 0 0 12 15.5Z" stroke="currentColor" stroke-width="1.8"/><path d="M19 12a7 7 0 0 0-.09-1.1l2.11-1.64-2-3.46-2.54 1a6.93 6.93 0 0 0-1.9-1.1l-.38-2.7h-4l-.38 2.7a6.93 6.93 0 0 0-1.9 1.1l-2.54-1-2 3.46L5.09 10.9A7 7 0 0 0 5 12a7 7 0 0 0 .09 1.1l-2.11 1.64 2 3.46 2.54-1a6.93 6.93 0 0 0 1.9 1.1l.38 2.7h4l.38-2.7a6.93 6.93 0 0 0 1.9-1.1l2.54 1 2-3.46-2.11-1.64A7 7 0 0 0 19 12Z" stroke="currentColor" stroke-width="1.2"/></svg>
        Manage Profile
      </a>
    </aside>

    <!-- Right: Tickets -->
    <main class="tickets-panel" aria-label="Your Tickets">
      <div class="tickets-head">
        <h3 class="tickets-title">Your Tickets 🎫</h3>
        @if($tickets->count())
          <span class="t-status" aria-label="Ticket count">{{ $tickets->count() }} total</span>
        @endif
      </div>

      <ul class="tickets-list">
        @forelse ($tickets as $ticket)
          @php
            $status = strtolower($ticket->payment_status ?? 'unknown');
            $statusClass = [
              'paid' => 'status-paid',
              'completed' => 'status-paid',
              'success' => 'status-paid',
              'pending' => 'status-pending',
              'processing' => 'status-pending',
              'failed' => 'status-failed',
              'cancelled' => 'status-cancelled',
            ][$status] ?? '';
          @endphp

          <li class="ticket-card">
            <div class="ticket-main">
              <div class="t-line">
                <span class="t-label">Event</span>
                <span class="t-value">{{ $ticket->event->title ?? 'Unknown' }}</span>
              </div>
              <div class="t-line">
                <span class="t-label">Date</span>
                <span class="t-value">{{ $ticket->created_at->format('M d, Y') }}</span>
              </div>
              <div class="t-line">
                <span class="t-label">Payment</span>
                <span class="t-value">
                  {{ str_replace('_',' ', $ticket->payment_option) }}
                  — <strong class="t-status {{ $statusClass }}">{{ ucfirst($ticket->payment_status) }}</strong>
                </span>
              </div>
            </div>

            <div class="ticket-actions">
              <a class="btn ghost" href="{{ route('tickets.show', $ticket) }}">
                <!-- eye icon -->
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8"/></svg>
                View
              </a>
              <a class="btn solid" href="{{ route('tickets.download', $ticket) }}">
                <!-- download icon -->
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3v12m0 0 4-4m-4 4-4-4M4 19h16" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                PDF
              </a>
            </div>
          </li>
        @empty
          <li class="tickets-empty">
            No tickets yet
          </li>
        @endforelse
      </ul>
    </main>
  </div>

  <!-- Your Event Requests -->
  <aside class="profile-card section-card" style="margin-top: 1.5rem;">
    <h3 class="subhead">
      <!-- calendar icon -->
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M7 2v4M17 2v4M3 9h18M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5A2 2 0 0 0 3 7v12a2 2 0 0 0 2 2Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
      Your Event Requests
    </h3>
    @php
      // TODO: move to controller for best practice
      $myEvents = \App\Models\Event::where('created_by', $user->id)->latest()->limit(5)->get();
    @endphp
    <ul class="tickets-list">
      @forelse($myEvents as $ev)
        <li class="ticket-card" aria-label="Event request">
          <div class="ticket-main">
            <div class="t-line"><span class="t-label">Title</span><span class="t-value">{{ $ev->title }}</span></div>
            <div class="t-line"><span class="t-label">Starts</span><span class="t-value">{{ $ev->starts_at?->format('M d, Y H:i') }}</span></div>
            @php
              $evStatus = strtolower($ev->status ?? 'draft');
              $evClass = [
                'approved' => 'status-paid',
                'published' => 'status-paid',
                'pending' => 'status-pending',
                'review' => 'status-pending',
                'rejected' => 'status-failed',
                'cancelled' => 'status-cancelled',
                'draft' => ''
              ][$evStatus] ?? '';
            @endphp
            <div class="t-line">
              <span class="t-label">Status</span>
              <span class="t-value"><strong class="t-status {{ $evClass }}">{{ ucfirst($ev->status) }}</strong></span>
            </div>
          </div>
        </li>
      @empty
        <li class="tickets-empty">
          No event requests yet. Start by
          <a class="btn ghost" style="margin-left:.5rem" href="{{ route('events.request.create') }}">requesting an event</a>.
        </li>
      @endforelse
    </ul>
  </aside>
</section>

<script>
  // Enhanced Dashboard Interactions
  function toggleNotifications() {
    // Simple notification toggle for demo
    const btn = event.target.closest('button');
    btn.style.transform = 'scale(0.95)';
    setTimeout(() => {
      btn.style.transform = '';
      alert('🔔 Notifications feature coming soon!');
    }, 150);
  }

  // Add loading states to buttons
  document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(btn => {
      btn.addEventListener('click', function(e) {
        // Add loading animation except for profile manage button
        if (!this.href || this.href.includes('profile.edit')) return;
        
        const originalContent = this.innerHTML;
        this.classList.add('loading');
        this.style.pointerEvents = 'none';
        
        // Simulate loading state for visual feedback
        setTimeout(() => {
          this.classList.remove('loading');
          this.style.pointerEvents = '';
        }, 800);
      });
    });

    // Add smooth scroll behavior for internal links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });

    // Add intersection observer for animations
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
        }
      });
    }, observerOptions);

    // Observe cards for slide-in animations
    document.querySelectorAll('.profile-card, .tickets-panel, .ticket-card').forEach(card => {
      card.style.opacity = '0';
      card.style.transform = 'translateY(20px)';
      card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
      observer.observe(card);
    });

    // Add subtle parallax effect to background
    window.addEventListener('scroll', () => {
      const scrolled = window.pageYOffset;
      const bg = document.querySelector('.dash-shell::before');
      if (bg) {
        document.querySelector('.dash-shell').style.backgroundPosition = `center ${scrolled * 0.5}px`;
      }
    });

    // Add hover sound effects (optional)
    const addHoverSounds = false; // Set to true if you want sound effects
    if (addHoverSounds) {
      document.querySelectorAll('.btn, .ticket-card, .meta-line').forEach(el => {
        el.addEventListener('mouseenter', () => {
          // You can add subtle audio feedback here
          // new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmkgAkWfzsyyhC8MLKaC7N2FNwgZaKHh5KdVE').play();
        });
      });
    }
  });
</script>
@endsection
