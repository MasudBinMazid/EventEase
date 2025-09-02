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
          </div>
        </div>

        <!-- Event Type & Pricing Section -->
        <div class="form-section">
          <h4 class="section-title">Event Type & Pricing</h4>
          
          <div class="form-group">
            <label class="form-label" for="event_type">Event Type</label>
            <select name="event_type" id="event_type" class="form-input" onchange="togglePricingSection()">
              <option value="free" {{ old('event_type', $event->event_type) == 'free' ? 'selected' : '' }}>Free Event</option>
              <option value="paid" {{ old('event_type', $event->event_type) == 'paid' ? 'selected' : '' }}>Paid Event</option>
            </select>
            <div class="form-hint">Choose whether this event is free or requires payment</div>
            @error('event_type')
              <div class="form-error">{{ $message }}</div>
            @enderror
          </div>

          <!-- Legacy Price Field (for backward compatibility) -->
          <div class="form-group" id="legacy-price-section" style="display: none;">
            <label class="form-label" for="price">Base Price (Legacy)</label>
            <input type="number" name="price" id="price" class="form-input" value="{{ old('price', $event->price) }}" min="0" step="0.01" placeholder="0.00">
            <div class="form-hint">This field is kept for compatibility. Use ticket types below for detailed pricing.</div>
            @error('price')
              <div class="form-error">{{ $message }}</div>
            @enderror
          </div>

          <!-- Ticket Types Section (only for paid events) -->
          <div id="ticket-types-section" style="display: none;">
            <div class="form-group">
              <label class="form-label">Ticket Types</label>
              <div class="form-hint">Add different ticket types with their own pricing and availability</div>
              
              <div id="ticket-types-container">
                @if($event->ticketTypes->count() > 0)
                  @foreach($event->ticketTypes as $index => $ticketType)
                    <div class="ticket-type-item" data-index="{{ $index + 1 }}">
                      <div class="ticket-type-header">
                        <span>Ticket Type #{{ $index + 1 }}</span>
                        <button type="button" class="ticket-type-remove" onclick="removeTicketType({{ $index + 1 }})">
                          Remove
                        </button>
                      </div>
                      
                      <div class="ticket-type-fields">
                        <div class="ticket-type-field">
                          <label>Name <span style="color: red;">*</span></label>
                          <input type="text" name="ticket_types[{{ $index + 1 }}][name]" value="{{ old('ticket_types.'.($index + 1).'.name', $ticketType->name) }}" placeholder="e.g., Early Bird, VIP, General" required>
                        </div>
                        
                        <div class="ticket-type-field">
                          <label>Price (৳) <span style="color: red;">*</span></label>
                          <input type="number" name="ticket_types[{{ $index + 1 }}][price]" value="{{ old('ticket_types.'.($index + 1).'.price', $ticketType->price) }}" min="0" step="0.01" placeholder="0.00" required>
                        </div>
                        
                        <div class="ticket-type-field">
                          <label>Quantity Available</label>
                          <input type="number" name="ticket_types[{{ $index + 1 }}][quantity_available]" value="{{ old('ticket_types.'.($index + 1).'.quantity_available', $ticketType->quantity_available) }}" min="1" placeholder="Leave empty for unlimited">
                        </div>
                        
                        <div class="ticket-type-field">
                          <label>Status</label>
                          <select name="ticket_types[{{ $index + 1 }}][status]">
                            <option value="available" {{ old('ticket_types.'.($index + 1).'.status', $ticketType->status) == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="sold_out" {{ old('ticket_types.'.($index + 1).'.status', $ticketType->status) == 'sold_out' ? 'selected' : '' }}>Sold Out</option>
                          </select>
                        </div>
                        
                        <div class="ticket-type-field full-width">
                          <label>Description</label>
                          <textarea name="ticket_types[{{ $index + 1 }}][description]" placeholder="Describe what's included with this ticket type">{{ old('ticket_types.'.($index + 1).'.description', $ticketType->description) }}</textarea>
                        </div>
                        
                        <input type="hidden" name="ticket_types[{{ $index + 1 }}][id]" value="{{ $ticketType->id }}">
                        <input type="hidden" name="ticket_types[{{ $index + 1 }}][sort_order]" value="{{ $index + 1 }}">
                      </div>
                    </div>
                  @endforeach
                @endif
              </div>
              
              <button type="button" id="add-ticket-type" class="btn btn-outline">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M12 5v14M5 12h14"/>
                </svg>
                Add Ticket Type
              </button>
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

          <div class="form-group">
            <label class="form-label" for="event_status">Event Status</label>
            @php $status = old('event_status', $event->event_status ?? 'available'); @endphp
            <select name="event_status" id="event_status" class="form-input {{ $errors->has('event_status') ? 'error' : '' }}">
              <option value="available" {{ $status === 'available' ? 'selected' : '' }}>Available</option>
              <option value="limited_sell" {{ $status === 'limited_sell' ? 'selected' : '' }}>Limited Sell</option>
              <option value="sold_out" {{ $status === 'sold_out' ? 'selected' : '' }}>Sold Out</option>
            </select>
            @error('event_status')
              <div class="form-error">{{ $message }}</div>
            @enderror
          </div>
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
  
  /* Ticket Types Styles */
  .ticket-type-item {
    border: 1px solid var(--border);
    border-radius: 12px;
    margin-bottom: 1rem;
    background: var(--surface);
  }
  
  .ticket-type-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid var(--border-light);
    background: var(--background);
    border-radius: 12px 12px 0 0;
    font-weight: 600;
  }
  
  .ticket-type-remove {
    background: var(--danger);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.85rem;
    transition: all 0.2s ease;
  }
  
  .ticket-type-remove:hover {
    background: var(--danger-hover);
    transform: translateY(-1px);
  }
  
  .ticket-type-fields {
    padding: 1rem;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
  }
  
  .ticket-type-field {
    display: flex;
    flex-direction: column;
  }
  
  .ticket-type-field.full-width {
    grid-column: 1 / -1;
  }
  
  .ticket-type-field label {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--text);
  }
  
  .ticket-type-field input,
  .ticket-type-field select,
  .ticket-type-field textarea {
    padding: 0.75rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: 0.95rem;
    transition: all 0.2s ease;
  }
  
  .ticket-type-field input:focus,
  .ticket-type-field select:focus,
  .ticket-type-field textarea:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px var(--primary-alpha);
  }
  
  .ticket-type-field textarea {
    resize: vertical;
    min-height: 80px;
  }
