<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\SearchController;
use App\Http\Controllers\Public\ProviderPublicController;
use App\Http\Controllers\Public\ServiceCategoryController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\Public\LocationAjaxController;
use App\Http\Controllers\Seeker;
use App\Http\Controllers\Provider;
use App\Http\Controllers\Admin;

// ─────────────────────────────────────────
// PUBLIC ROUTES (No auth required)
// ─────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jobs', [\App\Http\Controllers\Public\PublicJobController::class, 'index'])->name('jobs.index');
Route::get('/my-jobs', [HomeController::class, 'myJobs'])->name('my-jobs');
Route::get('/post-job', [HomeController::class, 'postJob'])->name('jobs.post');

// ─────────────────────────────────────────
// ADMIN SEED ROUTE (one-time, admin only)
// ─────────────────────────────────────────
Route::get('/admin/run-seed/demo-jobs', function () {
    if (!auth()->check() || auth()->user()->role !== 'admin') {
        abort(403);
    }
    \Artisan::call('db:seed', ['--class' => 'PublicJobBoardSeeder', '--force' => true]);
    return redirect()->route('jobs.index')->with('success', 'Demo jobs seeded successfully!');
})->middleware('auth');

Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/services', [ServiceCategoryController::class, 'index'])->name('services.index');
Route::get('/services/{slug}', [ServiceCategoryController::class, 'show'])->name('services.show');
Route::get('/providers/{user}', [ProviderPublicController::class, 'show'])->name('providers.show');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms-and-conditions', [PageController::class, 'terms'])->name('terms');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::post('/contact', [PageController::class, 'contactSubmit'])->name('contact.submit');

// AJAX - Location
Route::get('/ajax/areas/{district}', [LocationAjaxController::class, 'byDistrict'])->name('ajax.areas');

// ─────────────────────────────────────────
// AUTH ROUTES (Guests only)
// ─────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'attempt'])->name('login.attempt');

    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    // Google Social Auth Init & Callback
    Route::get('/auth/google', [\App\Http\Controllers\Auth\GoogleAuthController::class, 'redirect'])->name('auth.google');
    Route::get('/auth/google/callback', [\App\Http\Controllers\Auth\GoogleAuthController::class, 'callback']);
});

// Google Phone Completion (Accessible by guests with session or authenticated users missing phone)
Route::get('/auth/google/phone', [\App\Http\Controllers\Auth\GoogleAuthController::class, 'showPhoneForm'])->name('auth.google.phone');
Route::post('/auth/google/phone', [\App\Http\Controllers\Auth\GoogleAuthController::class, 'storePhone'])->name('auth.google.phone.store');

// ─────────────────────────────────────────
// OTP & PHONE VERIFICATION ROUTES (Guests & Unverified Users)
// ─────────────────────────────────────────
Route::get('/verify-otp', [OtpController::class, 'show'])->name('otp.show');
Route::post('/verify-otp', [OtpController::class, 'verify'])->name('otp.verify');
Route::post('/resend-otp', [OtpController::class, 'resend'])->name('otp.resend');
Route::get('/verify-otp/change-phone', [OtpController::class, 'showChangePhone'])->name('otp.change-phone');
Route::post('/verify-otp/change-phone', [OtpController::class, 'updatePhone'])->name('otp.change-phone.store');
Route::post('/verify-otp/cancel', [OtpController::class, 'cancel'])->name('otp.cancel');

// ─────────────────────────────────────────
// SHARED AUTH ROUTES
// ─────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LogoutController::class, 'destroy'])->name('logout');

    // Notification API (for Alpine bell component)
    Route::get('/notifications/bell', function () {
        $service = app(\App\Services\NotificationService::class);
        return response()->json($service->getBellData(auth()->user()));
    })->name('notifications.bell');

    Route::post('/notifications/read-all', function () {
        app(\App\Services\NotificationService::class)->markAllRead(auth()->user());
        return response()->json(['success' => true]);
    })->name('notifications.read-all');
});

