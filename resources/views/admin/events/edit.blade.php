@extends('admin.layout')
@section('title','Edit Event #'.$event->id)

@section('content')
@php
  $dt = fn($s)=> $s ? str_replace(' ', 'T', substr($s, 0, 16)) : '';
@endphp

<div class="admin-page">
  <!-- Page Header -->
  <div class="admin-header">
    <div>
      <h1 class="admin-title">Edit Event #{{ $event->id }}</h1>
      <p class="admin-subtitle">Update the event details and save changes</p>
    </div>
    <div class="admin-actions">
      <a href="{{ route('admin.events.index') }}" class="btn btn-outline">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="15,18 9,12 15,6"/>
        </svg>
        Back to Events
      </a>
    </div>
  </div>

  <!-- Form Card -->
  <div class="admin-card">
    <div class="card-header">
      <h3 class="card-title">Edit Event Information</h3>
      <p class="card-subtitle">Update event details and visibility settings</p>
    </div>
    
    <div class="card-body">
      @if ($errors->any())
        <div class="alert alert-error">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <line x1="15" y1="9" x2="9" y2="15"/>
            <line x1="9" y1="9" x2="15" y2="15"/>
          </svg>
          <div>
            <strong>Please review the highlighted fields:</strong>
            <ul style="margin-top: 0.5rem; list-style-type: disc; margin-left: 1.5rem;">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        </div>
      @endif

      <form method="post" enctype="multipart/form-data" action="{{ route('admin.events.update', $event) }}" class="form-grid">
        @csrf @method('PUT')

        <!-- Basic Information Section -->
        <div class="form-section">
          <h4 class="section-title">Basic Information</h4>
          
          <div class="form-group">
            <label class="form-label" for="title">
              Event Title
              <span class="required">*</span>
            </label>
            <input type="text" name="title" id="title" class="form-input {{ $errors->has('title') ? 'error' : '' }}" value="{{ old('title', $event->title) }}" required placeholder="Enter event title">
            @error('title')
              <div class="form-error">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group">
            <label class="form-label" for="description">Description</label>
            <textarea name="description" id="description" class="form-input {{ $errors->has('description') ? 'error' : '' }}" rows="4" placeholder="Describe the event details, agenda, and what attendees can expect">{{ old('description', $event->description) }}</textarea>
            @error('description')
              <div class="form-error">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label" for="location">Location</label>
              <input type="text" name="location" id="location" class="form-input {{ $errors->has('location') ? 'error' : '' }}" value="{{ old('location', $event->location) }}" placeholder="City or general area">
              @error('location')
                <div class="form-error">{{ $message }}</div>
              @enderror
            </div>
            <div class="form-group">
              <label class="form-label" for="venue">Venue</label>
              <input type="text" name="venue" id="venue" class="form-input {{ $errors->has('venue') ? 'error' : '' }}" value="{{ old('venue', $event->venue) }}" placeholder="Specific venue name">
              @error('venue')
                <div class="form-error">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>

        <!-- Schedule & Capacity Section -->
        <div class="form-section">
          <h4 class="section-title">Schedule & Capacity</h4>
          
          <div class="form-row">
            <div class="form-group">
              <label class="form-label" for="starts_at">Start Date & Time</label>
              <input type="datetime-local" name="starts_at" id="starts_at" class="form-input {{ $errors->has('starts_at') ? 'error' : '' }}" value="{{ old('starts_at', $dt($event->starts_at)) }}">
              <div class="form-help">Use your local timezone</div>
              @error('starts_at')
                <div class="form-error">{{ $message }}</div>
              @enderror
            </div>
            <div class="form-group">
              <label class="form-label" for="ends_at">End Date & Time</label>
              <input type="datetime-local" name="ends_at" id="ends_at" class="form-input {{ $errors->has('ends_at') ? 'error' : '' }}" value="{{ old('ends_at', $dt($event->ends_at)) }}">
              <div class="form-help">Leave empty for single-session events</div>
              @error('ends_at')
                <div class="form-error">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label" for="capacity">Capacity</label>
              <input type="number" name="capacity" id="capacity" class="form-input {{ $errors->has('capacity') ? 'error' : '' }}" value="{{ old('capacity', $event->capacity) }}" min="0" placeholder="Maximum attendees">
              @error('capacity')
                <div class="form-error">{{ $message }}</div>
              @enderror
            </div>
            <div class="form-group">
              <label class="form-label" for="price">Price</label>
              <input type="number" name="price" id="price" class="form-input {{ $errors->has('price') ? 'error' : '' }}" value="{{ old('price', $event->price) }}" min="0" step="0.01" placeholder="0.00">
              @error('price')
                <div class="form-error">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="form-group">
            <label class="form-label" for="purchase_option">Payment Option</label>
            @php $opt = old('purchase_option', $event->purchase_option ?? 'both'); @endphp
            <select name="purchase_option" id="purchase_option" class="form-input {{ $errors->has('purchase_option') ? 'error' : '' }}">
              <option value="both" {{ $opt === 'both' ? 'selected' : '' }}>Both Pay Now & Pay Later</option>
              <option value="pay_now" {{ $opt === 'pay_now' ? 'selected' : '' }}>Pay Now Only</option>
              <option value="pay_later" {{ $opt === 'pay_later' ? 'selected' : '' }}>Pay Later Only</option>
            </select>
            @error('purchase_option')
              <div class="form-error">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <!-- Media & Visibility Section -->
        <div class="form-section">
          <h4 class="section-title">Media & Visibility</h4>
          
          @if ($event->banner)
            <div class="form-group">
              <label class="form-label">Current Banner</label>
              <div class="image-preview-container">
                <img src="{{ asset($event->banner) }}" alt="Current banner" class="image-preview">
                <div class="image-overlay">
                  <span class="image-badge">Current Banner</span>
                </div>
              </div>
              <div class="checkbox-container">
                <label class="checkbox-label">
                  <input type="checkbox" name="remove_banner" value="1">
                  <span class="checkbox-mark"></span>
                  <div>
                    <span class="checkbox-title">Remove current banner</span>
                    <span class="checkbox-desc">Check this to remove the existing banner image</span>
                  </div>
                </label>
              </div>
            </div>
          @else
            <div class="form-group">
              <div class="empty-state">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                  <circle cx="8.5" cy="8.5" r="1.5"/>
                  <polyline points="21,15 16,10 5,21"/>
                </svg>
                <p>No banner image uploaded</p>
              </div>
            </div>
          @endif

          <div class="form-group">
            <label class="form-label" for="banner">{{ $event->banner ? 'Replace Banner' : 'Event Banner' }}</label>
            <input type="file" name="banner" id="banner" class="form-input {{ $errors->has('banner') ? 'error' : '' }}" accept="image/*">
            <div class="form-help">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
              </svg>
              Recommended: 1600×900px (JPG or PNG, max 2MB)
            </div>
            @error('banner')
              <div class="form-error">{{ $message }}</div>
            @enderror
          </div>

          <div class="checkbox-container">
            <label class="checkbox-label">
              <input type="checkbox" name="featured_on_home" value="1" {{ old('featured_on_home', $event->featured_on_home) ? 'checked' : '' }}>
              <span class="checkbox-mark"></span>
              <div>
                <span class="checkbox-title">Feature on Home Page</span>
                <span class="checkbox-desc">Display this event in the "Upcoming Events" section on the home page</span>
              </div>
            </label>
            @error('featured_on_home')
              <div class="form-error">{{ $message }}</div>
            @enderror
          </div>

          <div class="checkbox-container">
            <label class="checkbox-label">
              <input type="checkbox" name="visible_on_site" value="1" {{ old('visible_on_site', $event->visible_on_site) ? 'checked' : '' }}>
              <span class="checkbox-mark"></span>
              <div>
                <span class="checkbox-title">Show on Public Site</span>
                <span class="checkbox-desc">Make this event visible to visitors on the public website</span>
              </div>
            </label>
            @error('visible_on_site')
              <div class="form-error">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
          <button type="submit" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="m9 12 2 2 4-4"/>
              <path d="m21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9c2.12 0 4.07.74 5.61 1.97"/>
            </svg>
            Update Event
          </button>
          <a href="{{ route('admin.events.index') }}" class="btn btn-outline">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M19 12H5m7-7l-7 7 7 7"/>
            </svg>
            Cancel
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
  .form-section {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid var(--border-light);
  }
  
  .form-section:last-child {
    border-bottom: none;
  }
  
  .section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text);
    margin: 0 0 1.5rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  
  .section-title:before {
    content: '';
    width: 4px;
    height: 1.5rem;
    background: var(--primary);
    border-radius: 2px;
  }
  
  .form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
  }
  
  .form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-start;
    padding-top: 2rem;
    border-top: 1px solid var(--border-light);
    margin-top: 2rem;
  }
  
  .required {
    color: var(--danger);
    margin-left: 0.25rem;
  }
  
  .image-preview-container {
    position: relative;
    display: inline-block;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border);
    margin-bottom: 1rem;
  }
  
  .image-preview {
    width: 100%;
    max-width: 400px;
    height: 200px;
    object-fit: cover;
    display: block;
  }
  
  .image-overlay {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
  }
  
  .image-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.5rem;
    border-radius: 999px;
    background: var(--primary);
    color: white;
    font-size: 0.75rem;
    font-weight: 600;
  }
  
  .empty-state {
    padding: 2rem;
    text-align: center;
    border: 2px dashed var(--border);
    border-radius: 12px;
    background: var(--surface-light);
    color: var(--text-light);
  }
  
  .empty-state svg {
    margin-bottom: 1rem;
    opacity: 0.5;
  }
  
  .empty-state p {
    margin: 0;
  }
  
  .checkbox-container {
    margin: 1rem 0;
  }
  
  .checkbox-label {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    cursor: pointer;
    padding: 0.75rem;
    border-radius: var(--radius);
    transition: background 0.2s ease;
  }
  
  .checkbox-label:hover {
    background: var(--surface-light);
  }
  
  .checkbox-mark {
    width: 20px;
    height: 20px;
    border: 2px solid var(--border);
    border-radius: 4px;
    position: relative;
    transition: all 0.2s ease;
    flex-shrink: 0;
    margin-top: 0.125rem;
  }
  
  .checkbox-label input[type="checkbox"] {
    display: none;
  }
  
  .checkbox-label input[type="checkbox"]:checked + .checkbox-mark {
    background: var(--primary);
    border-color: var(--primary);
  }
  
  .checkbox-label input[type="checkbox"]:checked + .checkbox-mark:after {
    content: '';
    position: absolute;
    left: 6px;
    top: 2px;
    width: 6px;
    height: 10px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
  }
  
  .checkbox-title {
    font-weight: 600;
    color: var(--text);
    display: block;
  }
  
  .checkbox-desc {
    font-size: 0.85rem;
    color: var(--text-light);
    display: block;
    margin-top: 0.25rem;
  }
  
  @media (max-width: 768px) {
    .form-row {
      grid-template-columns: 1fr;
    }
    
    .form-actions {
      flex-direction: column;
    }
  }
</style>
@endsection
