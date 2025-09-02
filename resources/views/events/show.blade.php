@extends('layouts.app')

@section('title', $event->title)

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/events.css') }}">

<div class="event-details-container">
    <!-- Event Banner -->
    <div class="event-banner">
        @if($event->banner_url)
            <img src="{{ $event->banner_url }}" alt="{{ $event->title }}" class="banner-image">
        @else
            <div class="banner-placeholder">
                <i class="bi bi-image"></i>
                <p>Event Banner</p>
            </div>
        @endif
        <div class="banner-overlay"></div>
        <div class="banner-content">
            <div class="event-badge">
                @php
                    $now = \Carbon\Carbon::now();
                    $eventStart = $event->starts_at;
                    $eventEnd = $event->ends_at ?? $eventStart->copy()->addHours(3);
                    
                    if ($now->gt($eventEnd)) {
                        $status = 'past';
                        $statusText = 'Event Ended';
                    } elseif ($now->between($eventStart, $eventEnd)) {
                        $status = 'ongoing';
                        $statusText = 'Ongoing';
                    } else {
                        $status = 'upcoming';
                        $statusText = 'Upcoming';
                    }
                @endphp
                <span class="event-status status-{{ $status }}">{{ $statusText }}</span>
            </div>
            
            <div class="event-meta">
                <div class="meta-item">
                    <i class="bi bi-calendar-event"></i>
                    <span>{{ $event->starts_at->format('M d, Y') }}</span>
                </div>
                <div class="meta-item">
                    <i class="bi bi-clock"></i>
                    <span>{{ $event->starts_at->format('h:i A') }}</span>
                </div>
                <div class="meta-item">
                    <i class="bi bi-geo-alt"></i>
                    <span>{{ $event->location }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="event-content">
        <div class="event-info">
            <!-- Event Description -->
            <div class="info-section">
                <h2>About This Event</h2>
                <div class="event-description">
                    {!! nl2br(e($event->description)) !!}
                </div>
            </div>

            <!-- Event Details -->
            <div class="info-section">
                <h2>Event Details</h2>
                <div class="details-grid">
                    <div class="detail-item">
                        <strong>Category:</strong>
                        <span class="category-tag">{{ ucfirst($event->category) }}</span>
                    </div>
                    <div class="detail-item">
                        <strong>Date:</strong>
                        <span>{{ $event->starts_at->format('l, F j, Y') }}</span>
                    </div>
                    <div class="detail-item">
                        <strong>Time:</strong>
                        <span>{{ $event->starts_at->format('g:i A') }}</span>
                    </div>
                    @if($event->ends_at)
                    <div class="detail-item">
                        <strong>End Time:</strong>
                        <span>{{ $event->ends_at->format('M j, Y g:i A') }}</span>
                    </div>
                    @endif
                    <div class="detail-item">
                        <strong>Location:</strong>
                        <span>{{ $event->location }}</span>
                    </div>
                    <div class="detail-item">
                        <strong>Organizer:</strong>
                        <span>{{ $event->creator->name ?? 'Event Organizer' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ticket Purchase Card -->
        <div class="purchase-card">
            <div class="purchase-header">
                <h3>Get Your Ticket</h3>
                <div class="price-display">
                    @if($event->isPaid())
                        @if($event->ticketTypes->count() > 0)
                            <span class="price">{{ $event->price_display }}</span>
                            <span class="price-label">
                                @if($event->minimum_price != $event->maximum_price)
                                    multiple options available
                                @else
                                    per ticket
                                @endif
                            </span>
                        @else
                            <span class="price">৳{{ number_format($event->price, 2) }}</span>
                            <span class="price-label">per ticket</span>
                        @endif
                    @else
                        <span class="price free">Free</span>
                    @endif
                </div>
            </div>

            @if($event->isSoldOut())
                <!-- Sold Out Event -->
                <div class="past-event-notice">
                    <div class="notice-icon">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div class="notice-content">
                        <h4>Event Sold Out</h4>
                        <p>All tickets for this event have been sold. Please check back for any last-minute cancellations.</p>
                    </div>
                </div>
            @elseif($status === 'past')
                <!-- Past Event - No Purchase Option -->
                <div class="past-event-notice">
                    <div class="notice-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div class="notice-content">
                        <h4>Event Has Ended</h4>
                        <p>This event took place on {{ $event->starts_at->format('M j, Y') }}. Ticket sales are no longer available.</p>
                    </div>
                </div>
            @else
                <!-- Active Event - Show Purchase Form -->
                @if($event->isPaid() && $event->ticketTypes->count() > 0)
                    <!-- Multiple Ticket Types Available -->
                    <div class="ticket-types-section">
                        <h4>Available Ticket Types</h4>
                        @foreach($event->availableTicketTypes as $ticketType)
                            <div class="ticket-type-card {{ $ticketType->is_sold_out ? 'sold-out' : '' }}">
                                <div class="ticket-type-info">
                                    <h5>{{ $ticketType->name }}</h5>
                                    @if($ticketType->description)
                                        <p class="ticket-type-description">{{ $ticketType->description }}</p>
                                    @endif
                                    <div class="ticket-type-price">৳{{ number_format($ticketType->price, 2) }}</div>
                                    @if($ticketType->quantity_available)
                                        <div class="ticket-type-availability">
                                            {{ $ticketType->remaining_quantity }} of {{ $ticketType->quantity_available }} remaining
                                        </div>
                                    @endif
                                </div>
                                @if($ticketType->is_sold_out)
                                    <button class="btn btn-disabled" disabled>
                                        <i class="bi bi-x-circle"></i> Sold Out
                                    </button>
                                @else
                                    <form action="{{ route('tickets.start', $event) }}" method="POST" style="display: inline;" onsubmit="handleTicketSelection(this)">
                                        @csrf
                                        <input type="hidden" name="ticket_type_id" value="{{ $ticketType->id }}">
                                        <button type="submit" class="btn btn-primary ticket-select-btn">
                                            <i class="bi bi-ticket"></i> 
                                            <span class="btn-text">Select</span>
                                            <span class="btn-loading" style="display: none;">
                                                <i class="bi bi-hourglass-split"></i> Processing...
                                            </span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Single Ticket Type or Free Event -->
                    <form action="{{ route('tickets.start', $event) }}" method="POST" class="purchase-form">
                        @csrf
                    <div class="form-group">
                        <label for="quantity">Number of Tickets</label>
                        <div class="quantity-selector">
                            <button type="button" class="qty-btn" onclick="changeQuantity(-1)">-</button>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="10" readonly>
                            <button type="button" class="qty-btn" onclick="changeQuantity(1)">+</button>
                        </div>
                    </div>

                    <div class="total-section">
                        <div class="total-row">
                            <span>Subtotal:</span>
                            <span id="subtotal">
                                @if($event->isPaid())
                                    ৳{{ number_format($event->price, 2) }}
                                @else
                                    Free
                                @endif
                            </span>
                        </div>
                        <div class="total-row total-final">
                            <strong>Total:</strong>
                            <strong id="total">
                                @if($event->isPaid())
                                    ৳{{ number_format($event->price, 2) }}
                                @else
                                    Free
                                @endif
                            </strong>
                        </div>
                    </div>

                    @guest
                        <div class="auth-notice">
                            <p>Please <a href="{{ route('login') }}" class="auth-link">login</a> or <a href="{{ route('register') }}" class="auth-link">register</a> to purchase tickets.</p>
                        </div>
                    @else
                        <button type="submit" class="btn-purchase">
                            <i class="bi bi-ticket-perforated"></i>
                            @if($event->isPaid())
                                Buy Tickets
                            @else
                                Get Free Tickets
                            @endif
                        </button>
                    @endguest
                    </form>
                @endif
            @endif

            <!-- Event Stats -->
            <div class="event-stats">
                <div class="stat-item">
                    <i class="bi bi-people"></i>
                    <span>{{ $event->capacity_display }} capacity</span>
                </div>
                <div class="stat-item">
                    <i class="bi bi-calendar-check"></i>
                    <span>Created {{ $event->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const eventPrice = {{ $event->price }};

function changeQuantity(change) {
    const quantityInput = document.getElementById('quantity');
    const currentQuantity = parseInt(quantityInput.value);
    const newQuantity = Math.max(1, Math.min(10, currentQuantity + change));
    
    quantityInput.value = newQuantity;
    updateTotal();
}

function updateTotal() {
    const quantity = parseInt(document.getElementById('quantity').value);
    const subtotal = eventPrice * quantity;
    const total = subtotal;
    
    document.getElementById('subtotal').textContent = '৳' + subtotal.toFixed(2);
    document.getElementById('total').textContent = '৳' + total.toFixed(2);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateTotal();
});

function handleTicketSelection(form) {
    const button = form.querySelector('.ticket-select-btn');
    const btnText = button.querySelector('.btn-text');
    const btnLoading = button.querySelector('.btn-loading');
    
    // Show loading state
    btnText.style.display = 'none';
    btnLoading.style.display = 'inline';
    button.disabled = true;
    button.classList.add('loading');
    
    // Add a slight delay to show the loading effect
    setTimeout(() => {
        form.submit();
    }, 300);
    
    return false; // Prevent immediate form submission
}
</script>

@endsection