// ─────────────────────────────────────────
// SEEKER ROUTES
// ─────────────────────────────────────────
Route::middleware(['auth', 'is.seeker', 'phone.verified'])
    ->prefix('seeker')
    ->name('seeker.')
    ->group(function () {
        Route::get('/dashboard', [Seeker\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile/edit', [Seeker\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [Seeker\ProfileController::class, 'update'])->name('profile.update');

        // Become a Provider Onboarding Application
        Route::get('/become-provider', [Seeker\BecomeProviderController::class, 'show'])->name('become-provider');
        Route::post('/become-provider', [Seeker\BecomeProviderController::class, 'store'])->name('become-provider.store');
        Route::get('/become-provider/status', [Seeker\BecomeProviderController::class, 'status'])->name('become-provider.status');

        // Job Requests
        Route::resource('job-requests', Seeker\JobRequestController::class);
        Route::post('/job-requests/{jobRequest}/cancel', [Seeker\JobRequestController::class, 'cancel'])->name('job-requests.cancel');

        // Route Aliases for backwards compatibility (prevents RouteNotFoundException for seeker.jobs.*)
        Route::get('/jobs-redirect', [Seeker\JobRequestController::class, 'index'])->name('jobs.index');
        Route::get('/jobs-redirect/create', [Seeker\JobRequestController::class, 'create'])->name('jobs.create');
        Route::get('/jobs-redirect/{jobRequest}', [Seeker\JobRequestController::class, 'show'])->name('jobs.show');

        // Bid Management
        Route::post('/bids/{bid}/accept', [Seeker\BidManageController::class, 'accept'])->name('bids.accept');
        Route::post('/bids/{bid}/reject', [Seeker\BidManageController::class, 'reject'])->name('bids.reject');

        // Direct Bookings
        Route::get('/services/{directService}/book', [Seeker\DirectBookingController::class, 'create'])->name('direct-booking.create');
        Route::post('/services/{directService}/book', [Seeker\DirectBookingController::class, 'store'])->name('direct-booking.store');

        // Bookings
        Route::get('/bookings', [Seeker\BookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [Seeker\BookingController::class, 'show'])->name('bookings.show');
        Route::post('/bookings/{booking}/complete', [Seeker\BookingController::class, 'complete'])->name('bookings.complete');
        Route::post('/bookings/{booking}/cancel', [Seeker\BookingController::class, 'cancel'])->name('bookings.cancel');

        // Payments
        Route::post('/payments/{booking}/initiate', [Seeker\PaymentController::class, 'initiate'])->name('payments.initiate');
        Route::match(['get', 'post'], '/payments/success', [Seeker\PaymentController::class, 'success'])->name('payments.success');
        Route::match(['get', 'post'], '/payments/fail', [Seeker\PaymentController::class, 'fail'])->name('payments.fail');
        Route::match(['get', 'post'], '/payments/cancel', [Seeker\PaymentController::class, 'cancel'])->name('payments.cancel');

        // Messages
        Route::get('/messages', [Seeker\MessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/{booking}', [Seeker\MessageController::class, 'show'])->name('messages.show');
        Route::post('/messages/{booking}', [Seeker\MessageController::class, 'send'])->name('messages.send');
        Route::get('/messages/{booking}/poll', [Seeker\MessageController::class, 'poll'])->name('messages.poll');

        // Reviews
        Route::get('/bookings/{booking}/review', [Seeker\ReviewController::class, 'create'])->name('reviews.create');
        Route::post('/bookings/{booking}/review', [Seeker\ReviewController::class, 'store'])->name('reviews.store');

        // Disputes
        Route::get('/bookings/{booking}/dispute', [Seeker\DisputeController::class, 'create'])->name('disputes.create');
        Route::post('/bookings/{booking}/dispute', [Seeker\DisputeController::class, 'store'])->name('disputes.store');
    });

// ─────────────────────────────────────────
// PROVIDER ROUTES
// ─────────────────────────────────────────
Route::middleware(['auth', 'is.provider', 'phone.verified'])
    ->prefix('provider')
    ->name('provider.')
    ->group(function () {
        Route::get('/dashboard', [Provider\DashboardController::class, 'index'])->name('dashboard');

        // Profile
        Route::get('/profile/setup', [Provider\ProfileController::class, 'setup'])->name('profile.setup');
        Route::get('/profile/edit', [Provider\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [Provider\ProfileController::class, 'update'])->name('profile.update');

        // Skills
        Route::get('/skills', [Provider\ProfileController::class, 'skills'])->name('skills.manage');
        Route::post('/skills', [Provider\ProfileController::class, 'updateSkills'])->name('skills.update');

        // Verification
        Route::get('/verification', [Provider\VerificationController::class, 'show'])->name('verification.show');
        Route::post('/verification', [Provider\VerificationController::class, 'submit'])->name('verification.submit');

        // Direct Services
        Route::resource('services', Provider\DirectServiceController::class);

        // Browse Job Requests
        Route::get('/jobs', [Provider\JobBrowseController::class, 'index'])->name('jobs.index');
        Route::get('/jobs/{jobRequest}', [Provider\JobBrowseController::class, 'show'])->name('jobs.show');

        // Bids
        Route::post('/jobs/{jobRequest}/bid', [Provider\BidController::class, 'store'])->name('bids.store');
        Route::put('/bids/{bid}', [Provider\BidController::class, 'update'])->name('bids.update');
        Route::delete('/bids/{bid}', [Provider\BidController::class, 'destroy'])->name('bids.destroy');
        Route::get('/bids', [Provider\BidController::class, 'index'])->name('bids.index');

        // Bookings
        Route::get('/bookings', [Provider\BookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [Provider\BookingController::class, 'show'])->name('bookings.show');
        Route::post('/bookings/{booking}/confirm', [Provider\BookingController::class, 'confirm'])->name('bookings.confirm');
        Route::post('/bookings/{booking}/start', [Provider\BookingController::class, 'start'])->name('bookings.start');
        Route::post('/bookings/{booking}/complete', [Provider\BookingController::class, 'complete'])->name('bookings.complete');
        Route::post('/bookings/{booking}/cancel', [Provider\BookingController::class, 'cancel'])->name('bookings.cancel');

        // Messages
        Route::get('/messages', [Provider\MessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/{booking}', [Provider\MessageController::class, 'show'])->name('messages.show');
        Route::post('/messages/{booking}', [Provider\MessageController::class, 'send'])->name('messages.send');
        Route::get('/messages/{booking}/poll', [Provider\MessageController::class, 'poll'])->name('messages.poll');

        // Earnings & Withdrawals
        Route::get('/earnings', [Provider\EarningsController::class, 'index'])->name('earnings.index');
        Route::get('/withdrawals', [Provider\WithdrawalController::class, 'index'])->name('withdrawals.index');
        Route::get('/withdrawals/create', [Provider\WithdrawalController::class, 'create'])->name('withdrawals.create');
        Route::post('/withdrawals', [Provider\WithdrawalController::class, 'store'])->name('withdrawals.store');
    });

// ─────────────────────────────────────────
// ADMIN ROUTES
// ─────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin auth (separate from main auth)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [Admin\AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [Admin\AuthController::class, 'login'])->name('login.attempt');
    });

    Route::middleware(['auth', 'is.admin'])->group(function () {
        Route::post('/logout', [Admin\AuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // Users
        Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [Admin\UserController::class, 'show'])->name('users.show');
        Route::post('/users/{user}/suspend', [Admin\UserController::class, 'suspend'])->name('users.suspend');
        Route::post('/users/{user}/activate', [Admin\UserController::class, 'activate'])->name('users.activate');
        Route::post('/users/{user}/verify-otp', [Admin\UserController::class, 'verifyOtp'])->name('users.verify-otp');
        Route::post('/users/{user}/verify-profile', [Admin\UserController::class, 'verifyProfile'])->name('users.verify-profile');

        // Provider Verifications
        Route::get('/verifications', [Admin\ProviderVerificationController::class, 'index'])->name('verifications.index');
        Route::get('/verifications/{user}', [Admin\ProviderVerificationController::class, 'show'])->name('verifications.show');
        Route::post('/verifications/{user}/approve', [Admin\ProviderVerificationController::class, 'approve'])->name('verifications.approve');
        Route::post('/verifications/{user}/reject', [Admin\ProviderVerificationController::class, 'reject'])->name('verifications.reject');

        // Job Requests
        Route::get('/job-requests', [Admin\JobRequestController::class, 'index'])->name('job-requests.index');
        Route::get('/job-requests/{jobRequest}', [Admin\JobRequestController::class, 'show'])->name('job-requests.show');

        // Bookings
        Route::get('/bookings', [Admin\BookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [Admin\BookingController::class, 'show'])->name('bookings.show');

        // Payments
        Route::get('/payments', [Admin\PaymentController::class, 'index'])->name('payments.index');

        // Withdrawals
        Route::get('/withdrawals', [Admin\WithdrawalController::class, 'index'])->name('withdrawals.index');
        Route::get('/withdrawals/{withdrawal}', [Admin\WithdrawalController::class, 'show'])->name('withdrawals.show');
        Route::post('/withdrawals/{withdrawal}/approve', [Admin\WithdrawalController::class, 'approve'])->name('withdrawals.approve');
        Route::post('/withdrawals/{withdrawal}/reject', [Admin\WithdrawalController::class, 'reject'])->name('withdrawals.reject');

        // Disputes
        Route::get('/disputes', [Admin\DisputeController::class, 'index'])->name('disputes.index');
        Route::get('/disputes/{dispute}', [Admin\DisputeController::class, 'show'])->name('disputes.show');
        Route::post('/disputes/{dispute}/resolve', [Admin\DisputeController::class, 'resolve'])->name('disputes.resolve');

        // Categories
        Route::resource('categories', Admin\CategoryController::class);
        Route::resource('categories.subcategories', Admin\SubcategoryController::class)->shallow();

        // Reviews
        Route::get('/reviews', [Admin\ReviewController::class, 'index'])->name('reviews.index');
        Route::post('/reviews/{review}/hide', [Admin\ReviewController::class, 'hide'])->name('reviews.hide');
        Route::post('/reviews/{review}/show', [Admin\ReviewController::class, 'showReview'])->name('reviews.show');

        // Featured
        Route::get('/featured', [Admin\FeaturedController::class, 'index'])->name('featured.index');
        Route::post('/featured/{provider}/grant', [Admin\FeaturedController::class, 'grant'])->name('featured.grant');

        // SMS
        Route::get('/sms/send', [Admin\SmsController::class, 'show'])->name('sms.show');
        Route::post('/sms/send', [Admin\SmsController::class, 'send'])->name('sms.send');
        Route::get('/sms/logs', [Admin\SmsController::class, 'logs'])->name('sms.logs');

        // Locations
        Route::get('/locations', [Admin\LocationController::class, 'index'])->name('locations.index');
        Route::resource('locations/districts', Admin\DistrictController::class)->names('districts');
        Route::resource('locations.areas', Admin\AreaController::class)->shallow();

        // Fallback Route Alias (Prevents RouteNotFoundException for locations.index)
        Route::get('/admin/locations-redirect', [Admin\LocationController::class, 'index'])->name('locations.index');

        // Settings
        Route::get('/settings', [Admin\SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [Admin\SettingController::class, 'update'])->name('settings.update');
        Route::get('/settings/api', [Admin\SettingController::class, 'apiIndex'])->name('settings.api');
        Route::post('/settings/api', [Admin\SettingController::class, 'updateApi'])->name('settings.api.update');
        Route::get('/settings/api/check-sms-balance', [Admin\SettingController::class, 'checkSmsBalance'])->name('settings.api.sms-balance');
        Route::post('/settings/api/test-sms', [Admin\SettingController::class, 'testSms'])->name('settings.api.test-sms');

        // Contact Messages
        Route::get('/contact-messages', [Admin\ContactMessageController::class, 'index'])->name('contact-messages.index');
        Route::get('/contact-messages/{contactMessage}', [Admin\ContactMessageController::class, 'show'])->name('contact-messages.show');
        Route::delete('/contact-messages/{contactMessage}', [Admin\ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');

        // Reports
        Route::get('/reports', [Admin\ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [Admin\ReportController::class, 'export'])->name('reports.export');
    });
});

// ─────────────────────────────────────────
// Payment Gateway Webhooks / IPN (Unauthenticated)
// ─────────────────────────────────────────
Route::post('/seeker/payments/ipn', [App\Http\Controllers\Seeker\PaymentController::class, 'ipn'])->name('seeker.payments.ipn');
Route::post('/payments/ipn', [App\Http\Controllers\Seeker\PaymentController::class, 'ipn'])->name('payments.ipn');

// ─────────────────────────────────────────
// SYSTEM DEPLOY (Admin button - runs all post-deploy artisan commands)
// ─────────────────────────────────────────
Route::get('/system-deploy-force', function () {
    $log = [];

    try {
        // 1. Run DB Migrations
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $log[] = '✅ Migrations চালানো হয়েছে';

        // 2. Seed default service categories if missing
        try {
            \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'ServiceCategorySeeder', '--force' => true]);
            $log[] = '✅ Service categories seed হয়েছে';
        } catch (\Exception $e) {
            $log[] = '⚠️ Seed skipped: ' . $e->getMessage();
        }

        // 3. Storage symlink
        try {
            \Illuminate\Support\Facades\Artisan::call('storage:link', ['--force' => true]);
            $log[] = '✅ Storage link তৈরি হয়েছে';
        } catch (\Exception $se) {
            $log[] = '⚠️ Storage link skipped (already exists)';
        }

        // 4. Clear all caches
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        $log[] = '✅ সব Cache ও Compiled files মুছে দেওয়া হয়েছে';

        // 5. Delete compiled view files directly
        foreach (glob(storage_path('framework/views/*.php')) as $viewFile) {
            @unlink($viewFile);
        }

        // 6. Delete Vite "hot" file (prevents dev-server asset loading on production)
        $hotPaths = [
            public_path('hot'),
            base_path('public/hot'),
        ];
        foreach ($hotPaths as $hotFile) {
            if (file_exists($hotFile)) {
                @unlink($hotFile);
                $log[] = '✅ Vite hot ফাইল মুছে দেওয়া হয়েছে';
            }
        }

        // 7. Fix storage permissions
        @chmod(storage_path(), 0755);
        @chmod(storage_path('logs'), 0755);
        @chmod(storage_path('framework'), 0755);
        $log[] = '✅ Storage permissions ঠিক করা হয়েছে';

        $message = implode(' | ', $log);

        if (request()->has('redirect')) {
            return redirect(request('redirect'))->with('success', $message);
        }

        return redirect()->back()->with('success', $message);

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Deploy Error: ' . $e->getMessage());
    }
})->name('system.deploy');

Route::get('/seed-dummy-data', function () {
    if (!auth()->check() || auth()->user()->role !== 'admin') {
        abort(403, 'Unauthorized action.');
    }
    try {
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'DummyDataSeeder', '--force' => true]);
        return "Dummy data seeded successfully!<br><br><a href='/search'>Go to Search Page</a>";
    } catch (\Exception $e) {
        return "Error seeding dummy data: " . $e->getMessage();
    }
})->middleware('auth');
