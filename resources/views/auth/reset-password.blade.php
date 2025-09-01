@extends('layouts.app')

@section('title', 'Reset Password')

@section('extra-css')
<style>
.reset-password-container {
    max-width: 500px;
    margin: 60px auto;
    background: white;
    border-radius: 12px;
    box-shadow: var(--shadow-lg);
    overflow: hidden;
    border: 1px solid var(--border-light);
}

.reset-password-header {
    background: var(--gradient-primary);
    color: white;
    padding: 30px 40px;
    text-align: center;
}

.reset-password-header h1 {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
}

.reset-password-header p {
    margin: 10px 0 0;
    opacity: 0.9;
    font-size: 14px;
}

.reset-password-body {
    padding: 40px;
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
    background: #fafafa;
}

.form-input:focus {
    border-color: var(--accent-blue);
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    background: white;
}

.form-input[readonly] {
    background: #f9f9f9;
    color: var(--text-light);
    cursor: not-allowed;
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

.password-requirements {
    background: #f0f9ff;
    border: 1px solid #bae6fd;
    border-radius: 8px;
    padding: 15px;
    margin-top: 10px;
    font-size: 13px;
}

.password-requirements h4 {
    margin: 0 0 10px;
    color: var(--text-dark);
    font-size: 14px;
}

.password-requirements ul {
    margin: 0;
    padding-left: 18px;
}

.password-requirements li {
    color: var(--text-light);
    margin-bottom: 3px;
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
<div class="reset-password-container">
    <div class="reset-password-header">
        <h1>🔑 Create New Password</h1>
        <p>Enter a strong password to secure your account</p>
    </div>

    <div class="reset-password-body">
        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input id="email" class="form-input" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" readonly />
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password" class="form-label">New Password</label>
                <input id="password" class="form-input" type="password" name="password" required autocomplete="new-password" placeholder="Enter new password" />
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
                
                <div class="password-requirements">
                    <h4>Password Requirements:</h4>
                    <ul>
                        <li>At least 8 characters long</li>
                        <li>Contains at least one uppercase letter</li>
                        <li>Contains at least one lowercase letter</li>
                        <li>Contains at least one number</li>
                    </ul>
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                <input id="password_confirmation" class="form-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm new password" />
                @error('password_confirmation')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="submit-button">
                🔐 Reset Password
            </button>
        </form>

        <div class="back-to-login">
            <a href="{{ route('login') }}">← Back to Login</a>
        </div>
    </div>
</div>
@endsection