</style>

<script>
let ticketTypeCounter = {{ $event->ticketTypes->count() }};

function togglePricingSection() {
  const eventType = document.getElementById('event_type').value;
  const legacyPriceSection = document.getElementById('legacy-price-section');
  const ticketTypesSection = document.getElementById('ticket-types-section');
  
  if (eventType === 'paid') {
    legacyPriceSection.style.display = 'block';
    ticketTypesSection.style.display = 'block';
    
    // Add a default ticket type if none exist
    if (ticketTypeCounter === 0) {
      addTicketType();
    }
  } else {
    legacyPriceSection.style.display = 'none';
    ticketTypesSection.style.display = 'none';
  }
}

function addTicketType() {
  ticketTypeCounter++;
  const container = document.getElementById('ticket-types-container');
  
  const ticketTypeHtml = `
    <div class="ticket-type-item" data-index="${ticketTypeCounter}">
      <div class="ticket-type-header">
        <span>Ticket Type #${ticketTypeCounter}</span>
        <button type="button" class="ticket-type-remove" onclick="removeTicketType(${ticketTypeCounter})">
          Remove
        </button>
      </div>
      
      <div class="ticket-type-fields">
        <div class="ticket-type-field">
          <label>Name <span style="color: red;">*</span></label>
          <input type="text" name="ticket_types[${ticketTypeCounter}][name]" placeholder="e.g., Early Bird, VIP, General" required>
        </div>
        
        <div class="ticket-type-field">
          <label>Price (৳) <span style="color: red;">*</span></label>
          <input type="number" name="ticket_types[${ticketTypeCounter}][price]" min="0" step="0.01" placeholder="0.00" required>
        </div>
        
        <div class="ticket-type-field">
          <label>Quantity Available</label>
          <input type="number" name="ticket_types[${ticketTypeCounter}][quantity_available]" min="1" placeholder="Leave empty for unlimited">
        </div>
        
        <div class="ticket-type-field">
          <label>Status</label>
          <select name="ticket_types[${ticketTypeCounter}][status]">
            <option value="available">Available</option>
            <option value="sold_out">Sold Out</option>
          </select>
        </div>
        
        <div class="ticket-type-field full-width">
          <label>Description</label>
          <textarea name="ticket_types[${ticketTypeCounter}][description]" placeholder="Describe what's included with this ticket type"></textarea>
        </div>
        
        <input type="hidden" name="ticket_types[${ticketTypeCounter}][sort_order]" value="${ticketTypeCounter}">
      </div>
    </div>
  `;
  
  container.insertAdjacentHTML('beforeend', ticketTypeHtml);
}

function removeTicketType(index) {
  const ticketType = document.querySelector(`[data-index="${index}"]`);
  if (ticketType) {
    ticketType.remove();
  }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
  togglePricingSection();
  
  // Add event listener for add ticket type button
  document.getElementById('add-ticket-type').addEventListener('click', addTicketType);
});
</script>
@endsection
