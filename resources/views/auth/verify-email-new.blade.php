@extends('layouts.app')

@section('title', 'Verify Your Email')

@section('extra-css')
  <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
  <style>
    .verify-email-container {
      min-height: 80vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    
    .verify-card {
      background: white;
      border-radius: 16px;
      padding: 40px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      max-width: 500px;
      width: 100%;
      text-align: center;
    }
    
    .verify-icon {
      width: 80px;
      height: 80px;
      background: linear-gradient(135deg, var(--primary-navy) 0%, var(--accent-blue) 100%);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 24px;
      color: white;
      font-size: 36px;
    }
    
    .verify-title {
      font-size: 2rem;
      font-weight: 700;
      color: var(--text-dark);
      margin-bottom: 16px;
    }
    
    .verify-message {
      color: var(--text-medium);
      font-size: 1rem;
      line-height: 1.6;
      margin-bottom: 32px;
    }
    
    .verify-success {
      background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
      color: white;
      padding: 16px;
      border-radius: 12px;
      margin-bottom: 24px;
      font-weight: 500;
    }
    
    .verify-buttons {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }
    
    .btn-verify {
      background: linear-gradient(135deg, var(--primary-navy) 0%, var(--accent-blue) 100%);
      color: white;
      padding: 12px 24px;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      width: 100%;
    }
    
    .btn-verify:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
    }
    
    .btn-secondary {
      background: transparent;
      color: var(--text-medium);
      padding: 12px 24px;
      border: 2px solid var(--border-light);
      border-radius: 8px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
      width: 100%;
    }
    
    .btn-secondary:hover {
      border-color: var(--accent-blue);
      color: var(--accent-blue);
    }
    
    @media (max-width: 768px) {
      .verify-card {
        padding: 30px 20px;
        margin: 20px;
      }
      
      .verify-title {
        font-size: 1.5rem;
      }
    }
  </style>
@endsection

@section('content')
<div class="verify-email-container">
  <div class="verify-card">
    <div class="verify-icon">
      <i class="bi bi-envelope-check"></i>
    </div>
    
    <h1 class="verify-title">Verify Your Email</h1>
    
    <div class="verify-message">
      Thanks for signing up for EventEase! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
    </div>

    @if (session('status') == 'verification-link-sent')
      <div class="verify-success">
        <i class="bi bi-check-circle me-2"></i>
        A new verification link has been sent to the email address you provided during registration.
      </div>
    @endif

    <div class="verify-buttons">
      <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn-verify">
          <i class="bi bi-envelope me-2"></i>
          Resend Verification Email
        </button>
      </form>

      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-secondary">
          <i class="bi bi-box-arrow-right me-2"></i>
          Log Out
        </button>
      </form>
    </div>
    
    <div class="mt-4 text-sm" style="color: #6b7280;">
      <p>Having trouble? Check your spam folder or contact support.</p>
    </div>
  </div>
</div>
@endsection
