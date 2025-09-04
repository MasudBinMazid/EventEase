<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class GuestEmailVerificationController extends Controller
{
    /**
     * Mark the user's email address as verified.
     * This controller handles verification for both authenticated and guest users.
     */
    public function __invoke(Request $request, $id, $hash): RedirectResponse
    {
        try {
            // Find the user by ID
            $user = User::findOrFail($id);

            // Verify the hash matches the user's email
            if (!hash_equals($hash, sha1($user->email))) {
                return redirect()->route('login')
                    ->with('error', 'Invalid verification link. Please request a new verification email.');
            }

            // Check if the URL signature is valid
            if (!URL::hasValidSignature($request)) {
                return redirect()->route('login')
                    ->with('error', 'Verification link has expired. Please request a new verification email.');
            }

            // Check if email is already verified
            if ($user->hasVerifiedEmail()) {
                // If user is not logged in, log them in automatically after successful verification
                if (!Auth::check()) {
                    Auth::login($user);
                    return redirect()->route('dashboard')->with('success', 'Your email is already verified! Welcome back to EventEase!');
                }
                return redirect()->route('dashboard')->with('info', 'Your email is already verified!');
            }

            // Mark email as verified
            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
            }

            // If user is not logged in, log them in automatically after successful verification
            if (!Auth::check()) {
                Auth::login($user);
                return redirect()->route('dashboard')
                    ->with('success', 'Your email has been successfully verified! Welcome to EventEase!');
            }

            return redirect()->route('dashboard')
                ->with('success', 'Your email has been successfully verified!');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('login')
                ->with('error', 'Invalid verification link. The user associated with this link was not found.');
        } catch (\Exception $e) {
            \Log::error('Email verification error: ' . $e->getMessage(), [
                'user_id' => $id,
                'hash' => $hash,
                'url' => $request->fullUrl()
            ]);
            
            return redirect()->route('login')
                ->with('error', 'An error occurred during email verification. Please try again or contact support.');
        }
    }
}
