
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
        <a href="{{ url('/auth/google') }}" class="google-btn">Continue with Google</a>
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
        <a href="{{ url('/auth/google') }}" class="google-btn">Continue with Google</a>
      </div>
    </form>
  </div>
</div>