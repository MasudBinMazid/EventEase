@extends('layouts.app')
@section('title', 'Notifications')

@section('extra-css')
<style>
:root {
  --bg: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
  --card: rgba(255, 255, 255, 0.95);
  --border: rgba(8, 145, 178, 0.12);
  --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  --radius: 12px;
  --text: #111827;
  --text-secondary: #374151;
  --muted: #6b7280;
  --primary: #0f172a;
}

.notifications-container {
  padding: 2rem;
  background: var(--bg);
  min-height: 100vh;
}

.notifications-header {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 2rem;
  margin-bottom: 2rem;
  box-shadow: var(--shadow);
}

.notifications-list {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  overflow: hidden;
}

.notification-item {
  padding: 1.5rem;
  border-bottom: 1px solid var(--border);
  transition: all 0.3s ease;
}

.notification-item:last-child {
  border-bottom: none;
}

.notification-item.unread {
  background: rgba(99, 102, 241, 0.05);
  border-left: 4px solid var(--primary);
}

.notification-item:hover {
  background: rgba(99, 102, 241, 0.08);
}

.notification-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
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

.btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  border-radius: var(--radius);
  text-decoration: none;
  font-weight: 600;
  transition: all 0.3s ease;
  border: 1px solid var(--border);
}

.btn-primary {
  background: var(--primary);
  color: white;
  border-color: var(--primary);
}

.btn-ghost {
  background: var(--card);
  color: var(--text);
}

.btn:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow);
}
</style>
@endsection

@section('content')
<div class="notifications-container">
  <div class="notifications-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
      <div>
        <h1 style="margin: 0 0 0.5rem 0; color: var(--text); font-size: 1.75rem;">🔔 All Notifications</h1>
        <p style="margin: 0; color: var(--muted);">Stay updated with your latest messages and announcements</p>
      </div>
      <div style="display: flex; gap: 1rem;">
        <button onclick="markAllAsRead()" class="btn btn-primary">
          Mark All Read
        </button>
        <a href="{{ route('dashboard') }}" class="btn btn-ghost">
          ← Back to Dashboard
        </a>
      </div>
    </div>
  </div>

  <div class="notifications-list">
    @forelse($notifications as $notification)
      <div class="notification-item {{ $notification->isUnread() ? 'unread' : '' }}" data-id="{{ $notification->id }}">
        <div class="notification-meta">
          <span class="notification-type notification-type-{{ $notification->type }}">
            {{ ucfirst($notification->type) }}
          </span>
          <span style="color: var(--muted); font-size: 0.9rem;">
            {{ $notification->created_at->format('M j, Y \a\t g:i A') }}
          </span>
        </div>
        
        <h3 style="margin: 0 0 0.75rem 0; color: var(--text); font-size: 1.1rem;">
          {{ $notification->title }}
        </h3>
        
        <p style="margin: 0 0 1rem 0; color: var(--text-secondary); line-height: 1.5;">
          {{ $notification->message }}
        </p>
        
        <div style="display: flex; justify-content: space-between; align-items: center;">
          <small style="color: var(--muted);">
            From: {{ $notification->sender->name }}
          </small>
          @if($notification->isUnread())
            <button onclick="markAsRead({{ $notification->id }})" 
                    style="background: none; border: 1px solid var(--border); color: var(--text); padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; font-size: 0.85rem;">
              Mark as Read
            </button>
          @else
            <span style="color: var(--muted); font-size: 0.85rem;">✓ Read</span>
          @endif
        </div>
      </div>
    @empty
      <div style="padding: 3rem; text-align: center; color: var(--muted);">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="margin-bottom: 1rem; opacity: 0.5;">
          <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
          <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
        </svg>
        <h3 style="margin: 0 0 0.5rem 0;">No notifications yet</h3>
        <p style="margin: 0;">You'll see updates and messages here when they arrive.</p>
      </div>
    @endforelse
  </div>

  @if($notifications->hasPages())
    <div style="margin-top: 2rem; background: var(--card); border: 1px solid var(--border); border-radius: var(--radius); padding: 1.5rem;">
      {{ $notifications->links() }}
    </div>
  @endif
</div>

<script>
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
        location.reload(); // Refresh to update the UI
      }
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
      alert('✅ All notifications marked as read');
      location.reload();
    }
  })
  .catch(error => console.error('Error marking all notifications as read:', error));
}
</script>
@endsection
