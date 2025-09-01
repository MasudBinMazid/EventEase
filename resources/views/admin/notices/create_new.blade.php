@extends('admin.layout')
@section('title','Create Notice')

@section('content')
<div class="admin-page">
  <!-- Page Header -->
  <div class="admin-header">
    <div>
      <h1 class="admin-title">Create New Notice</h1>
      <p class="admin-subtitle">Design an engaging scrolling announcement for your events page</p>
    </div>
    <div class="admin-actions">
      <a href="{{ route('admin.notices.index') }}" class="btn btn-outline">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="15,18 9,12 15,6"/>
        </svg>
        Back to Notices
      </a>
    </div>
  </div>

  <!-- Form Card -->
  <div class="admin-card">
    <div class="card-header">
      <h3 class="card-title">Notice Information</h3>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.notices.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Title Field -->
        <div class="form-group">
          <label for="title" class="form-label">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; margin-right: 0.5rem;">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
              <polyline points="14,2 14,8 20,8"/>
              <line x1="16" y1="13" x2="8" y2="13"/>
              <line x1="16" y1="17" x2="8" y2="17"/>
              <polyline points="10,9 9,9 8,9"/>
            </svg>
            Notice Title (Internal Reference)
          </label>
          <input type="text" 
                 id="title" 
                 name="title" 
                 value="{{ old('title') }}"
                 class="form-input"
                 placeholder="e.g., Summer Festival Promotion"
                 required>
          @error('title')
            <div style="color: var(--danger); font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</div>
          @enderror
          <div style="color: var(--text-light); font-size: 0.85rem; margin-top: 0.5rem;">
            This title is for your reference only - visitors won't see it
          </div>
        </div>

        <!-- Content Field -->
        <div class="form-group">
          <label for="content" class="form-label">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; margin-right: 0.5rem;">
              <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
              <line x1="4" y1="22" x2="4" y2="15"/>
            </svg>
            Scrolling Content
          </label>
          <textarea id="content" 
                    name="content" 
                    class="form-input form-textarea"
                    placeholder="🎉 Don't miss our spectacular Summer Music Festival! Early bird tickets now available with 30% discount. Book now and secure your spot for the biggest event of the year!"
                    required>{{ old('content') }}</textarea>
          @error('content')
            <div style="color: var(--danger); font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</div>
          @enderror
          <div style="color: var(--text-light); font-size: 0.85rem; margin-top: 0.5rem;">
            This text will scroll horizontally across your events page. Use emojis and engaging language!
          </div>
        </div>

        <!-- Priority Field -->
        <div class="form-group">
          <label for="priority" class="form-label">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; margin-right: 0.5rem;">
              <circle cx="12" cy="12" r="3"/>
              <path d="M12 1v6m0 6v6"/>
              <path d="m21 12-6-6m-6 6-6-6"/>
              <path d="m21 12-6 6m-6-6-6 6"/>
            </svg>
            Priority Level
          </label>
          <select id="priority" name="priority" class="form-input">
            <option value="0" {{ old('priority') == '0' ? 'selected' : '' }}>
              🟢 Low Priority - General Information
            </option>
            <option value="50" {{ old('priority') == '50' ? 'selected' : '' }}>
              🟡 Medium Priority - Important Updates
            </option>
            <option value="80" {{ old('priority') == '80' ? 'selected' : '' }}>
              🔴 High Priority - Critical Announcements
            </option>
          </select>
          @error('priority')
            <div style="color: var(--danger); font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</div>
          @enderror
          <div style="color: var(--text-light); font-size: 0.85rem; margin-top: 0.5rem;">
            Higher priority notices appear first in the scrolling sequence
          </div>
        </div>

        <!-- Active Status -->
        <div class="form-group">
          <div style="display: flex; align-items: center; padding: 1rem; background: var(--border-light); border-radius: var(--radius); border: 2px solid var(--border);">
            <input type="checkbox" 
                   name="is_active" 
                   value="1" 
                   id="is_active"
                   {{ old('is_active', true) ? 'checked' : '' }}
                   style="width: 18px; height: 18px; margin-right: 0.75rem; accent-color: var(--success);">
            <label for="is_active" style="margin: 0; cursor: pointer; flex: 1;">
              <div style="font-weight: 600; color: var(--text); margin-bottom: 0.25rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; margin-right: 0.5rem;">
                  <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                  <polyline points="22,4 12,14.01 9,11.01"/>
                </svg>
                Activate Notice
              </div>
              <div style="font-size: 0.85rem; color: var(--text-light);">Notice will be visible in the scrolling bar when enabled</div>
            </label>
          </div>
          @error('is_active')
            <div style="color: var(--danger); font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</div>
          @enderror
        </div>

        <!-- Date Range -->
        <div class="form-group">
          <label class="form-label">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; margin-right: 0.5rem;">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
              <line x1="16" y1="2" x2="16" y2="6"/>
              <line x1="8" y1="2" x2="8" y2="6"/>
              <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            Schedule (Optional)
          </label>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div>
              <label for="start_date" style="display: block; font-size: 0.85rem; font-weight: 500; color: var(--text-light); margin-bottom: 0.5rem;">
                🟢 Start Date & Time
              </label>
              <input type="datetime-local" 
                     id="start_date" 
                     name="start_date" 
                     value="{{ old('start_date') }}"
                     class="form-input">
              @error('start_date')
                <div style="color: var(--danger); font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</div>
              @enderror
            </div>

            <div>
              <label for="end_date" style="display: block; font-size: 0.85rem; font-weight: 500; color: var(--text-light); margin-bottom: 0.5rem;">
                🔴 End Date & Time
              </label>
              <input type="datetime-local" 
                     id="end_date" 
                     name="end_date" 
                     value="{{ old('end_date') }}"
                     class="form-input">
              @error('end_date')
                <div style="color: var(--danger); font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div style="color: var(--text-light); font-size: 0.85rem; margin-top: 0.5rem;">
            Leave empty for permanent notices. Set dates to show notice only during specific periods
          </div>
        </div>

        <!-- Submit Buttons -->
        <div style="display: flex; justify-content: flex-end; gap: 1rem; padding-top: 2rem; border-top: 1px solid var(--border);">
          <a href="{{ route('admin.notices.index') }}" class="btn btn-outline">
            Cancel
          </a>
          <button type="submit" class="btn btn-success">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
              <polyline points="17,21 17,13 7,13 7,21"/>
              <polyline points="7,3 7,8 15,8"/>
            </svg>
            Create Notice
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Auto-resize textarea
  const textarea = document.getElementById('content');
  if (textarea) {
    textarea.addEventListener('input', function() {
      this.style.height = 'auto';
      this.style.height = this.scrollHeight + 'px';
    });
    
    // Initial resize
    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';
  }
  
  // Character count for content
  const contentInput = document.getElementById('content');
  if (contentInput) {
    function updateCharCount() {
      const length = contentInput.value.length;
      let countEl = document.getElementById('char-count');
      
      if (!countEl) {
        countEl = document.createElement('div');
        countEl.id = 'char-count';
        countEl.style.cssText = 'color: var(--text-light); font-size: 0.8rem; margin-top: 0.5rem; text-align: right;';
        contentInput.parentNode.appendChild(countEl);
      }
      
      countEl.textContent = `${length} characters entered`;
      
      if (length > 200) {
        countEl.style.color = 'var(--danger)';
        countEl.textContent = `${length} characters (consider keeping it shorter for better readability)`;
      } else {
        countEl.style.color = 'var(--text-light)';
      }
    }
    
    contentInput.addEventListener('input', updateCharCount);
    updateCharCount();
  }
  
  // Date validation
  const startDate = document.getElementById('start_date');
  const endDate = document.getElementById('end_date');
  
  if (startDate && endDate) {
    startDate.addEventListener('change', function() {
      if (endDate.value && this.value > endDate.value) {
        alert('Start date cannot be later than end date');
        this.value = '';
      }
    });
    
    endDate.addEventListener('change', function() {
      if (startDate.value && this.value < startDate.value) {
        alert('End date cannot be earlier than start date');
        this.value = '';
      }
    });
  }
});
</script>

<style>
@media (max-width: 768px) {
  .form-group > div[style*="grid-template-columns"] {
    grid-template-columns: 1fr !important;
  }
}
</style>
@endsection
