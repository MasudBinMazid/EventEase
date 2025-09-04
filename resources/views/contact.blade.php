@extends('layouts.app')

@section('title', 'Contact')

@section('extra-css')
  <link rel="stylesheet" href="{{ asset('assets/css/contact.css') }}">
@endsection

@section('content')
<section class="contact-container">




  {{-- Success Message --}}
  @if(session('success'))
    <p class="form-status success">{{ session('success') }}</p>
  @endif

  {{-- Validation Errors --}}
  @if ($errors->any())
    <div class="form-status error">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Contact Form --}}

  <h2>Contact Us</h2>


  <form action="{{ route('contact.store') }}" method="POST" class="contact-form" id="contactForm">
    @csrf
    <input type="text" name="name" placeholder="Your Name" value="{{ old('name') }}" required>
    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
    <textarea name="message" placeholder="Message..." required>{{ old('message') }}</textarea>
    <button type="submit">Send Message</button>
  </form>
</section>
@endsection

@section('extra-js')
  <script src="{{ asset('assets/js/contact.js') }}"></script>
@endsection
