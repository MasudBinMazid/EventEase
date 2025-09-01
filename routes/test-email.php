Route::get('/test-email', function () {
    try {
        $user = auth()->user() ?? \App\Models\User::first();
        
        if (!$user) {
            return 'No user found. Please register first.';
        }

        // Send verification email
        $user->sendEmailVerificationNotification();
        
        return 'Verification email sent to: ' . $user->email;
    } catch (Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
})->name('test.email');
