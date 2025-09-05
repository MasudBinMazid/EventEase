@extends('layouts.app')
@section('title', 'Dashboard')

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
    
    .dash-topbar:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-hover);
    }
    .dash-welcome{
      font-size: clamp(1.4rem, 3vw, 2rem);
      font-weight: 800;
      letter-spacing: -0.5px;
      text-shadow: 0 2px 10px rgba(0,0,0,0.1);
      color: var(--text) !important; /* EventEase dark text color for visibility */
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
    
    /* Notification Badge */
    .notification-badge {
      position: absolute;
      top: -8px;
      right: -8px;
      background: #ef4444 !important;
      color: white !important;
      border-radius: 50%;
      width: 20px;
      height: 20px;
      min-width: 20px;
      min-height: 20px;
      font-size: 0.7rem;
      display: flex !important;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      border: 2px solid white;
      z-index: 10;
      box-shadow: 0 2px 4px rgba(0,0,0,0.2);
      line-height: 1;
    }
    
    /* Ensure notification button has relative positioning */
    #notificationBtn {
      position: relative !important;
      overflow: visible !important;
    }
    
    /* Alternative badge styling for better visibility */
    .notification-badge-alt {
      position: absolute;
      top: -10px;
      right: -10px;
      background: #ff3333 !important;
      color: white !important;
      border-radius: 50%;
      width: 22px;
      height: 22px;
      font-size: 0.75rem;
      font-weight: 900;
      display: flex !important;
      align-items: center;
      justify-content: center;
      border: 3px solid #ffffff;
      z-index: 999;
      box-shadow: 0 2px 8px rgba(255, 51, 51, 0.5);
      animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.1); }
      100% { transform: scale(1); }
    }
    
    /* Notifications Panel */
    .notifications-panel {
      position: fixed;
      top: 100px;
      right: 20px;
      width: 400px;
      max-width: 90vw;
      max-height: 600px;
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      box-shadow: var(--shadow-hover);
      backdrop-filter: var(--glass);
      z-index: 1000;
      overflow: hidden;
    }
    
    .notifications-header {
      padding: 16px 20px;
      border-bottom: 1px solid var(--border);
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: rgba(255,255,255,0.05);
    }
    
    .notifications-header h3 {
      margin: 0;
      font-size: 1.1rem;
      color: var(--text);
    }
    
    .notifications-actions {
      display: flex;
      gap: 8px;
      align-items: center;
    }
    
    .notifications-list {
      max-height: 500px;
      overflow-y: auto;
      padding: 0;
    }
    
    .notification-item {
      padding: 16px 20px;
      border-bottom: 1px solid var(--border);
      transition: all 0.3s ease;
      position: relative;
    }
    
    .notification-item:last-child {
      border-bottom: none;
    }
    
    .notification-item.unread {
      background: rgba(99, 102, 241, 0.05);
      border-left: 4px solid var(--primary);
    }
    
    .notification-item.unread::before {
      content: '●';
      position: absolute;
      top: 16px;
      left: 8px;
      color: var(--primary);
      font-size: 0.8rem;
    }
    
    .notification-item:hover {
      background: rgba(99, 102, 241, 0.08);
    }
    
    .notification-content {
      padding-left: 16px;
    }
    
    .notification-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 8px;
    }
    
    .notification-type {
      font-size: 0.75rem;
      padding: 4px 8px;
      border-radius: 12px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    
    .notification-type-general {
      background: rgba(99, 102, 241, 0.1);
      color: var(--primary);
    }
    
    .notification-type-urgent {
      background: rgba(239, 68, 68, 0.1);
      color: #ef4444;
    }
    
    .notification-type-announcement {
      background: rgba(16, 185, 129, 0.1);
      color: #10b981;
    }
    
    .notification-type-reminder {
      background: rgba(245, 158, 11, 0.1);
      color: #f59e0b;
    }
    
    .notification-time {
      font-size: 0.8rem;
      color: var(--muted);
    }
    
    .notification-title {
      margin: 0 0 8px 0;
      font-size: 1rem;
      font-weight: 600;
      color: var(--text);
    }
    
    .notification-message {
      margin: 0 0 12px 0;
      font-size: 0.9rem;
      color: var(--text-secondary);
      line-height: 1.4;
    }
    
    .notification-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    .notification-sender {
      color: var(--muted);
      font-size: 0.8rem;
    }
    
    .mark-read-btn {
      background: none;
      border: 1px solid var(--border);
      color: var(--text);
      padding: 4px 8px;
      border-radius: 6px;
      font-size: 0.75rem;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .mark-read-btn:hover {
      background: var(--primary);
      color: white;
      border-color: var(--primary);
    }
    
    .notification-empty {
      padding: 40px 20px;
      text-align: center;
      color: var(--muted);
    }
    
    .notification-empty svg {
      margin-bottom: 16px;
      opacity: 0.5;
    }
    
    .notification-empty p {
      margin: 0 0 8px 0;
      font-size: 1rem;
      font-weight: 600;
    }
    
    .notification-empty small {
      font-size: 0.85rem;
      opacity: 0.8;
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
    
    /* Notification panel animations */
    @keyframes slideInRight {
      0% { 
        opacity: 0; 
        transform: translateX(100%); 
      }
      100% { 
        opacity: 1; 
        transform: translateX(0); 
      }
    }
    
    @keyframes slideOutRight {
      0% { 
        opacity: 1; 
        transform: translateX(0); 
      }
      100% { 
        opacity: 0; 
        transform: translateX(100%); 
      }
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
      <button class="btn ghost" onclick="toggleNotifications()" title="Notifications" id="notificationBtn">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" stroke="currentColor" stroke-width="2"/>
          <path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="2"/>
        </svg>
        @if(isset($unreadCount) && $unreadCount > 0)
          <span class="notification-badge notification-badge-alt">{{ $unreadCount }}</span>
        @endif
      </button>
    </div>
  </div>

  <!-- Notifications Panel -->
  <div id="notificationsPanel" class="notifications-panel" style="display: none;">
    <div class="notifications-header">
      <h3>🔔 Notifications</h3>
      <div class="notifications-actions">
        @if(isset($unreadCount) && $unreadCount > 0)
          <button onclick="markAllAsRead()" class="btn ghost" style="padding: 6px 12px; font-size: 0.8rem;">
            Mark All Read
          </button>
        @endif
        <a href="{{ route('notifications.index') }}" class="btn ghost" style="padding: 6px 12px; font-size: 0.8rem;">
          View All
        </a>
        <button onclick="closeNotifications()" class="btn ghost" style="padding: 6px 12px; font-size: 0.8rem;">
          ✕
        </button>
      </div>
    </div>
    
    <div class="notifications-list">
      @forelse($notifications ?? [] as $notification)
        <div class="notification-item {{ $notification->isUnread() ? 'unread' : 'read' }}" data-id="{{ $notification->id }}">
          <div class="notification-content">
            <div class="notification-header">
              <span class="notification-type notification-type-{{ $notification->type }}">
                {{ ucfirst($notification->type) }}
              </span>
              <span class="notification-time">{{ $notification->created_at->diffForHumans() }}</span>
            </div>
            <h4 class="notification-title">{{ $notification->title }}</h4>
            <p class="notification-message">{{ $notification->message }}</p>
            <div class="notification-footer">
              <small class="notification-sender">From: {{ $notification->sender->name }}</small>
              @if($notification->isUnread())
                <button onclick="markAsRead({{ $notification->id }})" class="mark-read-btn">
                  Mark as read
                </button>
              @endif
            </div>
          </div>
        </div>
      @empty
        <div class="notification-empty">
          <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
          </svg>
          <p>No notifications yet</p>
          <small>You'll see updates and messages here</small>
        </div>
      @endforelse
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
        <h3 class="tickets-title">Valid Tickets 🎫</h3>
        <div style="display: flex; gap: 12px; align-items: center;">
          @if($tickets->count())
            <span class="t-status" aria-label="Ticket count">{{ $tickets->count() }} valid</span>
          @endif
          <a href="{{ route('purchase.history') }}" class="btn ghost" style="padding: 8px 12px; font-size: 0.85rem;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"/>
              <polyline points="12,6 12,12 16,14"/>
            </svg>
            History
          </a>
        </div>
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
              @if($ticket->payment_status === 'paid')
                <div class="t-line">
                  <span class="t-label">Entry Status</span>
                  <span class="t-value">
                    <strong class="t-status" style="background: #10b981; color: white; border-color: rgba(16, 185, 129, 0.4);">
                      Ready for Entry
                    </strong>
                  </span>
                </div>
              @endif
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
            No valid tickets yet<br>
            <small style="color: var(--muted); font-size: 0.9rem;">
              Tickets marked as "entered" are moved to <a href="{{ route('purchase.history') }}" style="color: var(--accent); text-decoration: underline;">Purchase History</a>
            </small>
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
    const panel = document.getElementById('notificationsPanel');
    if (panel.style.display === 'none' || !panel.style.display) {
      panel.style.display = 'block';
      panel.style.animation = 'slideInRight 0.3s ease';
    } else {
      panel.style.animation = 'slideOutRight 0.3s ease';
      setTimeout(() => {
        panel.style.display = 'none';
      }, 300);
    }
  }

  function closeNotifications() {
    const panel = document.getElementById('notificationsPanel');
    panel.style.animation = 'slideOutRight 0.3s ease';
    setTimeout(() => {
      panel.style.display = 'none';
    }, 300);
  }

  function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/read`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        const notificationItem = document.querySelector(`[data-id="${notificationId}"]`);
        if (notificationItem) {
          notificationItem.classList.remove('unread');
          notificationItem.classList.add('read');
          const markReadBtn = notificationItem.querySelector('.mark-read-btn');
          if (markReadBtn) markReadBtn.remove();
        }
        updateNotificationBadge();
      }
    })
    .catch(error => console.error('Error marking notification as read:', error));
  }

  function markAllAsRead() {
    fetch('/notifications/mark-all-read', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        document.querySelectorAll('.notification-item.unread').forEach(item => {
          item.classList.remove('unread');
          item.classList.add('read');
          const markReadBtn = item.querySelector('.mark-read-btn');
          if (markReadBtn) markReadBtn.remove();
        });
        updateNotificationBadge();
        alert('✅ All notifications marked as read');
      }
    })
    .catch(error => console.error('Error marking all notifications as read:', error));
  }

  function updateNotificationBadge() {
    const badge = document.querySelector('.notification-badge');
    const unreadCount = document.querySelectorAll('.notification-item.unread').length;
    
    if (badge) {
      if (unreadCount === 0) {
        badge.remove();
      } else {
        badge.textContent = unreadCount;
      }
    }
  }

  // Close notifications panel when clicking outside
  document.addEventListener('click', function(event) {
    const panel = document.getElementById('notificationsPanel');
    const btn = document.getElementById('notificationBtn');
    
    if (panel && panel.style.display === 'block' && 
        !panel.contains(event.target) && 
        !btn.contains(event.target)) {
      closeNotifications();
    }
  });

  // Add loading states to buttons
  document.addEventListener('DOMContentLoaded', function() {
    // Debug notification badge
    const notificationBtn = document.getElementById('notificationBtn');
    const badge = document.querySelector('.notification-badge');
    
    console.log('Notification button:', notificationBtn);
    console.log('Notification badge:', badge);
    
    if (notificationBtn) {
      console.log('Button computed style position:', window.getComputedStyle(notificationBtn).position);
      console.log('Button computed style overflow:', window.getComputedStyle(notificationBtn).overflow);
    }
    
    if (badge) {
      console.log('Badge computed style position:', window.getComputedStyle(badge).position);
      console.log('Badge computed style top:', window.getComputedStyle(badge).top);
      console.log('Badge computed style right:', window.getComputedStyle(badge).right);
      console.log('Badge computed style z-index:', window.getComputedStyle(badge).zIndex);
    }
    
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
