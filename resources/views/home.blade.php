@extends('layouts.app')

@section('title', 'Home')

@section('extra-css')
  <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/event-slider.css') }}">

@endsection

@section('content')
<!-- 🔹 Hero Section -->
<section class="hero-section">
  <div class="hero-flex-container">
    {{-- Banner Slider --}}
    <div class="slider-container">
      <div class="slider" id="slider">
        <a href="https://eventease.com/event1" class="slide-link active" target="_blank">
          <img src="{{ asset('assets/images/banner1.png') }}" class="slide-img" alt="Event 1" />
        </a>
        <a href="https://eventease.com/event2" class="slide-link" target="_blank">
          <img src="{{ asset('assets/images/banner2.png') }}" class="slide-img" alt="Event 2" />
        </a>
        <a href="https://eventease.com/event3" class="slide-link" target="_blank">
          <img src="{{ asset('assets/images/banner3.png') }}" class="slide-img" alt="Event 3" />
        </a>
      </div>
      <button class="slider-btn prev" id="prevBtn" aria-label="Previous slide">←</button>
      <button class="slider-btn next" id="nextBtn" aria-label="Next slide">→</button>

      <div class="slider-indicators" id="sliderDots"></div>

    </div>

    {{-- Embedded YouTube Video --}}
    <div class="video-wrapper">
      <iframe src=""
        title="EventEase Promo"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        allowfullscreen>
      </iframe>
    </div>
  </div>
</section>



<!-- 🔹 Event Type Infinite Slider -->
<section class="event-type-slider">
  <div class="event-type-wrapper">
    <button class="arrow-btn left" id="eventTypePrev">←</button>

    <div class="event-type-scroll" id="eventTypeScroll">
      <div class="event-type-track" id="eventTypeTrack">
        <div class="event-type-item">
          <img src="{{ asset('assets/icons/event/competitions.png') }}" alt="Competitions">
          <p>Competitions</p>
        </div>
        <div class="event-type-item">
          <img src="{{ asset('assets/icons/event/fashion.png') }}" alt="Fashion Shows">
          <p>Fashion<br>Shows</p>
        </div>
        <div class="event-type-item">
          <img src="{{ asset('assets/icons/event/conference.png') }}" alt="Conferences">
          <p>Conferences</p>
        </div>
        <div class="event-type-item">
          <img src="{{ asset('assets/icons/event/seminar.png') }}" alt="Seminars">
          <p>Seminars</p>
        </div>
        <div class="event-type-item">
          <img src="{{ asset('assets/icons/event/reunion.png') }}" alt="Reunions">
          <p>Reunions</p>
        </div>
        <div class="event-type-item">
          <img src="{{ asset('assets/icons/event/exhibition.png') }}" alt="Exhibitions">
          <p>Exhibitions</p>
        </div>
        <div class="event-type-item">
          <img src="{{ asset('assets/icons/event/launch.png') }}" alt="Launching">
          <p>Launching</p>
        </div>
        <div class="event-type-item">
          <img src="{{ asset('assets/icons/event/standup.png') }}" alt="Stand-up">
          <p>Stand-up</p>
        </div>
        <div class="event-type-item">
          <img src="{{ asset('assets/icons/event/drama.png') }}" alt="Drama">
          <p>Drama</p>
        </div>
        <div class="event-type-item">
          <img src="{{ asset('assets/icons/event/party.png') }}" alt="Party">
          <p>Party</p>
        </div>
        <div class="event-type-item">
          <img src="{{ asset('assets/icons/event/concert.png') }}" alt="Award">
          <p>Concerts</p>
        </div>
        <div class="event-type-item">
          <img src="{{ asset('assets/icons/event/festival.png') }}" alt="Festival">
          <p>Festival</p>
        </div>
        <div class="event-type-item">
          <img src="{{ asset('assets/icons/event/hackathon.png') }}" alt="Hackathon">
          <p>Hackathon</p>
        </div>
        <div class="event-type-item">
          <img src="{{ asset('assets/icons/event/sports.png') }}" alt="Sports">
          <p>Sports</p>
        </div>
        <div class="event-type-item">
          <img src="{{ asset('assets/icons/event/workshop.png') }}" alt="Workshop">
          <p>Workshop</p>
        </div>
      </div>
    </div>

    <button class="arrow-btn right" id="eventTypeNext">→</button>
  </div>
</section>








