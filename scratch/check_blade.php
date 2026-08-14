<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$bladeFiles = glob_recursive(__DIR__ . '/../resources/views', '*.blade.php');

echo "=== CHECKING BLADE TEMPLATES SYNTAX & INCLUDES ===\n";

$bladeCompiler = app('blade.compiler');

$syntaxErrors = [];
$missingIncludes = [];

foreach ($bladeFiles as $file) {
    $content = file_get_contents($file);
    
    // Check @include('...')
    preg_match_all("/@include\(\s*['\"]([^'\"]+)['\"]/", $content, $incMatches);
    if (!empty($incMatches[1])) {
        foreach ($incMatches[1] as $inc) {
            $incPath = __DIR__ . '/../resources/views/' . str_replace('.', '/', $inc) . '.blade.php';
            if (!file_exists($incPath)) {
                $missingIncludes[] = [
                    'file' => str_replace(__DIR__ . '/../', '', $file),
                    'include' => $inc,
                ];
            }
        }
    }

    // Check @extends('...')
    preg_match_all("/@extends\(\s*['\"]([^'\"]+)['\"]/", $content, $extMatches);
    if (!empty($extMatches[1])) {
        foreach ($extMatches[1] as $ext) {
            $extPath = __DIR__ . '/../resources/views/' . str_replace('.', '/', $ext) . '.blade.php';
            if (!file_exists($extPath)) {
                $missingIncludes[] = [
                    'file' => str_replace(__DIR__ . '/../', '', $file),
                    'extends' => $ext,
                ];
            }
        }
    }

    // Compile blade syntax to test for parser errors
    try {
        $compiled = $bladeCompiler->compileString($content);
    } catch (\Throwable $e) {
        $syntaxErrors[] = [
            'file' => str_replace(__DIR__ . '/../', '', $file),
            'error' => $e->getMessage(),
        ];
    }
}

echo "1. MISSING @include / @extends: " . count($missingIncludes) . "\n";
foreach ($missingIncludes as $m) {
    echo "  - [{$m['file']}]: " . ($m['include'] ?? $m['extends']) . "\n";
}

echo "\n2. BLADE SYNTAX ERRORS: " . count($syntaxErrors) . "\n";
foreach ($syntaxErrors as $s) {
    echo "  - [{$s['file']}]: {$s['error']}\n";
}

function glob_recursive($dir, $pattern) {
    $files = glob($dir . '/' . $pattern);
    foreach (glob($dir . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $subdir) {
        $files = array_merge($files, glob_recursive($subdir, $pattern));
    }
    return $files;
}
