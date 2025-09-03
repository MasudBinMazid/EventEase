<link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">

<x-guest-layout>
    <div class="modal-content" style="position: relative; background: white; border-radius: 20px; padding: 40px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); max-width: 450px; margin: 0 auto;">
        
        <div class="auth-tabs" style="display: flex; margin-bottom: 30px; border-bottom: 2px solid #f3f4f6; border-radius: 12px; background: #f8fafc; padding: 4px;">
            <a href="{{ route('login') }}" class="tab-btn active" style="flex: 1; padding: 12px 20px; text-align: center; font-weight: 600; border-radius: 8px; text-decoration: none; color: white; background: linear-gradient(135deg, #0f172a 0%, #334155 100%); transition: all 0.3s ease;">Login</a>
            <a href="{{ route('register') }}" class="tab-btn" style="flex: 1; padding: 12px 20px; text-align: center; font-weight: 600; border-radius: 8px; text-decoration: none; color: #6b7280; background: transparent; transition: all 0.3s ease;">Register</a>
        </div>

        {{-- Login Errors --}}
        @if ($errors->any())
            <div class="auth-errors">
                <ul style="list-style: none; margin: 0; padding: 0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Status Messages --}}
        @if (session('status'))
            <div style="background: #d1fae5; color: #059669; padding: 12px 16px; margin-bottom: 15px; border: 1px solid #a7f3d0; border-radius: 8px; font-size: 14px; font-weight: 500;">
                {{ session('status') }}
            </div>
        @endif

        {{-- Login Form --}}
        <form method="POST" action="{{ route('login') }}" class="auth-form">
            @csrf
            <div>
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="you@example.com">
            </div>

            <div>
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required placeholder="••••••••">
            </div>

            <div class="auth-options" style="display: flex; justify-content: space-between; align-items: center; margin-top: 16px;">
                <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; color: #6b7280; font-weight: normal;">
                    <input id="remember_me" name="remember" type="checkbox" style="margin: 0;">
                    Remember me
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-password-link">Forgot Password?</a>
                @endif
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

        <!-- Small footer -->
        <p style="margin-top: 30px; text-align: center; font-size: 12px; color: #9ca3af;">
            Secure login · We never share your email.
        </p>
    </div>
</x-guest-layout>
