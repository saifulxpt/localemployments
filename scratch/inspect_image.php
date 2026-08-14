<?php
require __DIR__ . '/../vendor/autoload.php';

echo "Intervention Image inspection:\n";
if (class_exists(\Intervention\Image\ImageManager::class)) {
    $methods = get_class_methods(\Intervention\Image\ImageManager::class);
    print_r($methods);
} else {
    echo "Class does not exist!\n";
}