<!-- 🔹 Upcoming Events -->
<section class="upcoming-events">
  <h2>Explore Upcoming Events</h2>
  <div class="event-cards">
    @forelse($featuredEvents as $event)
      <a href="{{ route('events.show', $event) }}" class="event-card" target="_blank">
        @if($event->banner)
          <img src="{{ asset(ltrim($event->banner, '/')) }}" alt="{{ $event->title }}">
        @else
          <img src="{{ asset('assets/images/card1.png') }}" alt="{{ $event->title }}">
        @endif
        <div class="card-info">
          <h3>{{ $event->title }}</h3>
          <p>{{ $event->starts_at->format('F j') }} • {{ $event->location ?: $event->venue }}</p>
        </div>
      </a>
    @empty
      {{-- Fallback to static events if no featured events are available --}}
      <a href="https://eventease.com/tech-summit" class="event-card" target="_blank">
        <img src="{{ asset('assets/images/card1.png') }}" alt="Tech Summit">
        <div class="card-info">
          <h3>Tech Summit 2025</h3>
          <p>August 21 • Convention Center</p>
        </div>
      </a>

      <a href="http://127.0.0.1:8000/events/2" class="event-card" target="_blank">
        <img src="{{ asset('assets/images/card2.png') }}" alt="Music Fest">
        <div class="card-info">
          <h3>Music Fest Live</h3>
          <p>September 9 • DIU Auditorium</p>
        </div>
      </a>

      <a href="https://eventease.com/startup-fair" class="event-card" target="_blank">
        <img src="{{ asset('assets/images/card3.png') }}" alt="Startup Fair">
        <div class="card-info">
          <h3>Startup Fair</h3>
          <p>September 25 • Knowledge</p>
        </div>
      </a>
    @endforelse
  </div>
</section>


<!-- 🔹 Our Offerings -->
<section class="our-offerings">
  <h2>Our Offerings</h2>
  <p class="offer-subtitle">Explore the key features that make <b>EventEase</b> the perfect choice for event organizers!</p>
  <div class="offer-grid">
    <div class="offer-item">
      <img src="{{ asset('assets/icons/icon1.png') }}" alt="Easy Ticket Purchase" />
      <h3>Easy Ticket Purchase</h3>
      <p>Browse, and purchase tickets for a variety of events, from concerts to conferences, all from your device with ease and convenience.</p>
    </div>

    <div class="offer-item">
      <img src="{{ asset('assets/icons/icon2.png') }}" alt="Instant Ticket Delivery" />
      <h3>Automatic PDF Delivery</h3>
      <p>Receive your tickets instantly via email with PDF attachment immediately after booking. Professional tickets with QR codes delivered automatically to your inbox.</p>
    </div>

    <div class="offer-item">
      <img src="{{ asset('assets/icons/icon3.png') }}" alt="Multiple Payment Methods" />
      <h3>Multiple Payment Methods</h3>
      <p>Enjoy flexible payment options with bKash, Nagad, Upay, Visa, Mastercard, and more, ensuring secure and smooth transactions.</p>
    </div>

    <div class="offer-item">
      <img src="{{ asset('assets/icons/icon4.png') }}" alt="Tickipass Feature" />
      <h3>Tickipass Feature</h3>
      <p>Access purchased tickets instantly with Tickipass, displaying QR codes from your device, eliminating the need for printed e-ticket PDFs.</p>
    </div>

    <div class="offer-item">
      <img src="{{ asset('assets/icons/icon5.png') }}" alt="Comprehensive Dashboard" />
      <h3>Comprehensive Dashboard</h3>
      <p>Access real-time sales reports and attendance data through our user-friendly dashboard, providing valuable insights at your fingertips.</p>
    </div>

    <div class="offer-item">
      <img src="{{ asset('assets/icons/icon6.png') }}" alt="Smooth Scanning" />
      <h3>Smooth Scanning</h3>
      <p>Streamline the entry process with our efficient ticket scanning system, ensuring a hassle-free experience for attendees and organizers.</p>
    </div>
  </div>
</section>


<!-- 🔹 FAQ Section -->
<section class="faq-section">
  <h2>Frequently Asked Questions</h2>
  <div class="faq-container">

    <div class="faq-item">
      <button class="faq-question">How do I buy a ticket? <span class="icon">+</span></button>
      <div class="faq-answer">
        <p>You can book tickets through the Events section after logging in.</p>
      </div>
    </div>

    <div class="faq-item">
      <button class="faq-question">Where are the events hosted? <span class="icon">+</span></button>
      <div class="faq-answer">
        <p>We host both online and offline events. Locations are shown on each event card.</p>
      </div>
    </div>

    <div class="faq-item">
      <button class="faq-question">Can I refund or reschedule? <span class="icon">+</span></button>
      <div class="faq-answer">
        <p>Yes, depending on the event organizer's policy. See event terms for details.</p>
      </div>
    </div>

  </div>
</section>


@endsection

@section('extra-js')
  <script src="{{ asset('assets/js/home.js') }}"></script>
  <script src="{{ asset('assets/js/event-slider.js') }}"></script>

@endsection
