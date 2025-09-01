
<link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">


<div id="authModal" class="modal-overlay" style="display: none;">
  <div class="modal-content">
    <span class="close-modal" onclick="closeAuthModal()">&times;</span>

    <div class="auth-tabs">
      <button class="tab-btn active" onclick="switchAuthTab('login')">Login</button>
      <button class="tab-btn" onclick="switchAuthTab('register')">Register</button>
    </div>

    {{-- Login Errors --}}
    @if ($errors->any() && !old('name'))
      <div class="auth-errors">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- Login Form --}}
    <form method="POST" action="{{ route('login.custom') }}" class="auth-form" id="loginForm">
      @csrf
      <label>Email</label>
      <input type="email" name="email" required>

      <label>Password</label>
      <input type="password" name="password" required>

      <div class="auth-options">
        <a href="{{ route('password.request') }}" class="forgot-password-link">Forgot Password?</a>
      </div>

      <button type="submit">Login</button>

      <p class="auth-or">OR</p>
      <div class="social-login">
        <a href="{{ url('/auth/google') }}" class="google-btn">
          <svg width="18" height="18" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
          </svg>
          Continue with Google
        </a>
      </div>
    </form>

    {{-- Register Errors --}}
    @if ($errors->any() && old('name'))
      <div class="auth-errors">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- Register Form --}}
    <form method="POST" action="{{ route('register.custom') }}" class="auth-form" id="registerForm" style="display: none;">
      @csrf
      <label>Name</label>
      <input type="text" name="name" required>

      <label>Email</label>
      <input type="email" name="email" required>

      <label>Password</label>
      <input type="password" name="password" required>

      <label>Confirm Password</label>
      <input type="password" name="password_confirmation" required>

      <button type="submit">Register</button>

      <p class="auth-or">OR</p>
      <div class="social-login">
        <a href="{{ url('/auth/google') }}" class="google-btn">
          <svg width="18" height="18" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
          </svg>
          Continue with Google
        </a>
      </div>
    </form>
  </div>
</div>