@extends('layouts.app')

@section('title', 'Forgot Password')

@section('extra-css')
<style>
.forgot-password-container {
    max-width: 500px;
    margin: 60px auto;
    background: white;
    border-radius: 12px;
    box-shadow: var(--shadow-lg);
    overflow: hidden;
    border: 1px solid var(--border-light);
}

.forgot-password-header {
    background: var(--gradient-primary);
    color: white;
    padding: 30px 40px;
    text-align: center;
}

.forgot-password-header h1 {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
}

.forgot-password-header p {
    margin: 10px 0 0;
    opacity: 0.9;
    font-size: 14px;
}

.forgot-password-body {
    padding: 40px;
}

.description {
    color: var(--text-light);
    margin-bottom: 30px;
    line-height: 1.6;
}

.status-message {
    background: #d1fae5;
    color: #065f46;
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 20px;
    border: 1px solid #a7f3d0;
    font-weight: 500;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--text-dark);
}

.form-input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid var(--border-light);
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
    box-sizing: border-box;
}

.form-input:focus {
    border-color: var(--accent-blue);
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.error-message {
    color: #dc2626;
    font-size: 14px;
    margin-top: 5px;
    font-weight: 500;
}

.submit-button {
    width: 100%;
    background: var(--gradient-accent);
    color: white;
    padding: 14px 20px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 10px;
}

.submit-button:hover {
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

.back-to-login {
    text-align: center;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid var(--border-light);
}

.back-to-login a {
    color: var(--accent-blue);
    text-decoration: none;
    font-weight: 500;
}

.back-to-login a:hover {
    color: var(--accent-orange);
}
</style>
@endsection

@section('content')
<div class="forgot-password-container">
    <div class="forgot-password-header">
        <h1>🔐 Reset Your Password</h1>
        <p>We'll send you a secure link to reset your password</p>
    </div>

    <div class="forgot-password-body">
        <p class="description">
            Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
        </p>

        <!-- Session Status -->
        @if (session('status'))
            <div class="status-message">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Enter your email address" />
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="submit-button">
                📧 Send Password Reset Link
            </button>
        </form>

        <div class="back-to-login">
            <a href="{{ route('login') }}">← Back to Login</a>
        </div>
    </div>
</div>
@endsection
