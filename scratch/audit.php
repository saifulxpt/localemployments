<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

echo "=== DEEP LOCALEMPLOYMENTS SYSTEM AUDIT ===\n\n";

$routes = collect(Route::getRoutes())->mapWithKeys(function ($route) {
    return [$route->getName() => [
        'uri' => $route->uri(),
        'action' => $route->getActionName(),
        'methods' => $route->methods(),
        'middleware' => $route->middleware(),
    ]];
})->filter();

$bladeFiles = glob_recursive(__DIR__ . '/../resources/views', '*.blade.php');
$controllers = glob_recursive(__DIR__ . '/../app/Http/Controllers', '*.php');

echo "--- 1. CONTROLLER ACTIONS CHECK ---\n";
$missingControllerMethods = [];
foreach (Route::getRoutes() as $r) {
    $action = $r->getActionName();
    if (str_contains($action, '@')) {
        [$ctrl, $method] = explode('@', $action);
        if (!class_exists($ctrl)) {
            echo "  [CRITICAL] Controller class missing: {$ctrl}\n";
        } elseif (!method_exists($ctrl, $method)) {
            echo "  [CRITICAL] Method missing in controller: {$ctrl}@{$method} (Route: {$r->getName()})\n";
        }
    }
}

echo "\n--- 2. VIEW FILES CALLED BY CONTROLLERS ---\n";
foreach ($controllers as $file) {
    $content = file_get_contents($file);
    preg_match_all("/view\(\s*['\"]([^'\"]+)['\"]/", $content, $matches);
    if (!empty($matches[1])) {
        foreach ($matches[1] as $viewName) {
            $viewPath = __DIR__ . '/../resources/views/' . str_replace('.', '/', $viewName) . '.blade.php';
            if (!file_exists($viewPath)) {
                echo "  [MISSING VIEW] {$viewName} called in " . str_replace(__DIR__ . '/../', '', $file) . "\n";
            }
        }
    }
}

echo "\n--- 3. CHECKING ALL MODEL RELATIONSHIPS & TABLES ---\n";
$models = glob(__DIR__ . '/../app/Models/*.php');
foreach ($models as $modelFile) {
    $className = 'App\\Models\\' . basename($modelFile, '.php');
    if (class_exists($className)) {
        $reflection = new ReflectionClass($className);
        if (!$reflection->isAbstract()) {
            try {
                $instance = new $className;
                $table = $instance->getTable();
                if (!Schema::hasTable($table)) {
                    echo "  [DB TABLE MISSING] Table '{$table}' for model {$className} does not exist in schema!\n";
                }
                
                // Test relations
                foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                    if ($method->getNumberOfParameters() === 0 && $method->class === $className) {
                        try {
                            $res = $method->invoke($instance);
                            if ($res instanceof \Illuminate\Database\Eloquent\Relations\Relation) {
                                // Relation is valid syntax
                            }
                        } catch (\Throwable $re) {
                            // Some methods might not be relations
                        }
                    }
                }
            } catch (\Throwable $e) {
                echo "  [MODEL ERROR] {$className}: " . $e->getMessage() . "\n";
            }
        }
    }
}

echo "\n--- 4. CHECKING PAYMENT GATEWAY / SSLCOMMERZ / BKASH / NAGAD ROUTING & MIDDLEWARE ---\n";
foreach (['seeker.payments.ipn', 'seeker.payments.success', 'seeker.payments.fail', 'seeker.payments.cancel'] as $pRoute) {
    if (!$routes->has($pRoute)) {
        echo "  [PAYMENT ROUTE MISSING] {$pRoute}\n";
    } else {
        $rInfo = $routes->get($pRoute);
        echo "  [PAYMENT ROUTE OK] {$pRoute} -> URI: {$rInfo['uri']}, Middleware: " . implode(',', $rInfo['middleware']) . "\n";
    }
}

function glob_recursive($dir, $pattern) {
    $files = glob($dir . '/' . $pattern);
    foreach (glob($dir . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $subdir) {
        $files = array_merge($files, glob_recursive($subdir, $pattern));
    }
    return $files;
}
