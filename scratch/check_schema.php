<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

// Create an in-memory sqlite db to run all migrations and inspect schema
config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);

$artisan = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$artisan->call('migrate', ['--force' => true]);

echo "=== DATABASE SCHEMA & MODEL MAPPING AUDIT ===\n\n";

$models = glob(__DIR__ . '/../app/Models/*.php');

foreach ($models as $modelFile) {
    $className = 'App\\Models\\' . basename($modelFile, '.php');
    if (!class_exists($className)) continue;
    $reflection = new ReflectionClass($className);
    if ($reflection->isAbstract()) continue;

    $instance = new $className;
    $table = $instance->getTable();

    if (!Schema::hasTable($table)) {
        echo "[ERROR] Model {$className} points to non-existent table '{$table}'!\n";
        continue;
    }

    $columns = Schema::getColumnListing($table);
    $fillables = $instance->getFillable();

    foreach ($fillables as $field) {
        if (!in_array($field, $columns)) {
            echo "[FILLABLE MISMATCH] Model {$className} has '{$field}' in \$fillable, but column is missing from table '{$table}'!\n";
        }
    }
}

echo "\nMigration and Schema audit complete.\n";
