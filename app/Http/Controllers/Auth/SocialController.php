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
        return Socialite::driver('google')->redirect();
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
                Auth::login($user);
            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(Str::random(16)), // Random password since they use Google
                    'email_verified_at' => now(), // Mark as verified since Google verified it
                ]);

                Auth::login($user);
            }

            return redirect()->intended('/dashboard')->with('success', 'Successfully logged in with Google!');
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Unable to login with Google. Please try again.');
        }
    }
}
