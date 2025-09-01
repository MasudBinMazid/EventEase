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
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
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
| Make sure you have the 'admin' middleware alias registered (bootstrap/app.php).
*/
Route::prefix('admin')->name('admin.')->middleware(['auth','admin'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    // Users
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

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
}





