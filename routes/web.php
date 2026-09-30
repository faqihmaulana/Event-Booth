<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\BoothController;
use App\Http\Controllers\CategoriController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\StoreBookingController;
use App\Http\Controllers\SecurePaymentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\ReportController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->middleware('guest')->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->middleware('guest')->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('guest')->name('password.update');
Route::get('/email/verify', [AuthController::class, 'showVerificationNotice'])->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->middleware('signed')->name('verification.verify');
Route::post('/email/verification-notification', [AuthController::class, 'resendVerification'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/', [BannerController::class, 'getHomepageBanner'])->name('home');
Route::get('/contact', [BannerController::class, 'contact'])->name('contact');
Route::get('/about', [BannerController::class, 'about'])->name('pages.about');
Route::get('/event', [EventController::class, 'indexPublic'])->name('event');
Route::get('/booking-booth/{event_id?}', [CategoriController::class, 'booking'])->name('booking-booth');

Route::prefix('payment')->group(function () {
    Route::get('/finish', [PaymentController::class, 'finish'])->name('payment.finish');
    Route::get('/unfinish', [PaymentController::class, 'unfinish'])->name('payment.unfinish');
    Route::get('/error', [PaymentController::class, 'error'])->name('payment.error');
});
Route::post('/webhook/midtrans', [BookingController::class, 'handleMidtransNotification'])->name('webhook.midtrans');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/api/booths', [BoothController::class, 'getBooths'])->name('api.booths');
    Route::post('/bookings', StoreBookingController::class)->name('booking.store');
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('booking.my-bookings');
    Route::get('/pages/pesanan', [BookingController::class, 'pesananIndex'])->name('pages.pesanan');
    Route::get('/api/booking/{booking}', [BookingController::class, 'getBookingDetail'])->name('api.booking.detail');
    Route::get('/bookings/{booking}/payment-status', [SecurePaymentController::class, 'status'])->name('booking.payment-status');
    Route::get('/booking/{booking}/payment-token', [SecurePaymentController::class, 'token'])->name('booking.payment-token');
    Route::post('/booking/{booking}/process-payment', [SecurePaymentController::class, 'process'])->name('booking.process-payment');
    Route::get('/booking/{id}/invoice', [BookingController::class, 'generateInvoice'])->name('booking.invoice');

    Route::prefix('payment')->group(function () {
        Route::get('/{booking}', [PaymentController::class, 'show'])->name('payment.show');
        Route::post('/{booking}/process', [SecurePaymentController::class, 'process'])->name('payment.process');
    });

    Route::get('/booking/success/{booking}', function ($bookingId) {
        $booking = \App\Models\Booking::with('booth')->findOrFail($bookingId);
        abort_unless($booking->user_id === auth()->id() || auth()->user()->isAdmin(), 403);
        return view('booking.success', compact('booking'));
    })->name('booking.success');

    Route::get('/booking/expired/{booking}', function ($bookingId) {
        $booking = \App\Models\Booking::with('booth')->findOrFail($bookingId);
        abort_unless($booking->user_id === auth()->id() || auth()->user()->isAdmin(), 403);
        return view('booking.expired', compact('booking'));
    })->name('booking.expired');

    Route::get('/profile', [TenantController::class, 'showProfile'])->name('tenant.profile');
    Route::post('/profile', [TenantController::class, 'updateProfile'])->name('tenant.profile.update');

    Route::prefix('tenant/chat')->group(function () {
        Route::get('/', [ChatController::class, 'tenantChat'])->name('chat-tenant');
        Route::get('/admins', [ChatController::class, 'getAdminList']);
        Route::get('/messages/{userId}', [ChatController::class, 'getMessages']);
        Route::post('/send', [ChatController::class, 'sendMessage']);
        Route::get('/unread-count', [ChatController::class, 'getUnreadCount']);
        Route::get('/messages-all', [ChatController::class, 'getTenantMessages']);
        Route::post('/send-to-all-admins', [ChatController::class, 'sendToAllAdmins']);
    });
});

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::view('/chat', 'admin.chat')->name('chat');
    Route::get('/maps-booth', [BoothController::class, 'index'])->name('maps-booth');
    Route::get('/transaction', [TransactionController::class, 'index'])->name('transaction');
    Route::get('/report', [ReportController::class, 'index'])->name('report');
    Route::get('/report/export-excel', [ReportController::class, 'exportExcel'])->name('report.export-excel');
    Route::get('/report/event-performance', [ReportController::class, 'getEventPerformance'])->name('report.event-performance');
    Route::get('/report/booking/{id}', [ReportController::class, 'getBookingDetails'])->name('report.booking-details');

    Route::prefix('admin/bookings')->group(function () {
        Route::get('/', [BookingController::class, 'index'])->name('admin.bookings.index');
        Route::get('/{booking}', [BookingController::class, 'show'])->name('admin.bookings.show');
        Route::put('/{booking}/status', [BookingController::class, 'updateStatus'])->name('admin.bookings.update-status');
        Route::delete('/{booking}', [BookingController::class, 'destroy'])->name('admin.bookings.destroy');
        Route::get('/{booking}/payment-status', [SecurePaymentController::class, 'status'])->name('admin.bookings.payment-status');
    });

    Route::get('/statistics', [StatisticsController::class, 'index'])->name('admin.statistics');
    Route::get('/statistics/event-stats', [StatisticsController::class, 'getEventStats'])->name('admin.statistics.event-stats');
    Route::get('/statistics/section-stats', [StatisticsController::class, 'getSectionStats'])->name('admin.statistics.section-stats');
    Route::get('/statistics/hourly-stats', [StatisticsController::class, 'getHourlyStats'])->name('admin.statistics.hourly-stats');
    Route::get('/statistics/city-stats', [StatisticsController::class, 'getCityStats'])->name('admin.statistics.city-stats');

    Route::get('/manage-user', [UserController::class, 'index'])->name('manage-user');
    Route::post('/manage-user/store', [UserController::class, 'store'])->name('user.store');
    Route::put('/manage-user/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/manage-user/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::get('/manage-banner', [BannerController::class, 'index'])->name('manage-banner');
    Route::put('/manage-banner/update', [BannerController::class, 'update'])->name('update-banner');
    Route::post('/admin/gallery/add', [BannerController::class, 'addGalleryImages'])->name('add-gallery-images');
    Route::get('/admin/gallery/{id}', [BannerController::class, 'getGallery'])->name('get-gallery');
    Route::post('/admin/gallery/{id}', [BannerController::class, 'updateGallery'])->name('update-gallery');
    Route::post('/admin/gallery/order', [BannerController::class, 'updateGalleryOrder'])->name('update-gallery-order');
    Route::delete('/admin/gallery/{id}', [BannerController::class, 'deleteGalleryImage'])->name('delete-gallery');

    Route::get('/manage-event', [EventController::class, 'indexAdmin'])->name('manage-event');
    Route::post('/manage-event/store', [EventController::class, 'store'])->name('event.store');
    Route::put('/manage-event/{id}', [EventController::class, 'update'])->name('event.update');
    Route::delete('/manage-event/{id}', [EventController::class, 'destroy'])->name('event.destroy');
    Route::get('/categori-booth', [CategoriController::class, 'index'])->name('categori-booth');
    Route::post('/categori-booth/store', [CategoriController::class, 'store'])->name('booth.store');
    Route::put('/categori-booth/{id}', [CategoriController::class, 'update'])->name('booth.update');
    Route::delete('/categori-booth/{id}', [CategoriController::class, 'destroy'])->name('booth.destroy');

    Route::prefix('admin/booths')->group(function () {
        Route::post('/', [BoothController::class, 'store'])->name('admin.booths.store');
        Route::put('/{id}', [BoothController::class, 'update'])->name('admin.booths.update');
        Route::delete('/{id}', [BoothController::class, 'destroy'])->name('admin.booths.destroy');
        Route::post('/update-position', [BoothController::class, 'updatePosition'])->name('admin.booths.update-position');
        Route::post('/initialize', [BoothController::class, 'initializeBooths'])->name('admin.booths.initialize');
        Route::get('/api/get-booths', [BoothController::class, 'getBooths'])->name('admin.booths.api.get-booths');
        Route::get('/api/main-events', [BoothController::class, 'getMainEvents'])->name('admin.booths.api.main-events');
    });

    Route::prefix('admin/chat')->group(function () {
        Route::get('/', [ChatController::class, 'adminChat'])->name('admin.chat');
        Route::get('/tenants', [ChatController::class, 'getTenantList']);
        Route::get('/messages/{userId}', [ChatController::class, 'getMessages']);
        Route::post('/send', [ChatController::class, 'sendMessage']);
        Route::get('/unread-count', [ChatController::class, 'getUnreadCount']);
        Route::get('/notifications', [ChatController::class, 'getAdminNotifications']);
        Route::get('/notifications/count', [ChatController::class, 'getAdminUnreadCount']);
        Route::put('/notifications/{messageId}/read', [ChatController::class, 'markNotificationAsRead']);
        Route::put('/notifications/sender/{senderId}/read', [ChatController::class, 'markAllFromSenderAsRead']);
    });
});
