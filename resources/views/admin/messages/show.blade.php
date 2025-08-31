@extends('admin.layout')
@section('title','Message #'.$contact->id)

@section('content')

<div class="admin-page">
  <!-- Page Header -->
  <div class="admin-header">
    <div>
      <h1 class="admin-title">Message Details</h1>
      <p class="admin-subtitle">View and respond to contact message #{{ $contact->id }}</p>
    </div>
    <div class="admin-actions">
      <a href="mailto:{{ $contact->email }}?subject={{ rawurlencode('Re: Your message to us') }}" class="btn btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
          <polyline points="22,6 12,13 2,6"/>
        </svg>
        Reply by Email
      </a>
      <button class="btn btn-outline" type="button" id="copyEmailBtn" data-email="{{ $contact->email }}">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect width="14" height="14" x="8" y="8" rx="2" ry="2"/>
          <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/>
        </svg>
        Copy Email
      </button>
      <a href="{{ route('admin.messages.index') }}" class="btn btn-outline">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="15,18 9,12 15,6"/>
        </svg>
        Back to Messages
      </a>
    </div>
  </div>

  <!-- Contact Information -->
  <div class="admin-card">
    <div class="card-header">
      <h3 class="card-title">Contact Information</h3>
      <p class="card-subtitle">Sender details and message metadata</p>
    </div>
    <div class="card-body">
      <div class="contact-info-grid">
        <div class="contact-info-item">
          <div class="info-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
              <circle cx="12" cy="7" r="4"/>
            </svg>
          </div>
          <div>
            <label class="info-label">Full Name</label>
            <div class="info-value">{{ $contact->name }}</div>
          </div>
        </div>

        <div class="contact-info-item">
          <div class="info-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
              <polyline points="22,6 12,13 2,6"/>
            </svg>
          </div>
          <div>
            <label class="info-label">Email Address</label>
            <div class="info-value">
              <a href="mailto:{{ $contact->email }}" style="color: var(--primary); text-decoration: none; font-weight: 600;">
                {{ $contact->email }}
              </a>
            </div>
          </div>
        </div>

        <div class="contact-info-item">
          <div class="info-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"/>
              <polyline points="12,6 12,12 16,14"/>
            </svg>
          </div>
          <div>
            <label class="info-label">Received</label>
            <div class="info-value">
              <span class="badge badge-info">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <circle cx="12" cy="12" r="10"/>
                  <polyline points="12,6 12,12 16,14"/>
                </svg>
                {{ \Carbon\Carbon::parse($contact->created_at)->format('M j, Y g:i A') }}
              </span>
            </div>
          </div>
        </div>

        <div class="contact-info-item">
          <div class="info-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"/>
              <line x1="12" y1="8" x2="12" y2="12"/>
              <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
          </div>
          <div>
            <label class="info-label">Time Since</label>
            <div class="info-value">{{ \Carbon\Carbon::parse($contact->created_at)->diffForHumans() }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Message Content -->
  <div class="admin-card">
    <div class="card-header">
      <h3 class="card-title">Message Content</h3>
      <p class="card-subtitle">Full message from {{ $contact->name }}</p>
    </div>
    <div class="card-body">
      <div class="message-content">
        <div class="message-bubble">
          <div class="message-header">
            <div class="avatar">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
              </svg>
            </div>
            <div>
              <div style="font-weight: 600; color: var(--text);">{{ $contact->name }}</div>
              <div style="font-size: 0.85rem; color: var(--text-light);">{{ $contact->email }}</div>
            </div>
            <div style="margin-left: auto; font-size: 0.8rem; color: var(--text-muted);">
              {{ \Carbon\Carbon::parse($contact->created_at)->format('g:i A') }}
            </div>
          </div>
          <div class="message-body">
            {{ $contact->message }}
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Action Buttons -->
  <div style="display: flex; gap: 1rem; justify-content: flex-start; margin-top: 2rem;">
    <a href="{{ route('admin.messages.index') }}" class="btn btn-outline">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <polyline points="15,18 9,12 15,6"/>
      </svg>
      Back to Messages
    </a>
  </div>
</div>

<style>
  .contact-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
  }
  
  .contact-info-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    border: 1px solid var(--border-light);
    border-radius: 12px;
    background: var(--surface-light);
  }
  
  .info-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: var(--primary);
    color: white;
    flex-shrink: 0;
  }
  
  .info-label {
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-muted);
    margin-bottom: 0.25rem;
  }
  
  .info-value {
    font-weight: 600;
    color: var(--text);
    font-size: 0.95rem;
  }
  
  .message-content {
    max-width: none;
  }
  
  .message-bubble {
    border: 1px solid var(--border-light);
    border-radius: 16px;
    background: var(--surface);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
  }
  
  .message-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.5rem;
    background: var(--surface-light);
    border-bottom: 1px solid var(--border-light);
  }
  
  .avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--primary);
    color: white;
  }
  
  .message-body {
    padding: 1.5rem;
    font-size: 1rem;
    line-height: 1.6;
    color: var(--text);
    white-space: pre-wrap;
    word-break: break-word;
    background: white;
    border-radius: 0 0 16px 16px;
  }
  
  @media (max-width: 768px) {
    .contact-info-grid {
      grid-template-columns: 1fr;
      gap: 1rem;
    }
    
    .contact-info-item {
      padding: 0.75rem;
    }
    
    .info-icon {
      width: 36px;
      height: 36px;
    }
    
    .message-header {
      padding: 1rem;
      flex-wrap: wrap;
      gap: 0.75rem;
    }
    
    .message-body {
      padding: 1rem;
    }
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const copyBtn = document.getElementById('copyEmailBtn');
    
    if (copyBtn) {
      copyBtn.addEventListener('click', async function() {
        const email = copyBtn.getAttribute('data-email');
        
        try {
          await navigator.clipboard.writeText(email);
          
          // Update button to show success
          const originalHTML = copyBtn.innerHTML;
          copyBtn.innerHTML = `
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="m9 12 2 2 4-4"/>
              <path d="m21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9c2.12 0 4.07.74 5.61 1.97"/>
            </svg>
            Copied!
          `;
          
          setTimeout(() => {
            copyBtn.innerHTML = originalHTML;
          }, 2000);
          
        } catch (err) {
          alert('Could not copy email address. Please try again.');
        }
      });
    }
  });
</script>
@endsection
