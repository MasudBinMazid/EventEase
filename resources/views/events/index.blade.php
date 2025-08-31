@extends('layouts.app')

@section('title', 'Events')

@section('extra-css')
  <link rel="stylesheet" href="{{ asset('assets/css/events.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection

@section('content')

<!-- Header Section -->
<section class="events-header">
  <div class="events-header-content">
    <h1>Discover Amazing Events</h1>
    <p>Join exciting events happening around you</p>
  </div>
</section>

<!-- Search Section -->
<section class="search-section">
  <div class="search-container">
    <div class="search-bar">
      <i class="bi bi-search search-icon"></i>
      <input type="text" id="searchInput" placeholder="Search events by name, location, or description...">
    </div>
    
    <div class="filter-tabs">
      <label class="filter-tab active">
        <input type="radio" name="eventStatus" value="all" checked>
        <span>All Events</span>
      </label>
      <label class="filter-tab">
        <input type="radio" name="eventStatus" value="upcoming">
        <span>Upcoming</span>
      </label>
      <label class="filter-tab">
        <input type="radio" name="eventStatus" value="ongoing">
        <span>Live Now</span>
      </label>
      <label class="filter-tab">
        <input type="radio" name="eventStatus" value="past">
        <span>Past Events</span>
      </label>
    </div>
  </div>
</section>

<!-- Event Cards -->
<section class="events-grid">
  <div class="events-container">
    @forelse($events as $event)
      @php
        // More accurate event status calculation
        $now = now();
        $eventEnd = $event->ends_at ?? $event->starts_at;
        $isUpcoming = $now->lt($event->starts_at);
        $isPast = $now->gt($eventEnd);
        $isOngoing = $now->gte($event->starts_at) && $now->lte($eventEnd);
        
        if ($isPast) {
          $eventStatus = 'past';
        } elseif ($isOngoing) {
          $eventStatus = 'ongoing';
        } else {
          $eventStatus = 'upcoming';
        }
      @endphp
      
      <div class="event-card" data-status="{{ $eventStatus }}">
        <!-- Event Banner -->
        <div class="event-banner">
          @if($event->banner_url)
            <img src="{{ $event->banner_url }}" alt="{{ $event->title }}" class="event-image">
          @else
            <div class="event-placeholder">
              <i class="bi bi-calendar-event"></i>
            </div>
          @endif
          
          <!-- Event Status Badge -->
          <div class="event-status-badge {{ $eventStatus }}">
            @if($isPast)
              <i class="bi bi-clock-history"></i> Past Event
            @elseif($isOngoing)
              <i class="bi bi-broadcast"></i> Live Now
            @else
              <i class="bi bi-calendar-check"></i> Upcoming
            @endif
          </div>
        </div>
        
        <!-- Event Content -->
        <div class="event-content">
          <h3 class="event-title">{{ $event->title }}</h3>
          
          <div class="event-details">
            <div class="event-detail">
              <i class="bi bi-calendar3"></i>
              <span>{{ $event->starts_at->format('M d, Y • h:i A') }}</span>
            </div>
            
            <div class="event-detail">
              <i class="bi bi-geo-alt"></i>
              <span>{{ $event->location }}</span>
            </div>
            
            <div class="event-detail price">
              <i class="bi bi-tag"></i>
              <span class="price-amount">
                @if($event->price > 0)
                  ${{ number_format($event->price, 2) }}
                @else
                  Free
                @endif
              </span>
            </div>
            
            @if($event->creator)
              <div class="event-detail organizer">
                <i class="bi bi-person-circle"></i>
                <span>Organized by {{ $event->creator->name }}</span>
              </div>
            @endif
          </div>
          
          @if($event->description)
            <p class="event-description">
              {{ \Illuminate\Support\Str::limit(strip_tags($event->description), 100) }}
            </p>
          @endif
          
          <!-- Action Buttons -->
          <div class="event-actions">
            <a href="{{ route('events.show', $event) }}" class="btn btn-outline">
              <i class="bi bi-eye"></i> View Details
            </a>
            
            @if($isUpcoming || $isOngoing)
              <form action="{{ route('tickets.start', $event) }}" method="POST" style="display: inline; flex: 1;">
                @csrf
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                  <i class="bi bi-ticket"></i> 
                  @if($event->price > 0) Buy Ticket @else Get Ticket @endif
                </button>
              </form>
            @else
              <button class="btn btn-disabled" disabled>
                <i class="bi bi-clock-history"></i> Event Ended
              </button>
            @endif
          </div>
        </div>
      </div>
    @empty
      <div class="no-events">
        <i class="bi bi-calendar-x"></i>
        <h3>No Events Found</h3>
        <p>There are currently no events available. Check back later!</p>
      </div>
    @endforelse
  </div>
  
  <!-- Pagination -->
  @if($events->hasPages())
    <div class="pagination-wrapper">
      {{ $events->links() }}
    </div>
  @endif
</section>

@endsection

@section('extra-js')
  <script src="{{ asset('assets/js/events.js') }}"></script>
@endsection
