@extends('admin.layout')
@section('title','Create Event')

@section('content')
<div class="admin-page">
  <!-- Page Header -->
  <div class="admin-header">
    <div>
      <h1 class="admin-title">Create Event</h1>
      <p class="admin-subtitle">Fill in the details below to publish a new event</p>
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
      <h3 class="card-title">Event Information</h3>
      <p class="card-subtitle">Configure event details and visibility settings</p>
    </div>
    
    <div class="card-body">
      @if ($errors->any())
        <div class="alert alert-danger">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <line x1="15" y1="9" x2="9" y2="15"/>
            <line x1="9" y1="9" x2="15" y2="15"/>
          </svg>
          Please review the highlighted fields below.
        </div>
      @endif

      <form method="post" enctype="multipart/form-data" action="{{ route('admin.events.store') }}" class="admin-form">
        @csrf

        <div class="form-section">
          <h4 class="section-title">Basic Information</h4>
          
          <div class="form-group">
            <label class="form-label" for="title">
              Event Title
              <span class="required">*</span>
            </label>
            <input type="text" name="title" id="title" class="form-input" value="{{ old('title') }}" required placeholder="Enter event title">
            @error('title')
              <div class="form-error">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group">
            <label class="form-label" for="description">Description</label>
            <textarea name="description" id="description" class="form-input" rows="4" placeholder="Describe the event details, agenda, and what attendees can expect">{{ old('description') }}</textarea>
            @error('description')
              <div class="form-error">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label" for="location">Location</label>
              <input type="text" name="location" id="location" class="form-input" value="{{ old('location') }}" placeholder="City or general area">
              @error('location')
                <div class="form-error">{{ $message }}</div>
              @enderror
            </div>
            <div class="form-group">
              <label class="form-label" for="venue">Venue</label>
              <input type="text" name="venue" id="venue" class="form-input" value="{{ old('venue') }}" placeholder="Specific venue name">
              @error('venue')
                <div class="form-error">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>

        <div class="form-section">
          <h4 class="section-title">Schedule & Capacity</h4>
          
          <div class="form-row">
            <div class="form-group">
              <label class="form-label" for="starts_at">
                Start Date & Time
                <span class="required">*</span>
              </label>
              <input type="datetime-local" name="starts_at" id="starts_at" class="form-input" value="{{ old('starts_at') }}" required>
              <div class="form-hint">Use your local timezone</div>
              @error('starts_at')
                <div class="form-error">{{ $message }}</div>
              @enderror
            </div>
            <div class="form-group">
              <label class="form-label" for="ends_at">End Date & Time</label>
              <input type="datetime-local" name="ends_at" id="ends_at" class="form-input" value="{{ old('ends_at') }}">
              <div class="form-hint">Leave empty for single-session events</div>
              @error('ends_at')
                <div class="form-error">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label" for="capacity">Capacity</label>
              <input type="number" name="capacity" id="capacity" class="form-input" value="{{ old('capacity') }}" min="0" placeholder="Maximum attendees">
              @error('capacity')
                <div class="form-error">{{ $message }}</div>
              @enderror
            </div>
            <div class="form-group">
              <label class="form-label" for="price">Price</label>
              <input type="number" name="price" id="price" class="form-input" value="{{ old('price', 0) }}" min="0" step="0.01" placeholder="0.00">
              @error('price')
                <div class="form-error">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="form-group">
            <label class="form-label" for="purchase_option">Payment Option</label>
            <select name="purchase_option" id="purchase_option" class="form-input">
              <option value="both" {{ old('purchase_option', 'both') == 'both' ? 'selected' : '' }}>Both Pay Now & Pay Later</option>
              <option value="pay_now" {{ old('purchase_option') == 'pay_now' ? 'selected' : '' }}>Pay Now Only</option>
              <option value="pay_later" {{ old('purchase_option') == 'pay_later' ? 'selected' : '' }}>Pay Later Only</option>
            </select>
            @error('purchase_option')
              <div class="form-error">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="form-section">
          <h4 class="section-title">Media & Visibility</h4>
          
          <div class="form-group">
            <label class="form-label" for="banner">Event Banner</label>
            <input type="file" name="banner" id="banner" class="form-input" accept="image/*">
            <div class="form-hint">Recommended size: 1600×900px (JPG or PNG, max 2MB)</div>
            @error('banner')
              <div class="form-error">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group">
            <div class="checkbox-group">
              <label class="checkbox-label">
                <input type="checkbox" name="featured_on_home" value="1" {{ old('featured_on_home') ? 'checked' : '' }}>
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
          </div>

          <div class="form-group">
            <div class="checkbox-group">
              <label class="checkbox-label">
                <input type="checkbox" name="visible_on_site" value="1" {{ old('visible_on_site', true) ? 'checked' : '' }}>
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
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
            </svg>
            Create Event
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
@endsection
