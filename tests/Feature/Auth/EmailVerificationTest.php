<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_screen_can_be_rendered(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get('/verify-email');

        $response->assertStatus(200);
    }

    public function test_email_can_be_verified(): void
    {
        $user = User::factory()->unverified()->create();

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        Event::assertDispatched(Verified::class);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_email_can_be_verified_by_guest_user(): void
    {
        $user = User::factory()->unverified()->create();

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        // Test as guest (not logged in)
        $response = $this->get($verificationUrl);

        Event::assertDispatched(Verified::class);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        
        // Should redirect to dashboard and automatically log in the user
        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertAuthenticatedAs($user);
    }

    public function test_email_is_not_verified_with_invalid_hash(): void
    {
        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1('wrong-email')]
        );

        $response = $this->get($verificationUrl);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_verify_email_with_invalid_user_id(): void
    {
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => 99999, 'hash' => sha1('nonexistent@example.com')]
        );

        $response = $this->get($verificationUrl);

        $response->assertRedirect(route('login'));
    }
}
