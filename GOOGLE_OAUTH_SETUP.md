# Google OAuth Setup Guide for EventEase

This guide will help you set up Google OAuth authentication for your EventEase Laravel application.

## Prerequisites

✅ Laravel Socialite is already installed (`laravel/socialite": "^5.21"`)
✅ Google OAuth routes are already configured
✅ SocialController is already implemented
✅ Auth modal UI with Google button is ready

## Step 1: Create Google OAuth Application

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Navigate to "APIs & Services" > "Credentials"
4. Click "Create Credentials" > "OAuth 2.0 Client IDs"
5. Configure the OAuth consent screen first if prompted
6. For Application type, select "Web application"
7. Add authorized redirect URIs:
   - For local development: `http://localhost:8000/auth/google/callback`
   - For production: `https://yourdomain.com/auth/google/callback`
8. Save and note down your Client ID and Client Secret

## Step 2: Configure Environment Variables

Update your `.env` file with the Google OAuth credentials:

```env
# Google OAuth Configuration
GOOGLE_CLIENT_ID=your_actual_google_client_id_here
GOOGLE_CLIENT_SECRET=your_actual_google_client_secret_here
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

**Important:** Replace the placeholder values with your actual Google OAuth credentials!

## Step 3: Clear Configuration Cache

Run these commands to clear and reload configuration:

```bash
php artisan config:clear
php artisan config:cache
php artisan route:clear
```

## Step 4: Database Migration

Ensure your users table has the necessary fields. The current migration should include:

- `name` (string)
- `email` (string, unique)
- `password` (string, nullable for OAuth users)
- `email_verified_at` (timestamp, nullable)

## Step 5: Test the Integration

1. Start your Laravel development server: `php artisan serve`
2. Visit your application in the browser
3. Click the login/register button to open the auth modal
4. Click "Continue with Google"
5. You should be redirected to Google's OAuth consent screen
6. After authorization, you'll be redirected back and logged in

## How it Works

### Frontend (Auth Modal)
- Located in `resources/views/components/auth-modal.blade.php`
- Contains modern Google button with official Google branding
- Available in both login and register forms

### Backend Flow
1. User clicks "Continue with Google"
2. Redirected to `/auth/google` (handled by `SocialController@redirectToGoogle`)
3. Google OAuth consent screen appears
4. User authorizes the application
5. Google redirects to `/auth/google/callback` (handled by `SocialController@handleGoogleCallback`)
6. Controller checks if user exists by email
7. If user exists: logs them in
8. If user doesn't exist: creates new user and logs them in
9. Redirects to intended page (usually dashboard) with success message

### Security Features
- Email verification is automatically marked as completed for Google users
- Random password is generated for OAuth-only users
- User data is safely stored in the database
- Proper error handling for failed OAuth attempts

## Configuration Files

### Routes (`routes/web.php`)
```php
Route::get('/auth/google', [SocialController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [SocialController::class, 'handleGoogleCallback']);
```

### Services Configuration (`config/services.php`)
```php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI', 'http://127.0.0.1:8000/auth/google/callback'),
],
```

### User Model (`app/Models/User.php`)
- Fillable fields include: `name`, `email`, `password`, `phone`, `profile_picture`
- Implements `MustVerifyEmail` interface
- Has proper relationships and admin role checking

## Troubleshooting

### Common Issues:

1. **"Invalid redirect URI"**
   - Ensure the redirect URI in Google Console matches exactly with your application URL
   - Check that `GOOGLE_REDIRECT_URI` in `.env` is correct

2. **"Client ID not found"**
   - Verify `GOOGLE_CLIENT_ID` in `.env` is correct
   - Clear config cache: `php artisan config:clear`

3. **"Access denied"**
   - Check OAuth consent screen settings in Google Console
   - Ensure your application is not in "Testing" mode for production

4. **User creation fails**
   - Check database connection
   - Ensure users table exists and has required fields
   - Check fillable fields in User model

### Debug Mode
Enable debug mode in `.env` to see detailed error messages:
```env
APP_DEBUG=true
```

## Production Considerations

1. Update `GOOGLE_REDIRECT_URI` to use your production domain
2. Configure OAuth consent screen for production use
3. Set `APP_DEBUG=false` in production
4. Use HTTPS for redirect URIs in production
5. Consider implementing additional user profile fields if needed

## Next Steps

After successful setup, you might want to:
1. Add additional OAuth providers (Facebook, GitHub, etc.)
2. Implement user profile picture sync from Google
3. Add OAuth account linking for existing users
4. Implement logout from Google option

## Support

If you encounter issues:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Verify Google Console settings
3. Test with different browsers/incognito mode
4. Check network requests in browser dev tools
