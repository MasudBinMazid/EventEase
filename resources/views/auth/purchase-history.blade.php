@extends('layouts.app')
@section('title', 'Purchase History')

@section('extra-css')
  <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
  <!-- Inline design tokens + components (scoped) -->
  <style>
:root {
  /* Professional EventEase Color Palette - Updated */
  --bg: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
  --card: rgba(255, 255, 255, 0.95);
  --surface: rgba(255, 255, 255, 0.90);
  
  /* Professional Borders and Shadows */
  --border: rgba(8, 145, 178, 0.12);
  --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  --shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  --radius: 12px;
  --glass: blur(20px) saturate(120%);
  --glass-strong: blur(25px) saturate(140%);

  /* Professional Typography Colors */
  --text: #111827;
  --text-secondary: #374151;
  --muted: #6b7280;

  /* Professional Accent Colors */
  --accent: linear-gradient(135deg, #0f172a 0%, #334155 100%);
  --accent-2: linear-gradient(135deg, #0891b2 0%, #0d9488 100%);
  --accent-3: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
  --success: linear-gradient(135deg, #059669 0%, #0d9488 100%);
  --warning: linear-gradient(135deg, #d97706 0%, #ea580c 100%);
  --danger: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
  
  /* Professional Brand Colors */
  --primary: #0f172a;
  --primary-light: #e0f2fe;
  --secondary: #6b7280;
  --accent-soft: #ecfdf5;
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
    
    .dash-welcome{
      font-size: clamp(1.4rem, 3vw, 2rem);
      font-weight: 800;
      letter-spacing: -0.5px;
      text-shadow: 0 2px 10px rgba(0,0,0,0.1);
      color: var(--text) !important;
    }
    
    .brand-accent{
      background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #1e293b 100%);
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

    /* Enhanced Cards */
    .history-panel{
      border: 1px solid var(--border);
      border-radius: var(--radius);
      background: var(--card);
      backdrop-filter: var(--glass);
      box-shadow: var(--shadow);
      overflow: hidden;
      transition: all 0.3s ease;
      position: relative;
    }
    
    .history-panel::before {
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
    
    .history-panel:hover::before {
      opacity: 1;
    }
    
    .history-panel:hover {
      transform: translateY(-5px);
      box-shadow: var(--shadow-hover);
    }
    
    .history-panel{ 
      padding: 0; 
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
      background: var(--success);
      transform: scaleY(0);
      transition: transform 0.3s ease;
    }
    
    .ticket-card:hover::before {
      transform: scaleY(1);
    }
    
    .ticket-card:hover{ 
      transform: translateY(-3px); 
      background: rgba(16, 185, 129, 0.05); 
      border-color: rgba(16, 185, 129, 0.15);
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
    .status-entered{ 
      background: var(--success); 
      border-color: rgba(16, 185, 129, 0.4);
      color: #fff;
      box-shadow: 0 4px 15px rgba(16, 185, 129, 0.25);
    }
    
    .status-warning{ 
      background: var(--warning); 
      border-color: rgba(245, 158, 11, 0.4);
      color: #fff;
      box-shadow: 0 4px 15px rgba(245, 158, 11, 0.25);
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
      padding: 50px 30px; 
      text-align: center; 
      color: var(--muted);
      border: 2px dashed var(--border); 
      border-radius: var(--radius); 
      background: rgba(99, 102, 241, 0.03);
      font-size: 1.1rem;
    }

    .empty-icon {
      font-size: 3rem;
      margin-bottom: 1rem;
      opacity: 0.6;
    }

    .back-link {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      color: var(--muted);
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s ease;
      margin-bottom: 1rem;
    }

    .back-link:hover {
      color: var(--text);
      transform: translateX(-5px);
    }

    /* Entry details */
    .entry-details {
      margin-top: 8px;
      padding: 8px 12px;
      background: rgba(16, 185, 129, 0.1);
      border-radius: 8px;
      font-size: 0.85rem;
      color: var(--success);
    }

    .entry-meta {
      display: flex;
      align-items: center;
      gap: 12px;
      flex-wrap: wrap;
      margin-top: 4px;
    }

    .entry-meta span {
      display: flex;
      align-items: center;
      gap: 4px;
    }
  </style>
@endsection

@section('content')
<section class="dash-shell">
  <!-- Back link and header -->
  <a href="{{ route('dashboard') }}" class="back-link">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M19 12H5M12 19l-7-7 7-7"/>
    </svg>
    Back to Dashboard
  </a>

  <!-- Top bar -->
  <div class="dash-topbar" role="banner">
    <h2 class="dash-welcome">
      Purchase <span class="brand-accent">History</span> 📋
    </h2>
    <div style="display: flex; gap: 12px; align-items: center;">
      <div class="t-status status-entered">
        {{ $enteredTickets->count() }} Entered
      </div>
    </div>
  </div>

  <!-- Purchase History Panel -->
  <main class="history-panel" aria-label="Purchase History">
    <div class="tickets-head">
      <h3 class="tickets-title">Entered Tickets 🎫</h3>
      @if($enteredTickets->count())
        <span class="t-status" aria-label="Entered ticket count">{{ $enteredTickets->count() }} total</span>
      @endif
    </div>

    <ul class="tickets-list">
      @forelse ($enteredTickets as $ticket)
        <li class="ticket-card">
          <div class="ticket-main">
            <div class="t-line">
              <span class="t-label">Event</span>
              <span class="t-value">{{ $ticket->event->title ?? 'Unknown' }}</span>
            </div>
            <div class="t-line">
              <span class="t-label">Purchased</span>
              <span class="t-value">{{ $ticket->created_at->format('M d, Y') }}</span>
            </div>
            <div class="t-line">
              <span class="t-label">Payment</span>
              <span class="t-value">
                {{ str_replace('_',' ', $ticket->payment_option) }}
                — <strong class="t-status status-entered">{{ ucfirst($ticket->payment_status) }}</strong>
              </span>
            </div>
            <div class="t-line">
              <span class="t-label">Quantity</span>
              <span class="t-value">{{ $ticket->quantity }} {{ $ticket->quantity > 1 ? 'tickets' : 'ticket' }}</span>
            </div>
            <div class="t-line">
              <span class="t-label">Total Amount</span>
              <span class="t-value">৳{{ number_format($ticket->total_amount, 2) }}</span>
            </div>
            
            <!-- Entry Information -->
            <div class="entry-details">
              <div style="font-weight: 600; margin-bottom: 4px;">
                ✅ Event Attended
              </div>
              <div class="entry-meta">
                <span>
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12,6 12,12 16,14"/>
                  </svg>
                  {{ $ticket->entry_marked_at ? $ticket->entry_marked_at->format('M d, Y g:i A') : 'N/A' }}
                </span>
                @if($ticket->entryMarker)
                  <span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                      <circle cx="12" cy="7" r="4"/>
                    </svg>
                    by {{ $ticket->entryMarker->name }}
                  </span>
                @endif
              </div>
            </div>
          </div>

          <div class="ticket-actions">
            <a class="btn ghost" href="{{ route('tickets.show', $ticket) }}">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" stroke="currentColor" stroke-width="1.8"/>
                <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8"/>
              </svg>
              View
            </a>
            <a class="btn solid" href="{{ route('tickets.download', $ticket) }}">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M12 3v12m0 0 4-4m-4 4-4-4M4 19h16" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              PDF
            </a>
          </div>
        </li>
      @empty
        <li class="tickets-empty">
          <div class="empty-icon">🎫</div>
          <div style="font-size: 1.2rem; font-weight: 600; margin-bottom: 0.5rem;">
            No entered tickets yet
          </div>
          <div style="color: var(--muted); margin-bottom: 1.5rem;">
            Tickets will appear here after you attend events and they are marked as entered.
          </div>
          <a href="{{ route('dashboard') }}" class="btn ghost">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Back to Dashboard
          </a>
        </li>
      @endforelse
    </ul>
  </main>
</section>

<script>
  // Enhanced interactions
  document.addEventListener('DOMContentLoaded', function() {
    // Add loading states to buttons
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(btn => {
      btn.addEventListener('click', function(e) {
        if (!this.href) return;
        
        const originalContent = this.innerHTML;
        this.style.pointerEvents = 'none';
        this.style.opacity = '0.7';
        
        // Simulate loading state
        setTimeout(() => {
          this.style.pointerEvents = '';
          this.style.opacity = '';
        }, 800);
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
    document.querySelectorAll('.history-panel, .ticket-card').forEach(card => {
      card.style.opacity = '0';
      card.style.transform = 'translateY(20px)';
      card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
      observer.observe(card);
    });
  });
</script>
@endsection
