<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Route;

echo "=== SYSTEMATIC PAGE & FEATURE AUDIT ===\n\n";

$issues = [];

// 1. Check Missing Views
$missingViews = [
    'admin.locations.districts.index' => 'app/Http/Controllers/Admin/DistrictController.php',
    'seeker.direct-booking.create' => 'app/Http/Controllers/Seeker/DirectBookingController.php',
    'seeker.disputes.create' => 'app/Http/Controllers/Seeker/DisputeController.php',
    'seeker.job-requests.edit' => 'app/Http/Controllers/Seeker/JobRequestController.php',
    'seeker.reviews.create' => 'app/Http/Controllers/Seeker/ReviewController.php',
];

foreach ($missingViews as $view => $caller) {
    $viewPath = __DIR__ . '/../resources/views/' . str_replace('.', '/', $view) . '.blade.php';
    if (!file_exists($viewPath)) {
        $issues[] = [
            'severity' => 'CRITICAL',
            'category' => 'Missing View (500 Error on Click)',
            'details' => "View '{$view}' does not exist, called by {$caller}",
        ];
    }
}

// 2. Check SSLCommerz / Payment HTTP Methods & CSRF
$paymentRoutes = ['seeker.payments.success', 'seeker.payments.fail', 'seeker.payments.cancel', 'seeker.payments.ipn'];
foreach ($paymentRoutes as $rName) {
    $route = Route::getRoutes()->getByName($rName);
    if ($route) {
        if (!in_array('POST', $route->methods())) {
            $issues[] = [
                'severity' => 'CRITICAL',
                'category' => 'Payment Gateway Crash (405 Method Not Allowed)',
                'details' => "Route '{$rName}' only accepts GET, but SSLCommerz callbacks and IPN submit via POST.",
            ];
        }
        if ($rName === 'seeker.payments.ipn' && in_array('auth', $route->middleware())) {
            $issues[] = [
                'severity' => 'CRITICAL',
                'category' => 'Payment IPN Blocked (401 Unauthorized)',
                'details' => "Payment IPN route '{$rName}' has 'auth' middleware, blocking webhook calls from payment gateway.",
            ];
        }
    }
}

// 3. Check Provider Booking completion / earnings logic
$bookingService = new ReflectionClass(\App\Services\BookingService::class);
// Check if BookingService methods exist and are called properly

// 4. Check Seeker Booking show line 45 typo:
$bookingShowBlade = file_get_contents(__DIR__ . '/../resources/views/seeker/bookings/show.blade.php');
if (str_contains($bookingShowBlade, '$booking->jobRequest->description ?? \'কোনো নোট নেই\'')) {
    // In directService block, it checks $booking->jobRequest->description instead of $booking->seeker_note or $booking->directService!
    $issues[] = [
        'severity' => 'HIGH',
        'category' => 'UI / Null Pointer Bug',
        'details' => "In seeker/bookings/show.blade.php (Line 45): In directService section, it attempts to read '\$booking->jobRequest->description' which is NULL for direct bookings, crashing or showing blank.",
    ];
}

// 5. Check Provider Booking show for similar direct booking issues
$providerBookingShowBlade = file_get_contents(__DIR__ . '/../resources/views/provider/bookings/show.blade.php');
if (str_contains($providerBookingShowBlade, '$booking->jobRequest->description ??')) {
    $issues[] = [
        'severity' => 'HIGH',
        'category' => 'UI / Null Pointer Bug',
        'details' => "In provider/bookings/show.blade.php: Direct booking description references jobRequest.",
    ];
}

// 6. Check Admin Dashboard / Analytics
$adminDashboard = file_get_contents(__DIR__ . '/../app/Http/Controllers/Admin/DashboardController.php');

// Output all identified issues
echo "TOTAL ISSUES DETECTED: " . count($issues) . "\n\n";
foreach ($issues as $i => $issue) {
    echo ($i + 1) . ". [{$issue['severity']}] {$issue['category']}\n";
    echo "   -> {$issue['details']}\n\n";
}
