<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class SocialController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        try {
            return Socialite::driver('google')->redirect();
        } catch (\Exception $e) {
            \Log::error('Google OAuth Redirect Error: ' . $e->getMessage());
            return redirect('/')->with('error', 'Google OAuth is not properly configured. Please contact support.');
        }
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check if user exists with this email
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // User exists, log them in
                $user->update(['last_login_at' => now()]);
                Auth::login($user);
                $message = 'Welcome back! Successfully logged in with Google.';
            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(Str::random(16)), // Random password since they use Google
                    'email_verified_at' => now(), // Mark as verified since Google verified it
                    'last_login_at' => now(), // Set initial login time
                ]);

                Auth::login($user);
                $message = 'Account created successfully! Welcome to EventEase.';
            }

            return redirect()->intended('/dashboard')->with('success', $message);
        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            return redirect('/')->with('error', 'Authentication session expired. Please try again.');
        } catch (\Exception $e) {
            \Log::error('Google OAuth Error: ' . $e->getMessage());
            return redirect('/')->with('error', 'Unable to login with Google. Please try again or contact support.');
        }
    }
}
