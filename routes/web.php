<?php

use Illuminate\Support\Facades\Route;

// Public / front controllers
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventRequestController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\AuthController;

// Test email route for development
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
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PaymentController;

// Admin controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\BlogAdminController;
use App\Http\Controllers\Admin\EventAdminController;
use App\Http\Controllers\Admin\EventRequestAdminController;
use App\Http\Controllers\Admin\SalesController as AdminSalesController;
use App\Http\Controllers\Admin\SalesExportController;
use App\Http\Controllers\Admin\SalesByEventController;
use App\Http\Controllers\Admin\MessageAdminController;
use App\Http\Controllers\Admin\StatsController as AdminStatsController;
use App\Http\Controllers\Admin\PaymentReceivedController;
use App\Http\Controllers\Admin\NoticeController;

// Organizer controllers
use App\Http\Controllers\Organizer\OrganizerController;

/*
|--------------------------------------------------------------------------
| Navbar / static pages
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index']);
Route::get('/gallery', fn () => view('gallery'));
Route::get('/contact', fn () => view('contact'));

/*
|--------------------------------------------------------------------------
| Contact form
|--------------------------------------------------------------------------
*/
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

/*
|--------------------------------------------------------------------------
| Auth scaffolding (Breeze/Fortify/etc.)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Authenticated user area (dashboard + profile)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/purchase-history', [ProfileController::class, 'purchaseHistory'])->name('purchase.history');
    
    // Notification routes
    Route::post('/notifications/{notification}/read', [ProfileController::class, 'markNotificationAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [ProfileController::class, 'markAllNotificationsAsRead'])->name('notifications.markAllRead');
    Route::get('/notifications', [ProfileController::class, 'notifications'])->name('notifications.index');
    
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::get('/tickets/{ticket}/download', [TicketController::class, 'download'])->name('tickets.download');
    Route::get('/tickets/{ticket}/complete-payment', [TicketController::class, 'completePayment'])->name('ticket.complete-payment');
    Route::post('/tickets/{ticket}/complete-payment', [TicketController::class, 'initiatePayment'])->name('ticket.initiate-payment');
});

/*
|--------------------------------------------------------------------------
| Events (public list shows approved in controller)
|--------------------------------------------------------------------------
*/
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

/*
|--------------------------------------------------------------------------
| User-submitted event requests
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/events/request/create', [EventRequestController::class, 'create'])->name('events.request.create');
    Route::post('/events/request', [EventRequestController::class, 'store'])->name('events.request.store');
});

/*
|--------------------------------------------------------------------------
| Ticket purchase flow (auth + verified required)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    // Start buying for a specific event
    Route::post('/events/{event}/buy', [TicketController::class, 'start'])->name('tickets.start');

    // Checkout and confirm
    Route::get('/checkout', [TicketController::class, 'checkout'])->name('tickets.checkout'); // expects ?event_id=&qty=
    Route::post('/checkout/confirm', [TicketController::class, 'confirm'])->name('tickets.confirm');

    // Manual payment flow (Pay now → show instructions → user clicks "Yes, I Paid")
    Route::get('/payments/manual',  [PaymentController::class, 'manual'])->name('payments.manual');
    Route::post('/payments/manual/confirm', [PaymentController::class, 'manualConfirm'])->name('payments.manual.confirm');

    // Ticket views & download
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::get('/tickets/{ticket}/download', [TicketController::class, 'download'])->name('tickets.download');
    
    // Ticket verification (public route for QR code scanning)
    Route::get('/verify/{ticketCode}', [TicketController::class, 'verify'])->name('tickets.verify');
    Route::get('/verify', [TicketController::class, 'verifyForm'])->name('tickets.verify.form');
    Route::post('/verify/{ticketCode}/enter', [TicketController::class, 'markAsEntered'])->name('tickets.mark.entered');
    
    /* 
    // Development/Testing routes - Remove in production
    Route::get('/test-entry-status', function() {
        $tickets = \App\Models\Ticket::with(['user', 'event'])->where('payment_status', 'paid')->limit(3)->get();
        
        $output = "<h1>Entry Status Test</h1>";
        $output .= "<p>Found " . count($tickets) . " paid tickets:</p>";
        
        foreach ($tickets as $ticket) {
            $output .= "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
            $output .= "<strong>Ticket:</strong> " . $ticket->ticket_code . "<br>";
            $output .= "<strong>Event:</strong> " . $ticket->event->title . "<br>";
            $output .= "<strong>Holder:</strong> " . $ticket->user->name . "<br>";
            $output .= "<strong>Payment Status:</strong> " . $ticket->payment_status . "<br>";
            $output .= "<strong>Entry Status:</strong> " . $ticket->entry_status . "<br>";
            if ($ticket->entry_marked_at) {
                $output .= "<strong>Entered At:</strong> " . $ticket->entry_marked_at . "<br>";
            }
            $output .= "<a href='/verify/" . $ticket->ticket_code . "' target='_blank'>Test Verify</a>";
            $output .= "</div>";
        }
        
        return $output;
    });
    
    Route::get('/test-api/{ticketCode?}', function($ticketCode = null) {
        if (!$ticketCode) {
            $ticket = \App\Models\Ticket::where('payment_status', 'paid')->first();
            $ticketCode = $ticket ? $ticket->ticket_code : 'TKT-NOTFOUND';
        }
        
        $response = (new \App\Http\Controllers\TicketController)->verify($ticketCode);
        
        return response()->json([
            'ticket_code' => $ticketCode,
            'api_response' => $response->getData()
        ]);
    });
    
    Route::get('/test-workflow', function() {
        return view('test-workflow');
    });
    */    // Test API route
    Route::get('/test-api/{ticketCode?}', function($ticketCode = null) {
        if (!$ticketCode) {
            $ticket = \App\Models\Ticket::where('payment_status', 'paid')->first();
            $ticketCode = $ticket ? $ticket->ticket_code : 'TKT-NOTFOUND';
        }
        
        $response = (new \App\Http\Controllers\TicketController)->verify($ticketCode);
        
        return response()->json([
            'ticket_code' => $ticketCode,
            'api_response' => $response->getData()
        ]);
    });
    
    // Serve test workflow page
    Route::get('/test-workflow', function() {
        return view('test-workflow');
    });
});

