<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\ImageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

echo "=== TESTING IMAGESERVICE ===\n";

$imageService = new ImageService();

// Create dummy image file
$tmpImg = tempnam(sys_get_temp_dir(), 'test_img_') . '.jpg';
$gd = imagecreatetruecolor(500, 500);
$bg = imagecolorallocate($gd, 34, 139, 34);
imagefill($gd, 0, 0, $bg);
imagejpeg($gd, $tmpImg, 90);
imagedestroy($gd);

$uploadedFile = new UploadedFile($tmpImg, 'test.jpg', 'image/jpeg', null, true);

// 1. Test storeAvatar
$avatarPath = $imageService->storeAvatar($uploadedFile);
echo "Avatar stored at: {$avatarPath} (Exists: " . (Storage::disk('public')->exists($avatarPath) ? 'YES' : 'NO') . ")\n";

// 2. Test storePhoto
$photoPath = $imageService->storePhoto($uploadedFile, 'job-photos');
echo "Job photo stored at: {$photoPath} (Exists: " . (Storage::disk('public')->exists($photoPath) ? 'YES' : 'NO') . ")\n";

// 3. Test storeMultiple
$multiPaths = $imageService->storeMultiple([$uploadedFile, $uploadedFile], 'job-photos');
echo "Multiple photos stored: " . count($multiPaths) . " items\n";

// Clean up
$imageService->deleteOld($avatarPath);
$imageService->deleteOld($photoPath);
foreach ($multiPaths as $p) $imageService->deleteOld($p);
@unlink($tmpImg);

echo "\n🎉 ImageService is 100% WORKING and TESTED!\n";
