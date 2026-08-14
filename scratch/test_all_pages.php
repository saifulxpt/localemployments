<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Route;

echo "=== DYNAMIC SYSTEMATIC PAGE & FEATURE AUDIT ===\n\n";

$issues = [];
$controllers = glob_recursive(__DIR__ . '/../app/Http/Controllers', '*.php');

// 1. Check all view() calls in all controllers dynamically
foreach ($controllers as $file) {
    $content = file_get_contents($file);
    preg_match_all("/view\(\s*['\"]([^'\"]+)['\"]/", $content, $matches);
    if (!empty($matches[1])) {
        foreach ($matches[1] as $viewName) {
            $viewPath = __DIR__ . '/../resources/views/' . str_replace('.', '/', $viewName) . '.blade.php';
            if (!file_exists($viewPath)) {
                $issues[] = [
                    'severity' => 'CRITICAL',
                    'category' => 'Missing View File',
                    'details' => "View '{$viewName}' not found in " . str_replace(__DIR__ . '/../', '', $file),
                ];
            }
        }
    }
}

// 2. Check Payment routes
$paymentRoutes = ['seeker.payments.success', 'seeker.payments.fail', 'seeker.payments.cancel', 'seeker.payments.ipn'];
foreach ($paymentRoutes as $rName) {
    $route = Route::getRoutes()->getByName($rName);
    if ($route) {
        if (!in_array('POST', $route->methods())) {
            $issues[] = [
                'severity' => 'CRITICAL',
                'category' => 'Payment Gateway Error',
                'details' => "Route '{$rName}' does not accept POST.",
            ];
        }
        if ($rName === 'seeker.payments.ipn' && in_array('auth', $route->middleware())) {
            $issues[] = [
                'severity' => 'CRITICAL',
                'category' => 'Payment IPN Middleware Error',
                'details' => "Route '{$rName}' requires auth.",
            ];
        }
    }
}

// 3. Check @stack('scripts') in layouts
$layouts = ['seeker', 'provider', 'admin', 'public'];
foreach ($layouts as $l) {
    $lPath = __DIR__ . "/../resources/views/layouts/{$l}.blade.php";
    if (file_exists($lPath)) {
        $lContent = file_get_contents($lPath);
        if (!str_contains($lContent, "@stack('scripts')")) {
            $issues[] = [
                'severity' => 'HIGH',
                'category' => 'Missing @stack(scripts)',
                'details' => "Layout '{$l}' does not contain @stack('scripts')",
            ];
        }
    }
}

echo "TOTAL ISSUES DETECTED: " . count($issues) . "\n\n";
if (empty($issues)) {
    echo "🎉 ALL SYSTEM AUDITS PASSED WITH ZERO ISSUES!\n";
} else {
    foreach ($issues as $i => $issue) {
        echo ($i + 1) . ". [{$issue['severity']}] {$issue['category']}\n";
        echo "   -> {$issue['details']}\n\n";
    }
}

function glob_recursive($dir, $pattern) {
    $files = glob($dir . '/' . $pattern);
    foreach (glob($dir . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $subdir) {
        $files = array_merge($files, glob_recursive($subdir, $pattern));
    }
    return $files;
}