/*
|--------------------------------------------------------------------------
| Social auth (Google only)
|--------------------------------------------------------------------------
*/
Route::get('/auth/google', [SocialController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [SocialController::class, 'handleGoogleCallback']);

/*
|--------------------------------------------------------------------------
| SSLCommerz Payment Gateway
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/payment/sslcommerz/initiate', [App\Http\Controllers\SSLCommerzController::class, 'initiatePayment'])->name('sslcommerz.initiate');
    Route::get('/payment/success-page', [App\Http\Controllers\SSLCommerzController::class, 'paymentSuccessPage'])->name('sslcommerz.success.page');
    Route::get('/payment/status/{tran_id}', [App\Http\Controllers\SSLCommerzController::class, 'checkPaymentStatus'])->name('sslcommerz.status');
    
    // Test route for payment success page (remove in production)
    Route::get('/payment/test-success/{ticket_id}', function($ticketId) {
        session()->flash('payment_success_ticket', $ticketId);
        return redirect()->route('sslcommerz.success.page');
    })->name('test.payment.success');
});

// SSLCommerz callback routes (no auth required)
Route::post('/payment/success', [App\Http\Controllers\SSLCommerzController::class, 'paymentSuccess'])->name('sslcommerz.success');
Route::post('/payment/fail', [App\Http\Controllers\SSLCommerzController::class, 'paymentFail'])->name('sslcommerz.fail');
Route::post('/payment/cancel', [App\Http\Controllers\SSLCommerzController::class, 'paymentCancel'])->name('sslcommerz.cancel');
Route::post('/payment/ipn', [App\Http\Controllers\SSLCommerzController::class, 'paymentIPN'])->name('sslcommerz.ipn');

// Payment status pages (no auth required - accessible even if logged out)
Route::get('/payment/failed', function() {
    return view('payments.failed', [
        'failedReason' => session('payment_fail_reason', 'Unknown error'),
        'eventId' => session('payment_fail_event_id'),
        'tranId' => session('payment_fail_tran_id')
    ]);
})->name('payment.failed');

Route::get('/payment/cancelled', function() {
    return view('payments.cancelled', [
        'eventId' => session('payment_cancel_event_id'),
        'event' => session('payment_cancel_event_id') ? \App\Models\Event::find(session('payment_cancel_event_id')) : null,
        'quantity' => session('payment_cancel_quantity'),
        'totalAmount' => session('payment_cancel_amount'),
        'tranId' => session('payment_cancel_tran_id')
    ]);
})->name('payment.cancelled');

// Test route to simulate the cancel flow
Route::get('/test-cancel-flow', function() {
    // Simulate what happens when SSLCommerz sends a cancel request
    $tranId = 'TEST_' . time();
    
    // Store some test data in session like a real transaction would
    session()->flash('payment_cancel_event_id', 1);
    session()->flash('payment_cancel_quantity', 2);
    session()->flash('payment_cancel_amount', 500.00);
    session()->flash('payment_cancel_tran_id', $tranId);
    
    return redirect()->route('payment.cancelled');
});

// Test route to simulate actual SSLCommerz POST request
Route::post('/test-sslcommerz-cancel', function(\Illuminate\Http\Request $request) {
    // This simulates exactly what SSLCommerz would send
    $controller = new \App\Http\Controllers\SSLCommerzController(new \App\Services\SSLCommerzService());
    return $controller->paymentCancel($request);
});

// Test route for authenticated user to verify login status
Route::middleware(['auth'])->get('/test-auth-status', function() {
    return response()->json([
        'authenticated' => true,
        'user' => auth()->user()->name ?? 'Unknown',
        'user_id' => auth()->id(),
        'message' => 'User is logged in successfully'
    ]);
});

/*
|--------------------------------------------------------------------------
| Username/password auth (custom endpoints in your AuthController)
|--------------------------------------------------------------------------
*/
Route::post('/login',    [AuthController::class, 'login'])->name('login.custom');
Route::post('/register', [AuthController::class, 'register'])->name('register.custom');
Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Blog (public)
|--------------------------------------------------------------------------
*/
Route::get('/blog',      [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{id}', [BlogController::class, 'show'])->name('blog.show');

/*
|--------------------------------------------------------------------------
| Gallery details (static demo data)
|--------------------------------------------------------------------------
*/
Route::get('/gallery/event-{id}', function ($id) {
    $events = [
        1 => ['title' => 'Book Fair 2025', 'images' => ['a1.png', 'a2.png', 'a3.png', 'a4.png', 'a5.png']],
        2 => ['title' => 'Art Exhibition', 'images' => ['b1.png', 'b2.png', 'b3.png', 'b4.png', 'b5.png']],
        3 => ['title' => 'Tech Conference', 'images' => ['c1.png', 'c2.png', 'c3.png', 'c4.png', 'c5.png']],
        4 => ['title' => 'Food Carnival', 'images' => ['d1.png', 'd2.png', 'd3.png', 'd4.png']],
        5 => ['title' => 'Film Night', 'images' => ['e1.png', 'e2.png', 'e3.png', 'e4.png']],
        6 => ['title' => 'Startup Meetup', 'images' => ['f1.png', 'f2.png', 'f3.png', 'f4.png', 'f5.png']],
        7 => ['title' => 'Book Fair', 'images' => ['g1.png', 'g2.png', 'g3.png', 'g4.png', 'g5.png']],
        8 => ['title' => 'Dance Show', 'images' => ['h1.png', 'h2.png', 'h3.png', 'h4.png', 'h5.png']],
        9 => ['title' => 'Drama Performance', 'images' => ['i1.png', 'i2.png', 'i3.png', 'i4.png', 'i5.png', 'i6.png']],
        10 => ['title' => 'Fashion Gala', 'images' => ['j1.png', 'j2.png' , 'j3.png' , 'j4.png', 'j5.png']],
        11 => ['title' => 'Science Fair', 'images' => ['h1.png', 'h2.png' , 'h3.png', 'h4.png', 'h5.png']],
        12 => ['title' => 'Photography Expo', 'images' => ['i1.png', 'i2.png' , 'i3.png', 'i4.png', 'i5.png']],
        13 => ['title' => 'Cultural Day', 'images' => ['j1.png', 'j2.png' , 'j3.png', 'j4.png', 'j5.png']],
        14 => ['title' => 'Charity Concert', 'images' => ['k1.jpg', 'k2.jpg' , 'k3.jpg' , 'k4.jpg', 'k5.jpg']],
    ];

    abort_unless(array_key_exists($id, $events), 404);

    return view('gallery-details', $events[$id]);
});

/*
|--------------------------------------------------------------------------
| Admin Area (inside Laravel)
|--------------------------------------------------------------------------
| Manager middleware allows both admin and manager roles to access all admin functions
| except user deletion and role updates which are admin-only
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'manager'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    // Users (managers can view, only admins can delete/change roles)
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/details', [AdminUserController::class, 'getUserDetails'])->name('users.details');
    Route::post('/users/{user}/send-notification', [AdminUserController::class, 'sendNotification'])->name('users.sendNotification');
    Route::post('/users/send-bulk-notification', [AdminUserController::class, 'sendBulkNotification'])->name('users.sendBulkNotification');
    
    // Debug route for templates
    Route::get('/debug-templates', function() {
        $templates = \App\Models\NotificationTemplate::all();
        return response()->json([
            'count' => $templates->count(),
            'templates' => $templates->map(function($t) {
                return [
                    'id' => $t->id,
                    'name' => $t->name,
                    'category' => $t->category,
                    'is_active' => $t->is_active
                ];
            })
        ]);
    });
    
    // Admin-only routes for user management
    Route::middleware('admin')->group(function () {
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
        Route::patch('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.updateRole');
    });

    // Blogs (CRUD)
    Route::get('/blogs', [BlogAdminController::class, 'index'])->name('blogs.index');
    Route::get('/blogs/create', [BlogAdminController::class, 'create'])->name('blogs.create');
    Route::post('/blogs', [BlogAdminController::class, 'store'])->name('blogs.store');
    Route::get('/blogs/{blog}/edit', [BlogAdminController::class, 'edit'])->name('blogs.edit');
    Route::put('/blogs/{blog}', [BlogAdminController::class, 'update'])->name('blogs.update');
    Route::delete('/blogs/{blog}', [BlogAdminController::class, 'destroy'])->name('blogs.destroy');

    // Events (CRUD)
    Route::get('/events', [EventAdminController::class, 'index'])->name('events.index');
    Route::get('/events/create', [EventAdminController::class, 'create'])->name('events.create');
    Route::post('/events', [EventAdminController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [EventAdminController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventAdminController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventAdminController::class, 'destroy'])->name('events.destroy');

    // Pending event requests (approve/reject)
    Route::get('/event-requests', [EventRequestAdminController::class, 'index'])->name('requests.index');
    Route::post('/event-requests/{event}/approve', [EventRequestAdminController::class, 'approve'])->name('requests.approve');
    Route::post('/event-requests/{event}/reject',  [EventRequestAdminController::class, 'reject'])->name('requests.reject');

    // Sales (tickets) + export
    Route::get('/sales', [AdminSalesController::class, 'index'])->name('sales.index');
    Route::get('/sales/export', [SalesExportController::class, 'export'])->name('sales.export');
    Route::post('/sales/{ticket}/verify', [AdminSalesController::class, 'verify'])->name('sales.verify'); // ← added

    // Sales by event
    Route::get('/sales-events', [SalesByEventController::class, 'index'])->name('sales.events');

    // Messages (contacts) + reply
    Route::get('/messages', [MessageAdminController::class, 'index'])->name('messages.index');
    Route::get('/messages/{contact}', [MessageAdminController::class, 'show'])->name('messages.show');
    Route::post('/messages/{contact}/reply', [MessageAdminController::class, 'reply'])->name('messages.reply');

    // Statistics
    Route::get('/stats', [AdminStatsController::class, 'index'])->name('stats.index');

    // Payment Received (manual payments awaiting verification)
Route::get('/payments-received', [PaymentReceivedController::class, 'index'])->name('payments.index');
Route::post('/payments-received/{ticket}/verify', [PaymentReceivedController::class, 'verify'])->name('payments.verify');

    // Notice Management
    Route::get('/notices', [NoticeController::class, 'index'])->name('notices.index');
    Route::get('/notices/create', [NoticeController::class, 'create'])->name('notices.create');
    Route::post('/notices', [NoticeController::class, 'store'])->name('notices.store');
    Route::get('/notices/{notice}/edit', [NoticeController::class, 'edit'])->name('notices.edit');
    Route::put('/notices/{notice}', [NoticeController::class, 'update'])->name('notices.update');
    Route::delete('/notices/{notice}', [NoticeController::class, 'destroy'])->name('notices.destroy');
    Route::post('/notices/settings', [NoticeController::class, 'toggleSettings'])->name('notices.settings');

    // Feature Banner Management
    Route::get('/banners', [\App\Http\Controllers\Admin\FeatureBannerController::class, 'index'])->name('banners.index');
    Route::get('/banners/create', [\App\Http\Controllers\Admin\FeatureBannerController::class, 'create'])->name('banners.create');
    Route::post('/banners', [\App\Http\Controllers\Admin\FeatureBannerController::class, 'store'])->name('banners.store');
    Route::get('/banners/{banner}/edit', [\App\Http\Controllers\Admin\FeatureBannerController::class, 'edit'])->name('banners.edit');
    Route::put('/banners/{banner}', [\App\Http\Controllers\Admin\FeatureBannerController::class, 'update'])->name('banners.update');
    Route::delete('/banners/{banner}', [\App\Http\Controllers\Admin\FeatureBannerController::class, 'destroy'])->name('banners.destroy');
    Route::post('/banners/{banner}/toggle', [\App\Http\Controllers\Admin\FeatureBannerController::class, 'toggleStatus'])->name('banners.toggle');
});

/*
|--------------------------------------------------------------------------
| Manager Redirect
|--------------------------------------------------------------------------
| Redirect managers to admin panel since they have access to all admin functions
*/
Route::get('/manager', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    
    $userRole = strtolower((string)auth()->user()->role);
    if (!in_array($userRole, ['admin', 'manager'])) {
        abort(403, 'Manager or Admin access required');
    }
    
    return redirect()->route('admin.index');
})->middleware(['auth', 'verified']);

/*
|--------------------------------------------------------------------------
| Organizer Panel
|--------------------------------------------------------------------------
| Routes for event organizers to manage their events
*/
Route::prefix('organizer')->name('organizer.')->middleware(['auth', 'verified', 'organizer'])->group(function () {
    // Dashboard
    Route::get('/', [OrganizerController::class, 'index'])->name('dashboard');
    
    // Event details (only events created by this organizer)
    Route::get('/events/{event}', [OrganizerController::class, 'show'])->name('events.show');
    
    // Event tickets (only for events created by this organizer)
    Route::get('/events/{event}/tickets', [OrganizerController::class, 'tickets'])->name('tickets');
});

// Test route for ticket email functionality (development only)
if (config('app.debug')) {
    Route::get('/test-ticket-email/{ticket}', function (\App\Models\Ticket $ticket) {
        try {
            $ticket->user->notify(new \App\Notifications\TicketPdfNotification($ticket));
            
            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully to ' . $ticket->user->email,
                'ticket' => [
                    'id' => $ticket->id,
                    'code' => $ticket->ticket_code,
                    'user' => $ticket->user->name,
                    'event' => $ticket->event->title,
                    'status' => $ticket->payment_status
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage(),
                'error' => $e->getLine() . ': ' . $e->getFile()
            ], 500);
        }
    })->name('test.ticket.email');
    
    // Preview route to see how the email looks
    Route::get('/preview-ticket-email/{ticket}', function (\App\Models\Ticket $ticket) {
        $eventDetails = '
            <div style="margin: 10px 0;">
                <strong>Event:</strong> ' . $ticket->event->title . '
            </div>
            <div style="margin: 10px 0;">
                <strong>📅 Date:</strong> ' . ($ticket->event->starts_at ? $ticket->event->starts_at->format('l, F j, Y g:i A') : 'TBA') . '
            </div>
            <div style="margin: 10px 0;">
                <strong>📍 Venue:</strong> ' . ($ticket->event->venue ?? $ticket->event->location ?? 'TBA') . '
            </div>
            <div style="margin: 10px 0;">
                <strong>🎫 Ticket Code:</strong> ' . $ticket->ticket_code . '
            </div>
            <div style="margin: 10px 0;">
                <strong>🔢 Quantity:</strong> ' . $ticket->quantity . ' ' . ($ticket->quantity > 1 ? 'tickets' : 'ticket') . '
            </div>
            <div style="margin: 10px 0;">
                <strong>💰 Total Amount:</strong> ৳' . number_format($ticket->total_amount, 2) . '
            </div>
            <div style="margin: 10px 0;">
                <strong>💳 Payment Status:</strong> ' . ucfirst($ticket->payment_status) . '
            </div>
        ';
        
        $contentLines = [];
        $actionUrl = route('tickets.show', $ticket);
        $actionText = 'View Ticket Online';
        
        if ($ticket->payment_status === 'paid') {
            $contentLines[] = '✅ Your payment has been confirmed and your ticket is valid for entry.';
        } else {
            $contentLines[] = '⏳ Your ticket is generated but payment verification is pending.';
            $contentLines[] = 'You will receive another confirmation email once your payment is verified.';
            $actionText = 'Complete Payment';
        }
        
        $importantNote = 'Please bring this ticket (printed or on your mobile device) and a valid ID to the event.<br><br>Your PDF ticket is attached to this email for your convenience.';
        
        return view('emails.ticket-notification', [
            'subject' => '🎫 Your Event Ticket - ' . $ticket->event->title,
            'headerTitle' => 'Your Event Ticket',
            'greeting' => 'Hello ' . $ticket->user->name . '!',
            'introLine' => 'Thank you for your booking! Your event ticket is ready.',
            'eventDetails' => $eventDetails,
            'contentLines' => $contentLines,
            'actionUrl' => $actionUrl,
            'actionText' => $actionText,
            'importantNote' => $importantNote,
            'closingLine' => 'If you have any questions, please don\'t hesitate to contact us.<br><br>See you at the event! 🎉',
        ]);
    })->name('preview.ticket.email');
    
    // Preview email verification template
    Route::get('/preview-verify-email', function () {
        $user = \App\Models\User::first();
        if (!$user) {
            return 'No users found. Please create a user first.';
        }
        
        $contentLines = [
            'Thank you for creating an account with EventEase, your trusted partner for event discovery and ticket booking.',
            'To get started and access all our amazing features, please verify your email address by clicking the button below.',
            'This verification link will expire in 60 minutes.',
            'If you did not create an account with EventEase, no further action is required.'
        ];

        return view('emails.auth-notification', [
            'subject' => 'Welcome to EventEase - Verify Your Email Address',
            'headerTitle' => 'Email Verification',
            'greeting' => 'Welcome to EventEase, ' . $user->name . '!',
            'introLine' => '🎉 Welcome to EventEase! We\'re excited to have you join our community.',
            'contentLines' => $contentLines,
            'actionUrl' => '#',
            'actionText' => '✅ Verify Email Address',
            'importantNote' => '<strong>🔒 Security Note:</strong> This is an automated email. If you didn\'t create an EventEase account, you can safely ignore this message.',
            'closingLines' => [
                'Welcome to the EventEase community! 🎉',
                'Get ready to discover amazing events and book tickets with ease.'
            ]
        ]);
    })->name('preview.verify.email');
    
    // Preview password reset template
    Route::get('/preview-reset-password', function () {
        $user = \App\Models\User::first();
        if (!$user) {
            return 'No users found. Please create a user first.';
        }
        
        $contentLines = [
            'You are receiving this email because we received a password reset request for your EventEase account.',
            'This password reset link will expire in 60 minutes.',
            'If you did not request a password reset, no further action is required. Your account remains secure.'
        ];

        return view('emails.auth-notification', [
            'subject' => '🔐 Reset Your EventEase Password',
            'headerTitle' => 'Password Reset',
            'greeting' => 'Hello ' . $user->name . '!',
            'introLine' => '🔑 We received a request to reset your EventEase account password.',
            'contentLines' => $contentLines,
            'actionUrl' => '#',
            'actionText' => '🔐 Reset Password',
            'importantNote' => '<strong>🛡️ Security Reminder:</strong> For security reasons, please do not share this link with anyone. If you didn\'t request this reset, your account is still secure.',
            'closingLines' => [
                'If you have any questions or need help, feel free to contact our support team.',
                'Stay secure! 🛡️'
            ]
        ]);
    })->name('preview.reset.password');
    
    // Test route to assign organizer role (development only)
    Route::get('/test-assign-organizer/{email}', function($email) {
        $user = \App\Models\User::where('email', $email)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        
        $oldRole = $user->role;
        $user->role = 'organizer';
        $user->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'old_role' => $oldRole,
                'new_role' => $user->role
            ],
            'organizer_panel_url' => route('organizer.dashboard')
        ]);
    })->name('test.assign.organizer');
}





