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

        <!-- Styling Options -->
        <div class="form-group">
          <label class="form-label">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; margin-right: 0.5rem;">
              <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
            Visual Styling
          </label>
          
          <!-- Colors -->
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
            <div>
              <label for="bg_color" style="display: block; font-size: 0.85rem; font-weight: 500; color: var(--text-light); margin-bottom: 0.5rem;">
                🎨 Background Color
              </label>
              <div style="display: flex; gap: 0.5rem; align-items: center;">
                <input type="color" 
                       id="bg_color" 
                       name="bg_color" 
                       value="{{ old('bg_color', '#f59e0b') }}"
                       style="width: 50px; height: 40px; border-radius: 8px; border: 2px solid var(--border); cursor: pointer;">
                <input type="text" 
                       id="bg_color_text" 
                       value="{{ old('bg_color', '#f59e0b') }}"
                       class="form-input" 
                       placeholder="#f59e0b"
                       style="flex: 1;">
              </div>
              @error('bg_color')
                <div style="color: var(--danger); font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</div>
              @enderror
            </div>

            <div>
              <label for="text_color" style="display: block; font-size: 0.85rem; font-weight: 500; color: var(--text-light); margin-bottom: 0.5rem;">
                ✏️ Text Color
              </label>
              <div style="display: flex; gap: 0.5rem; align-items: center;">
                <input type="color" 
                       id="text_color" 
                       name="text_color" 
                       value="{{ old('text_color', '#ffffff') }}"
                       style="width: 50px; height: 40px; border-radius: 8px; border: 2px solid var(--border); cursor: pointer;">
                <input type="text" 
                       id="text_color_text" 
                       value="{{ old('text_color', '#ffffff') }}"
                       class="form-input" 
                       placeholder="#ffffff"
                       style="flex: 1;">
              </div>
              @error('text_color')
                <div style="color: var(--danger); font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <!-- Font Settings -->
          <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 1rem;">
            <div>
              <label for="font_family" style="display: block; font-size: 0.85rem; font-weight: 500; color: var(--text-light); margin-bottom: 0.5rem;">
                📝 Font Family
              </label>
              <select id="font_family" name="font_family" class="form-input">
                <option value="Inter, sans-serif" {{ old('font_family', 'Inter, sans-serif') == 'Inter, sans-serif' ? 'selected' : '' }}>Inter (Default)</option>
                <option value="Arial, sans-serif" {{ old('font_family') == 'Arial, sans-serif' ? 'selected' : '' }}>Arial</option>
                <option value="Helvetica, sans-serif" {{ old('font_family') == 'Helvetica, sans-serif' ? 'selected' : '' }}>Helvetica</option>
                <option value="Georgia, serif" {{ old('font_family') == 'Georgia, serif' ? 'selected' : '' }}>Georgia</option>
                <option value="Times New Roman, serif" {{ old('font_family') == 'Times New Roman, serif' ? 'selected' : '' }}>Times New Roman</option>
                <option value="Courier New, monospace" {{ old('font_family') == 'Courier New, monospace' ? 'selected' : '' }}>Courier New</option>
                <option value="Roboto, sans-serif" {{ old('font_family') == 'Roboto, sans-serif' ? 'selected' : '' }}>Roboto</option>
                <option value="Open Sans, sans-serif" {{ old('font_family') == 'Open Sans, sans-serif' ? 'selected' : '' }}>Open Sans</option>
                <option value="Poppins, sans-serif" {{ old('font_family') == 'Poppins, sans-serif' ? 'selected' : '' }}>Poppins</option>
                <option value="Montserrat, sans-serif" {{ old('font_family') == 'Montserrat, sans-serif' ? 'selected' : '' }}>Montserrat</option>
              </select>
              @error('font_family')
                <div style="color: var(--danger); font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</div>
              @enderror
            </div>

            <div>
              <label for="font_size" style="display: block; font-size: 0.85rem; font-weight: 500; color: var(--text-light); margin-bottom: 0.5rem;">
                📏 Size (px)
              </label>
              <input type="number" 
                     id="font_size" 
                     name="font_size" 
                     value="{{ old('font_size', 16) }}"
                     class="form-input"
                     min="10" 
                     max="48"
                     placeholder="16">
              @error('font_size')
                <div style="color: var(--danger); font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</div>
              @enderror
            </div>

            <div>
              <label for="font_weight" style="display: block; font-size: 0.85rem; font-weight: 500; color: var(--text-light); margin-bottom: 0.5rem;">
                💪 Weight
              </label>
              <select id="font_weight" name="font_weight" class="form-input">
                <option value="300" {{ old('font_weight') == '300' ? 'selected' : '' }}>Light</option>
                <option value="400" {{ old('font_weight') == '400' ? 'selected' : '' }}>Normal</option>
                <option value="500" {{ old('font_weight', '500') == '500' ? 'selected' : '' }}>Medium</option>
                <option value="600" {{ old('font_weight') == '600' ? 'selected' : '' }}>Semi Bold</option>
                <option value="700" {{ old('font_weight') == '700' ? 'selected' : '' }}>Bold</option>
                <option value="800" {{ old('font_weight') == '800' ? 'selected' : '' }}>Extra Bold</option>
              </select>
              @error('font_weight')
                <div style="color: var(--danger); font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</div>
              @enderror
            </div>

            <div>
              <label for="text_style" style="display: block; font-size: 0.85rem; font-weight: 500; color: var(--text-light); margin-bottom: 0.5rem;">
                🎭 Style
              </label>
              <select id="text_style" name="text_style" class="form-input">
                <option value="normal" {{ old('text_style', 'normal') == 'normal' ? 'selected' : '' }}>Normal</option>
                <option value="italic" {{ old('text_style') == 'italic' ? 'selected' : '' }}>Italic</option>
              </select>
              @error('text_style')
                <div style="color: var(--danger); font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <!-- Preview -->
          <div style="margin-top: 1rem;">
            <label style="display: block; font-size: 0.85rem; font-weight: 500; color: var(--text-light); margin-bottom: 0.5rem;">
              👁️ Live Preview
            </label>
            <div id="notice-preview" 
                 style="padding: 12px 20px; border-radius: 8px; border: 2px solid var(--border); min-height: 50px; display: flex; align-items: center; overflow: hidden; position: relative;">
              <div id="preview-text" style="white-space: nowrap; animation: scroll-left 10s linear infinite;">
                Your notice content will appear here...
              </div>
            </div>
          </div>

          <div style="color: var(--text-light); font-size: 0.85rem; margin-top: 0.5rem;">
            Customize the appearance of your scrolling notice to match your brand
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
      updatePreview(); // Update preview when content changes
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

  // Color picker synchronization
  const bgColorPicker = document.getElementById('bg_color');
  const bgColorText = document.getElementById('bg_color_text');
  const textColorPicker = document.getElementById('text_color');
  const textColorText = document.getElementById('text_color_text');

  if (bgColorPicker && bgColorText) {
    bgColorPicker.addEventListener('input', function() {
      bgColorText.value = this.value;
      updatePreview();
    });
    
    bgColorText.addEventListener('input', function() {
      if (this.value.match(/^#[0-9A-F]{6}$/i)) {
        bgColorPicker.value = this.value;
        updatePreview();
      }
    });
  }

  if (textColorPicker && textColorText) {
    textColorPicker.addEventListener('input', function() {
      textColorText.value = this.value;
      updatePreview();
    });
    
    textColorText.addEventListener('input', function() {
      if (this.value.match(/^#[0-9A-F]{6}$/i)) {
        textColorPicker.value = this.value;
        updatePreview();
      }
    });
  }

  // Font and style change handlers
  const fontFamily = document.getElementById('font_family');
  const fontSize = document.getElementById('font_size');
  const fontWeight = document.getElementById('font_weight');
  const textStyle = document.getElementById('text_style');

  [fontFamily, fontSize, fontWeight, textStyle].forEach(element => {
    if (element) {
      element.addEventListener('change', updatePreview);
    }
  });

  // Live preview function
  function updatePreview() {
    const preview = document.getElementById('notice-preview');
    const previewText = document.getElementById('preview-text');
    
    if (!preview || !previewText) return;

    const content = contentInput ? contentInput.value || 'Your notice content will appear here...' : 'Your notice content will appear here...';
    const bgColor = bgColorText ? bgColorText.value : '#f59e0b';
    const textColor = textColorText ? textColorText.value : '#ffffff';
    const fontFam = fontFamily ? fontFamily.value : 'Inter, sans-serif';
    const fontSz = fontSize ? fontSize.value + 'px' : '16px';
    const fontWt = fontWeight ? fontWeight.value : '500';
    const textSt = textStyle ? textStyle.value : 'normal';

    preview.style.backgroundColor = bgColor;
    previewText.style.color = textColor;
    previewText.style.fontFamily = fontFam;
    previewText.style.fontSize = fontSz;
    previewText.style.fontWeight = fontWt;
    previewText.style.fontStyle = textSt;
    previewText.textContent = content;
  }

  // Initial preview update
  updatePreview();
});
</script>

<style>
@media (max-width: 768px) {
  .form-group > div[style*="grid-template-columns"] {
    grid-template-columns: 1fr !important;
  }
}

@keyframes scroll-left {
  0% { transform: translateX(100%); }
  100% { transform: translateX(-100%); }
}
</style>
@endsection
