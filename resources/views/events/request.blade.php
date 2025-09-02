@extends('layouts.app')
@section('title','Request an Event')

@section('extra-css')
<link href="{{ asset('css/event-request.css') }}" rel="stylesheet">
<style>
/* EventEase - Event Request Form Enhanced Styles */
:root {
  --primary-gradient: linear-gradient(135deg, #0891b2, #0d9488);
  --secondary-gradient: linear-gradient(135deg, #f8fafc, #f1f5f9);
  --accent-color: #0891b2;
  --success-color: #10b981;
  --warning-color: #f59e0b;
  --error-color: #ef4444;
  --text-primary: #1e293b;
  --text-secondary: #64748b;
  --text-muted: #94a3b8;
  --border-color: #e2e8f0;
  --border-focus: #0891b2;
  --shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
  --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
  --border-radius: 12px;
  --border-radius-lg: 16px;
  --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Enhanced container and card styling */
.event-request-container {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  min-height: calc(100vh - 80px);
  padding: 2rem 1rem;
  position: relative;
}

.event-request-container::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.05"><circle cx="30" cy="30" r="4"/></g></svg>') repeat;
  pointer-events: none;
}

.ee-card {
  position: relative;
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
  border-radius: 20px;
  padding: clamp(1.5rem, 3vw, 3rem);
}

.ee-title {
  display: flex;
  align-items: center;
  gap: .75rem;
  margin-bottom: 1.5rem;
}

.ee-title h2 {
  margin: 0;
  font-weight: 800;
  letter-spacing: .3px;
  font-size: 2rem;
  background: linear-gradient(135deg, #0891b2, #0d9488);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.ee-grid {
  display: grid;
  gap: 1.5rem;
  grid-template-columns: 1fr;
}

@media (min-width: 720px) {
  .ee-grid-2 {
    grid-template-columns: 1fr 1fr;
  }
  .ee-grid-3 {
    grid-template-columns: 1fr 1fr 1fr;
  }
}

.ee-label {
  display: block;
  font-weight: 600;
  margin-bottom: .5rem;
  color: #374151;
}

.ee-required {
  color: #dc3545;
  margin-left: .25rem;
}

.ee-input, .ee-textarea, .ee-select {
  width: 100%;
  border: 2px solid #e5e7eb;
  background: #fff;
  color: #111827;
  border-radius: 12px;
  padding: .875rem 1rem;
  outline: none;
  transition: all .2s ease;
  font-size: .95rem;
}

.ee-input:focus, .ee-textarea:focus, .ee-select:focus {
  border-color: #0891b2;
  box-shadow: 0 0 0 6px rgba(99, 102, 241, .1);
  transform: translateY(-1px);
}

.ee-help {
  font-size: .875rem;
  color: #6b7280;
  margin-top: .5rem;
}

.ee-error {
  border-color: #dc3545 !important;
  box-shadow: 0 0 0 6px rgba(220, 53, 69, .1) !important;
}

.ee-actions {
  display: flex;
  gap: 1rem;
  align-items: center;
  justify-content: center;
  margin-top: 2rem;
}

.ee-btn {
  border: none;
  border-radius: 12px;
  padding: 1rem 2rem;
  font-weight: 700;
  cursor: pointer;
  background: linear-gradient(135deg, #0891b2, #0d9488);
  color: #fff;
  box-shadow: 0 10px 25px -10px rgba(99, 102, 241, .6);
  transition: all .2s ease;
  font-size: 1.1rem;
  position: relative;
  overflow: hidden;
}

.ee-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 15px 35px -10px rgba(99, 102, 241, .8);
}

.ee-btn:active {
  transform: translateY(0);
}

.ee-btn-secondary {
  background: #f3f4f6;
  color: #374151;
  box-shadow: 0 10px 25px -10px rgba(0, 0, 0, .15);
}

.ee-btn-secondary:hover {
  background: #e5e7eb;
}

.ee-muted {
  color: #6b7280;
  font-size: .95rem;
}

.ee-note {
  background: linear-gradient(135deg, #f8fafc, #f1f5f9);
  border: 2px solid #e2e8f0;
  border-radius: 16px;
  padding: 1.5rem;
  margin-top: 2rem;
}

.ee-note h4 {
  margin: 0 0 .75rem;
  font-weight: 700;
  color: #1e293b;
}

.ee-badge {
  display: inline-flex;
  align-items: center;
  gap: .5rem;
  background: linear-gradient(135deg, #eef2ff, #e0e7ff);
  color: #4338ca;
  border: 2px solid #c7d2fe;
  font-size: .85rem;
  padding: .5rem .75rem;
  border-radius: 20px;
  font-weight: 800;
}

.ee-alert {
  border-radius: 12px;
  padding: 1rem 1.25rem;
  margin-bottom: 1.5rem;
}

.ee-alert-danger {
  background: #fef2f2;
  color: #991b1b;
  border: 2px solid #fecaca;
}

.ee-field {
  display: flex;
  flex-direction: column;
}

/* File upload styles */
.ee-file-upload {
  position: relative;
  overflow: hidden;
  display: inline-block;
  width: 100%;
}

.ee-file-input {
  position: absolute;
  left: -9999px;
}

.ee-file-label {
  display: flex;
  align-items: center;
  gap: .75rem;
  padding: 1rem;
  border: 2px dashed #d1d5db;
  border-radius: 12px;
  background: #f9fafb;
  cursor: pointer;
  transition: all .2s ease;
}

.ee-file-label:hover {
  border-color: #0891b2;
  background: #f0f4ff;
}

.ee-file-preview {
  margin-top: 1rem;
  padding: 1rem;
  background: #f8fafc;
  border-radius: 12px;
  border: 1px solid #e2e8f0;
}

.ee-file-preview img {
  max-width: 100%;
  max-height: 200px;
  border-radius: 8px;
  object-fit: cover;
}

.ee-remove-file {
  background: #ef4444;
  color: white;
  border: none;
  padding: .5rem .75rem;
  border-radius: 8px;
  cursor: pointer;
  margin-top: .5rem;
}

/* Price section styles */
.ee-price-section {
  background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
  border: 2px solid #bae6fd;
  border-radius: 16px;
  padding: 1.5rem;
  margin-top: 1rem;
  position: relative;
}

.ee-price-section::before {
  content: '💰';
  position: absolute;
  top: -12px;
  left: 20px;
  background: white;
  padding: 0 8px;
  font-size: 1.2rem;
}

.ee-price-toggle {
  display: flex;
  align-items: center;
  gap: .75rem;
  margin-bottom: 1rem;
}

.ee-toggle {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 24px;
}

.ee-toggle input {
  opacity: 0;
  width: 0;
  height: 0;
}

.ee-slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: #ccc;
  transition: .3s;
  border-radius: 24px;
  box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
}

.ee-slider:before {
  position: absolute;
  content: "";
  height: 20px;
  width: 20px;
  left: 2px;
  bottom: 2px;
  background: white;
  transition: .3s;
  border-radius: 50%;
}

input:checked + .ee-slider {
  background: linear-gradient(135deg, #0891b2, #0d9488);
  box-shadow: 0 0 10px rgba(99, 102, 241, 0.3);
}

input:checked + .ee-slider:before {
  transform: translateX(26px);
}

/* Section headers */
.ee-section {
  border-left: 4px solid #0891b2;
  padding-left: 1rem;
  margin: 2rem 0 1rem;
  position: relative;
}

.ee-section::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 4px;
  background: linear-gradient(135deg, #0891b2, #0d9488);
  border-radius: 2px;
}

.ee-section h3 {
  margin: 0;
  color: #1e293b;
  font-weight: 700;
}

.ee-section p {
  margin: .25rem 0 0;
  color: #64748b;
  font-size: .9rem;
}

/* Form progress */
.form-progress {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 3px;
  background: rgba(99, 102, 241, 0.1);
  z-index: 1000;
}

.form-progress-bar {
  height: 100%;
  background: linear-gradient(135deg, #0891b2, #0d9488);
  width: 0%;
  transition: width 0.3s ease;
}

/* Success state animations */
.field-valid .ee-input, .field-valid .ee-textarea, .field-valid .ee-select {
  border-left: 4px solid var(--success-color);
  border-color: #10b981;
}

.field-error .ee-input, .field-error .ee-textarea, .field-error .ee-select {
  border-left: 4px solid var(--error-color);
  border-color: #ef4444;
}

/* Loading states */
.ee-btn.loading {
  pointer-events: none;
  opacity: 0.7;
}

.ee-btn.loading::after {
  content: '';
  position: absolute;
  width: 16px;
  height: 16px;
  margin: auto;
  border: 2px solid transparent;
  border-top-color: #ffffff;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

@keyframes spin {
  0% { transform: translate(-50%, -50%) rotate(0deg); }
  100% { transform: translate(-50%, -50%) rotate(360deg); }
}

/* Animations */
@keyframes slideDown {
  from { opacity: 0; transform: translateY(-20px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes slideUp {
  from { opacity: 1; transform: translateY(0); }
  to { opacity: 0; transform: translateY(-20px); }
}

/* Responsive design */
@media (max-width: 768px) {
  .event-request-container {
    padding: 1rem 0.5rem;
  }
  
  .ee-card {
    margin: 0;
    border-radius: 12px;
  }
  
  .ee-grid-2, .ee-grid-3 {
    grid-template-columns: 1fr !important;
  }
  
  .ee-actions {
    flex-direction: column;
  }
  
  .ee-btn {
    width: 100%;
  }
  
  .ee-title h2 {
    font-size: 1.5rem;
  }
}

/* Accessibility improvements */
.ee-input:focus-visible, .ee-textarea:focus-visible, .ee-select:focus-visible {
  outline: 2px solid var(--accent-color);
  outline-offset: 2px;
}

.ee-btn:focus-visible {
  outline: 2px solid #ffffff;
  outline-offset: 2px;
}
</style>
@endsection

@section('content')
<!-- Form Progress Indicator -->
<div class="form-progress">
  <div class="form-progress-bar" id="progressBar"></div>
</div>

<section class="container event-request-container" style="max-width: 900px; margin: 2rem auto;">
  <style>
    .ee-card{background:#fff;border:1px solid rgba(0,0,0,.06);border-radius:20px;box-shadow:0 20px 40px -15px rgba(0,0,0,.15);padding:clamp(1.5re    {{-- NOTE & HIGHLIGHTS --}}
    <div class="ee-note" aria-live="polite">
      <h4>📝 Review Process & Next Steps</h4>
      <ul style="margin:.75rem 0 0 1.5rem;">
        <li><strong>Review Time:</strong> Events are typically reviewed within 24-48 hours by our team.</li>
        <li><strong>Banner Quality:</strong> High-quality banners get better visibility. Our team may suggest improvements.</li>
        <li><strong>Pricing Flexibility:</strong> You can modify ticket prices and add multiple tiers after approval.</li>
        <li><strong>Promotion:</strong> Approved events are automatically promoted on our platform and social media.</li>
        <li><strong>Analytics:</strong> Track registrations, views, and engagement through your dashboard.</li>
      </ul>
    </div>

    <p class="ee-muted" style="margin-top:1rem;text-align:center;">
      Questions? Contact us at <a href="mailto:support@eventease.local" style="color:#0891b2;font-weight:600;">support@eventease.local</a> 
      or call <strong>+880-1234-567890</strong>
    </p>.ee-title{display:flex;align-items:center;gap:.75rem;margin-bottom:1.5rem}
    .ee-title h2{margin:0;font-weight:800;letter-spacing:.3px;font-size:2rem;background:linear-gradient(135deg,#0891b2,#8b5cf6);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
    .ee-grid{display:grid;gap:1.5rem;grid-template-columns:1fr}
    @media (min-width:720px){.ee-grid-2{grid-template-columns:1fr 1fr}.ee-grid-3{grid-template-columns:1fr 1fr 1fr}}
    .ee-label{display:block;font-weight:600;margin-bottom:.5rem;color:#374151}
    .ee-required{color:#dc3545;margin-left:.25rem}
    .ee-input,.ee-textarea,.ee-select{width:100%;border:2px solid #e5e7eb;background:#fff;color:#111827;border-radius:12px;padding:.875rem 1rem;outline:none;transition:all .2s ease;font-size:.95rem}
    .ee-input:focus,.ee-textarea:focus,.ee-select:focus{border-color:#0891b2;box-shadow:0 0 0 6px rgba(99,102,241,.1);transform:translateY(-1px)}
    .ee-help{font-size:.875rem;color:#6b7280;margin-top:.5rem}
    .ee-error{border-color:#dc3545 !important;box-shadow:0 0 0 6px rgba(220,53,69,.1) !important}
    .ee-actions{display:flex;gap:1rem;align-items:center;justify-content:center;margin-top:2rem}
    .ee-btn{border:none;border-radius:12px;padding:1rem 2rem;font-weight:700;cursor:pointer;background:linear-gradient(135deg,#0891b2,#8b5cf6);color:#fff;box-shadow:0 10px 25px -10px rgba(99,102,241,.6);transition:all .2s ease;font-size:1.1rem}
    .ee-btn:hover{transform:translateY(-2px);box-shadow:0 15px 35px -10px rgba(99,102,241,.8)}
    .ee-btn:active{transform:translateY(0)}
    .ee-btn-secondary{background:#f3f4f6;color:#374151;box-shadow:0 10px 25px -10px rgba(0,0,0,.15)}
    .ee-btn-secondary:hover{background:#e5e7eb}
    .ee-muted{color:#6b7280;font-size:.95rem}
    .ee-note{background:linear-gradient(135deg,#f8fafc,#f1f5f9);border:2px solid #e2e8f0;border-radius:16px;padding:1.5rem;margin-top:2rem}
    .ee-note h4{margin:0 0 .75rem;font-weight:700;color:#1e293b}
    .ee-badge{display:inline-flex;align-items:center;gap:.5rem;background:linear-gradient(135deg,#eef2ff,#e0e7ff);color:#4338ca;border:2px solid #c7d2fe;font-size:.85rem;padding:.5rem .75rem;border-radius:20px;font-weight:800}
    .ee-alert{border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.5rem}
    .ee-alert-danger{background:#fef2f2;color:#991b1b;border:2px solid #fecaca}
    .ee-field{display:flex;flex-direction:column}
    
    /* File upload styles */
    .ee-file-upload{position:relative;overflow:hidden;display:inline-block;width:100%}
    .ee-file-input{position:absolute;left:-9999px}
    .ee-file-label{display:flex;align-items:center;gap:.75rem;padding:1rem;border:2px dashed #d1d5db;border-radius:12px;background:#f9fafb;cursor:pointer;transition:all .2s ease}
    .ee-file-label:hover{border-color:#0891b2;background:#f0f4ff}
    .ee-file-preview{margin-top:1rem;padding:1rem;background:#f8fafc;border-radius:12px;border:1px solid #e2e8f0}
    .ee-file-preview img{max-width:100%;max-height:200px;border-radius:8px;object-fit:cover}
    .ee-remove-file{background:#ef4444;color:white;border:none;padding:.5rem .75rem;border-radius:8px;cursor:pointer;margin-top:.5rem}
    
    /* Price section styles */
    .ee-price-section{background:linear-gradient(135deg,#f0f9ff,#e0f2fe);border:2px solid #bae6fd;border-radius:16px;padding:1.5rem;margin-top:1rem}
    .ee-price-toggle{display:flex;align-items:center;gap:.75rem;margin-bottom:1rem}
    .ee-toggle{position:relative;display:inline-block;width:50px;height:24px}
    .ee-toggle input{opacity:0;width:0;height:0}
    .ee-slider{position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;background:#ccc;transition:.3s;border-radius:24px}
    .ee-slider:before{position:absolute;content:"";height:20px;width:20px;left:2px;bottom:2px;background:white;transition:.3s;border-radius:50%}
    input:checked + .ee-slider{background:#0891b2}
    input:checked + .ee-slider:before{transform:translateX(26px)}
    
    /* Section headers */
    .ee-section{border-left:4px solid #0891b2;padding-left:1rem;margin:2rem 0 1rem}
    .ee-section h3{margin:0;color:#1e293b;font-weight:700}
    .ee-section p{margin:.25rem 0 0;color:#64748b;font-size:.9rem}
  </style>

    <div class="ee-card">
    <div class="ee-title">
      <span class="ee-badge">🎉 New Request</span>
      <h2>Create Your Event</h2>
    </div>
    <p class="ee-muted" style="margin-top:-.25rem;">
      Fill out the details below to submit your event for review. Fields marked with <span class="ee-required">*</span> are required.
    </p>

    @if ($errors->any())
      <div class="ee-alert ee-alert-danger" role="alert">
        @php($count = $errors->count())
        <strong>⚠️ We found {{ $count }} {{ $count === 1 ? 'issue' : 'issues' }}:</strong>
        <ul style="margin:.75rem 0 0 1.5rem;">
          @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('events.request.store') }}" enctype="multipart/form-data" novalidate id="eventForm">
      @csrf

      <!-- Basic Information Section -->
      <div class="ee-section">
        <h3>📋 Basic Information</h3>
        <p>Essential details about your event</p>
      </div>

      <div class="ee-grid">
        {{-- Title --}}
        @php($titleHasError = $errors->has('title'))
        <div class="ee-field">
          <label class="ee-label" for="title">Event Title <span class="ee-required">*</span></label>
          <input
            id="title"
            type="text"
            name="title"
            required
            class="ee-input {{ $titleHasError ? 'ee-error' : '' }}"
            value="{{ old('title') }}"
            aria-invalid="{{ $titleHasError ? 'true' : 'false' }}"
            placeholder="e.g., Tech Summit 2025, Music Festival, Workshop Series">
          <div class="ee-help">Keep it catchy and descriptive (recommended: 50-80 characters).</div>
          @if($titleHasError)
            <div class="ee-help" style="color:#b91c1c;">{{ $errors->first('title') }}</div>
          @endif
        </div>

        {{-- Description --}}
        @php($descHasError = $errors->has('description'))
        <div class="ee-field">
          <label class="ee-label" for="description">Event Description</label>
          <textarea
            id="description"
            name="description"
            rows="6"
            class="ee-textarea {{ $descHasError ? 'ee-error' : '' }}"
            placeholder="Describe your event in detail. What can attendees expect? Who are the speakers? What's the agenda? Include any special highlights or unique features.">{{ old('description') }}</textarea>
          <div class="ee-help">Help attendees understand what makes your event special and worth attending.</div>
          @if($descHasError)
            <div class="ee-help" style="color:#b91c1c;">{{ $errors->first('description') }}</div>
          @endif
        </div>
      </div>

      <!-- Event Banner Section -->
      <div class="ee-section">
        <h3>🖼️ Event Banner</h3>
        <p>Upload an eye-catching banner to attract attendees</p>
      </div>

      <div class="ee-grid">
        @php($bannerHasError = $errors->has('banner'))
        <div class="ee-field">
          <label class="ee-label" for="banner">Event Banner Image</label>
          <div class="ee-file-upload">
            <input 
              type="file" 
              id="banner" 
              name="banner" 
              accept="image/*" 
              class="ee-file-input {{ $bannerHasError ? 'ee-error' : '' }}"
              onchange="previewImage(this)">
            <label for="banner" class="ee-file-label">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                <circle cx="8.5" cy="8.5" r="1.5"/>
                <polyline points="21,15 16,10 5,21"/>
              </svg>
              <span>Click to upload banner image or drag & drop</span>
            </label>
          </div>
          <div id="banner-preview" class="ee-file-preview" style="display: none;">
            <img id="preview-img" src="" alt="Banner preview">
            <button type="button" class="ee-remove-file" onclick="removeImage()">Remove Image</button>
          </div>
          <div class="ee-help">Recommended size: 1200x600px. Supported formats: JPG, PNG, WebP (max 5MB)</div>
          @if($bannerHasError)
            <div class="ee-help" style="color:#b91c1c;">{{ $errors->first('banner') }}</div>
          @endif
        </div>
      </div>

      <!-- Location & Timing Section -->
      <div class="ee-section">
        <h3>📍 Location & Timing</h3>
        <p>When and where will your event take place</p>
      </div>

      <div class="ee-grid">
        {{-- Location --}}
        @php($locHasError = $errors->has('location'))
        <div class="ee-field">
          <label class="ee-label" for="location">Location</label>
          <input
            id="location"
            type="text"
            name="location"
            class="ee-input {{ $locHasError ? 'ee-error' : '' }}"
            value="{{ old('location') }}"
            placeholder="e.g., Dhaka Conference Hall A, Virtual (Zoom), Dhaka University Auditorium">
          <div class="ee-help">Include specific venue name, address, or mention if it's virtual.</div>
          @if($locHasError)
            <div class="ee-help" style="color:#b91c1c;">{{ $errors->first('location') }}</div>
          @endif
        </div>

        {{-- Starts / Ends --}}
        <div class="ee-grid ee-grid-2">
          @php($startHasError = $errors->has('starts_at'))
          <div class="ee-field">
            <label class="ee-label" for="starts_at">Event Starts <span class="ee-required">*</span></label>
            <input
              id="starts_at"
              type="datetime-local"
              name="starts_at"
              required
              class="ee-input {{ $startHasError ? 'ee-error' : '' }}"
              value="{{ old('starts_at') }}"
              min="{{ now()->format('Y-m-d\TH:i') }}">
            <div class="ee-help">Local time zone. Must be a future date and time.</div>
            @if($startHasError)
              <div class="ee-help" style="color:#b91c1c;">{{ $errors->first('starts_at') }}</div>
            @endif
          </div>

          @php($endHasError = $errors->has('ends_at'))
          <div class="ee-field">
            <label class="ee-label" for="ends_at">Event Ends</label>
            <input
              id="ends_at"
              type="datetime-local"
              name="ends_at"
              class="ee-input {{ $endHasError ? 'ee-error' : '' }}"
              value="{{ old('ends_at') }}"
              min="{{ now()->format('Y-m-d\TH:i') }}">
            <div class="ee-help">Optional, but helps with scheduling and planning.</div>
            @if($endHasError)
              <div class="ee-help" style="color:#b91c1c;">{{ $errors->first('ends_at') }}</div>
            @endif
          </div>
        </div>

        {{-- Capacity --}}
        @php($capHasError = $errors->has('capacity'))
        <div class="ee-field">
          <label class="ee-label" for="capacity">Expected Capacity</label>
          <input
            id="capacity"
            type="number"
            name="capacity"
            min="1"
            max="10000"
            class="ee-input {{ $capHasError ? 'ee-error' : '' }}"
            value="{{ old('capacity') }}"
            placeholder="e.g., 150">
          <div class="ee-help">Maximum number of attendees. Leave blank if unlimited or unknown.</div>
          @if($capHasError)
            <div class="ee-help" style="color:#b91c1c;">{{ $errors->first('capacity') }}</div>
          @endif
        </div>
      </div>

      <!-- Pricing Section -->
      <div class="ee-section">
        <h3>💰 Ticket Pricing</h3>
        <p>Configure ticket pricing and payment options</p>
      </div>

      <div class="ee-price-section">
        <div class="ee-price-toggle">
          <label class="ee-toggle">
            <input type="checkbox" id="is_paid" name="is_paid" value="1" {{ old('is_paid') ? 'checked' : '' }} onchange="togglePriceFields()">
            <span class="ee-slider"></span>
          </label>
          <label for="is_paid" style="font-weight: 600; cursor: pointer;">This is a paid event</label>
        </div>

        <div id="price-fields" style="display: {{ old('is_paid') ? 'block' : 'none' }};">
          <div class="ee-grid ee-grid-3">
            @php($priceHasError = $errors->has('price'))
            <div class="ee-field">
              <label class="ee-label" for="price">Ticket Price</label>
              <div style="position: relative;">
                <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #6b7280; font-weight: 600;">৳</span>
                <input
                  id="price"
                  type="number"
                  name="price"
                  min="0"
                  step="0.01"
                  class="ee-input {{ $priceHasError ? 'ee-error' : '' }}"
                  style="padding-left: 2.5rem;"
                  value="{{ old('price') }}"
                  placeholder="0.00">
              </div>
              @if($priceHasError)
                <div class="ee-help" style="color:#b91c1c;">{{ $errors->first('price') }}</div>
              @endif
            </div>

            <div class="ee-field">
              <label class="ee-label" for="currency">Currency</label>
              <select id="currency" name="currency" class="ee-select" readonly>
                <option value="BDT" selected>BDT (৳ - Bangladeshi Taka)</option>
              </select>
              <div class="ee-help">Currently supporting Bangladeshi Taka only</div>
            </div>

            <div class="ee-field">
              <label class="ee-toggle" style="margin-top: 1.75rem;">
                <input type="checkbox" name="allow_pay_later" value="1" {{ old('allow_pay_later', '1') ? 'checked' : '' }}>
                <span class="ee-slider"></span>
              </label>
              <label style="margin-left: 0.5rem; font-weight: 600;">Allow pay later</label>
              <div class="ee-help">Let attendees register now and pay at the venue</div>
            </div>
          </div>
        </div>
      </div>

      <div class="ee-actions">
        <button type="button" class="ee-btn ee-btn-secondary" onclick="resetForm()">
          🔄 Reset Form
        </button>
        <button class="ee-btn" type="submit">
          🚀 Submit Event Request
        </button>
      </div>
    </form>

    {{-- NOTE & HIGHLIGHTS --}}
    <div class="ee-note" aria-live="polite">
      <h4>Note & Highlights</h4>
      <ul style="margin:.25rem 0 0 1.2rem;">
        @if (Route::has('contact'))
          <li><strong>Event Banner:</strong> Please <a href="{{ route('contact') }}">contact us</a> to add or update the banner artwork.</li>
        @else
          <li><strong>Event Banner:</strong> Please <a href="mailto:mamun15-5451@diu.edu.bd">contact us</a> to add or update the banner artwork.</li>
        @endif
        <li><strong>Ticket Price:</strong> Reach out to finalize ticket tiers and currency.</li>
        <li><strong>Confirmation:</strong> Your event will be reviewed. We’ll confirm details via email before it goes live.</li>
      </ul>
    </div>
 
    <p class="ee-muted" style="margin-top:.75rem;">
      Need help right now? Email <a href="mailto:mamun15-5451@diu.edu.bd">mamun15-5451@diu.edu.bd</a>.
    </p>
  </div>

  <script>
    // Enhanced functionality for the event request form
    
    // Form progress tracking
    function updateProgress() {
      const form = document.getElementById('eventForm');
      const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
      const filled = Array.from(inputs).filter(input => input.value.trim() !== '').length;
      const progress = (filled / inputs.length) * 100;
      document.getElementById('progressBar').style.width = progress + '%';
    }

    // Image preview functionality with drag and drop
    function previewImage(input) {
      const preview = document.getElementById('banner-preview');
      const previewImg = document.getElementById('preview-img');
      
      if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validate file size (5MB)
        if (file.size > 5 * 1024 * 1024) {
          alert('File size too large. Please choose an image under 5MB.');
          input.value = '';
          return;
        }
        
        // Validate file type
        const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
        if (!validTypes.includes(file.type)) {
          alert('Invalid file type. Please choose a JPEG, PNG, GIF, or WebP image.');
          input.value = '';
          return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
          previewImg.src = e.target.result;
          preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
        
        // Mark field as valid
        input.closest('.ee-field').classList.add('field-valid');
      }
    }

    function removeImage() {
      document.getElementById('banner').value = '';
      document.getElementById('banner-preview').style.display = 'none';
      document.getElementById('banner').closest('.ee-field').classList.remove('field-valid');
      updateProgress();
    }

    // Enhanced drag and drop functionality
    function setupDragAndDrop() {
      const fileLabel = document.querySelector('.ee-file-label');
      const fileInput = document.getElementById('banner');
      
      ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        fileLabel.addEventListener(eventName, preventDefaults, false);
      });
      
      function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
      }
      
      ['dragenter', 'dragover'].forEach(eventName => {
        fileLabel.addEventListener(eventName, highlight, false);
      });
      
      ['dragleave', 'drop'].forEach(eventName => {
        fileLabel.addEventListener(eventName, unhighlight, false);
      });
      
      function highlight(e) {
        fileLabel.style.borderColor = '#0891b2';
        fileLabel.style.backgroundColor = '#f0f4ff';
      }
      
      function unhighlight(e) {
        fileLabel.style.borderColor = '#d1d5db';
        fileLabel.style.backgroundColor = '#f9fafb';
      }
      
      fileLabel.addEventListener('drop', handleDrop, false);
      
      function handleDrop(e) {
        const files = e.dataTransfer.files;
        if (files.length > 0) {
          fileInput.files = files;
          previewImage(fileInput);
        }
      }
    }

    // Toggle price fields with animation
    function togglePriceFields() {
      const checkbox = document.getElementById('is_paid');
      const priceFields = document.getElementById('price-fields');
      const priceInput = document.getElementById('price');
      
      if (checkbox.checked) {
        priceFields.style.display = 'block';
        priceFields.style.animation = 'slideDown 0.3s ease';
        priceInput.required = true;
        setTimeout(() => {
          priceInput.focus();
        }, 300);
      } else {
        priceFields.style.animation = 'slideUp 0.3s ease';
        setTimeout(() => {
          priceFields.style.display = 'none';
        }, 300);
        priceInput.required = false;
        priceInput.value = '';
        priceInput.closest('.ee-field').classList.remove('field-valid');
      }
      updateProgress();
    }

    // Reset form with confirmation
    function resetForm() {
      if (confirm('Are you sure you want to reset the form? All entered data will be lost.')) {
        document.getElementById('eventForm').reset();
        removeImage();
        togglePriceFields();
        updateProgress();
        
        // Reset all field states
        document.querySelectorAll('.field-valid, .field-error').forEach(field => {
          field.classList.remove('field-valid', 'field-error');
        });
        
        // Show success message
        showNotification('Form reset successfully!', 'info');
      }
    }

    // Auto-update end time when start time changes
    function setupTimeHelpers() {
      document.getElementById('starts_at').addEventListener('change', function() {
        const startTime = new Date(this.value);
        const endTimeInput = document.getElementById('ends_at');
        
        if (startTime && !endTimeInput.value) {
          const endTime = new Date(startTime.getTime() + (2 * 60 * 60 * 1000));
          endTimeInput.value = endTime.toISOString().slice(0, 16);
          endTimeInput.closest('.ee-field').classList.add('field-valid');
        }
        updateProgress();
      });
    }

    // Real-time validation
    function setupRealTimeValidation() {
      const inputs = document.querySelectorAll('.ee-input, .ee-textarea, .ee-select');
      
      inputs.forEach(input => {
        input.addEventListener('blur', function() {
          validateField(this);
          updateProgress();
        });
        
        input.addEventListener('input', function() {
          if (this.closest('.ee-field').classList.contains('field-error')) {
            validateField(this);
          }
          updateProgress();
        });
      });
    }

    function validateField(field) {
      const fieldContainer = field.closest('.ee-field');
      let isValid = true;
      
      // Check if required field is empty
      if (field.hasAttribute('required') && field.value.trim() === '') {
        isValid = false;
      }
      
      // Specific validations
      if (field.name === 'title' && field.value.length > 0 && field.value.length < 5) {
        isValid = false;
      }
      
      if (field.name === 'starts_at') {
        const startTime = new Date(field.value);
        if (startTime <= new Date()) {
          isValid = false;
        }
      }
      
      if (field.name === 'ends_at' && field.value) {
        const startTime = new Date(document.getElementById('starts_at').value);
        const endTime = new Date(field.value);
        if (endTime <= startTime) {
          isValid = false;
        }
      }
      
      if (field.name === 'price') {
        const isPaid = document.getElementById('is_paid').checked;
        if (isPaid && (!field.value || parseFloat(field.value) <= 0)) {
          isValid = false;
        }
      }
      
      // Update field state
      fieldContainer.classList.remove('field-valid', 'field-error');
      if (field.value.trim() !== '') {
        fieldContainer.classList.add(isValid ? 'field-valid' : 'field-error');
      }
      
      return isValid;
    }

    // Enhanced form submission with loading state
    function setupFormSubmission() {
      document.getElementById('eventForm').addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        const startTime = new Date(document.getElementById('starts_at').value);
        const endTime = new Date(document.getElementById('ends_at').value);
        const isPaid = document.getElementById('is_paid').checked;
        const price = document.getElementById('price').value;
        
        // Validate all fields
        let isFormValid = true;
        const inputs = this.querySelectorAll('.ee-input, .ee-textarea, .ee-select');
        inputs.forEach(input => {
          if (!validateField(input)) {
            isFormValid = false;
          }
        });
        
        // Check specific validations
        if (startTime <= new Date()) {
          showNotification('Start time must be in the future.', 'error');
          isFormValid = false;
        }
        
        if (endTime && endTime <= startTime) {
          showNotification('End time must be after start time.', 'error');
          isFormValid = false;
        }
        
        if (isPaid && (!price || parseFloat(price) <= 0)) {
          showNotification('Please set a valid price for paid events.', 'error');
          isFormValid = false;
        }
        
        if (!isFormValid) {
          e.preventDefault();
          return false;
        }
        
        // Add loading state
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span style="opacity: 0;">🚀 Submit Event Request</span>';
        
        showNotification('Submitting your event request...', 'info');
      });
    }

    // Notification system
    function showNotification(message, type = 'info') {
      const notification = document.createElement('div');
      notification.className = `notification notification-${type}`;
      notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'error' ? '#ef4444' : type === 'success' ? '#10b981' : '#0891b2'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.25);
        z-index: 1000;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        max-width: 300px;
      `;
      notification.textContent = message;
      
      document.body.appendChild(notification);
      
      setTimeout(() => {
        notification.style.transform = 'translateX(0)';
      }, 100);
      
      setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
          document.body.removeChild(notification);
        }, 300);
      }, 3000);
    }

    // Initialize all functionality
    document.addEventListener('DOMContentLoaded', function() {
      updateProgress();
      togglePriceFields();
      setupDragAndDrop();
      setupTimeHelpers();
      setupRealTimeValidation();
      setupFormSubmission();
      
      // Add CSS animations
      const style = document.createElement('style');
      style.textContent = `
        @keyframes slideDown {
          from { opacity: 0; transform: translateY(-20px); }
          to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideUp {
          from { opacity: 1; transform: translateY(0); }
          to { opacity: 0; transform: translateY(-20px); }
        }
      `;
      document.head.appendChild(style);
      
      showNotification('Form loaded successfully! Fill out the details to create your event.', 'success');
    });
  </script>
</section>
@endsection
