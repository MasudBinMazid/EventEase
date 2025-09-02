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
              <label class="form-label" for="event_status">Event Status</label>
              <select name="event_status" id="event_status" class="form-input">
                <option value="available" {{ old('event_status', 'available') == 'available' ? 'selected' : '' }}>Available</option>
                <option value="limited_sell" {{ old('event_status') == 'limited_sell' ? 'selected' : '' }}>Limited Sell</option>
                <option value="sold_out" {{ old('event_status') == 'sold_out' ? 'selected' : '' }}>Sold Out</option>
              </select>
              @error('event_status')
                <div class="form-error">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>

        <div class="form-section">
          <h4 class="section-title">Event Type & Pricing</h4>
          
          <div class="form-group">
            <label class="form-label" for="event_type">Event Type</label>
            <select name="event_type" id="event_type" class="form-input" onchange="togglePricingSection()">
              <option value="free" {{ old('event_type', 'free') == 'free' ? 'selected' : '' }}>Free Event</option>
              <option value="paid" {{ old('event_type') == 'paid' ? 'selected' : '' }}>Paid Event</option>
            </select>
            <div class="form-hint">Choose whether this event is free or requires payment</div>
            @error('event_type')
              <div class="form-error">{{ $message }}</div>
            @enderror
          </div>

          <!-- Legacy Price Field (for backward compatibility) -->
          <div class="form-group" id="legacy-price-section" style="display: none;">
            <label class="form-label" for="price">Base Price (Legacy)</label>
            <input type="number" name="price" id="price" class="form-input" value="{{ old('price', 0) }}" min="0" step="0.01" placeholder="0.00">
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
                <!-- Ticket type templates will be added here dynamically -->
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

<style>
.ticket-type-item {
  border: 1px solid var(--border);
  border-radius: 8px;
  padding: 1rem;
  margin-bottom: 1rem;
  background: var(--surface);
}

.ticket-type-header {
  display: flex;
  justify-content: between;
  align-items: center;
  margin-bottom: 1rem;
  font-weight: 600;
}

.ticket-type-remove {
  background: #dc2626;
  color: white;
  border: none;
  border-radius: 4px;
  padding: 0.5rem;
  cursor: pointer;
  font-size: 0.875rem;
}

.ticket-type-remove:hover {
  background: #b91c1c;
}

.ticket-type-fields {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.ticket-type-field {
  display: flex;
  flex-direction: column;
}

.ticket-type-field label {
  font-weight: 500;
  margin-bottom: 0.5rem;
  color: var(--text);
}

.ticket-type-field input,
.ticket-type-field select,
.ticket-type-field textarea {
  padding: 0.75rem;
  border: 1px solid var(--border);
  border-radius: 6px;
  background: white;
}

.ticket-type-field textarea {
  resize: vertical;
  min-height: 80px;
}

.full-width {
  grid-column: 1 / -1;
}
</style>

<script>
let ticketTypeCounter = 0;

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
  
  document.getElementById('add-ticket-type').addEventListener('click', addTicketType);
});
</script>

@endsection
