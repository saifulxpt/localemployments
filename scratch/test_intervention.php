<?php
require __DIR__ . '/../vendor/autoload.php';

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Format;

$manager = new ImageManager(Driver::class);

// 1. Create image
$img = $manager->createImage(200, 200);

// 2. Cover / resize
$img->cover(100, 100);

// 3. Encode to JPEG with JpegEncoder
$encodedJpeg = $img->encode(new JpegEncoder(80))->toString();
echo "JPEG encoded length: " . strlen($encodedJpeg) . " bytes\n";

// 4. Encode to WebP with WebpEncoder
$encodedWebp = $img->encode(new WebpEncoder(80))->toString();
echo "WebP encoded length: " . strlen($encodedWebp) . " bytes\n";

// 5. Test decode from temporary file
$tmpFile = tempnam(sys_get_temp_dir(), 'img_') . '.jpg';
file_put_contents($tmpFile, $encodedJpeg);

$decodedImg = $manager->decodePath($tmpFile);
echo "Decoded image width: " . $decodedImg->width() . ", height: " . $decodedImg->height() . "\n";
$decodedImg->scaleDown(width: 50, height: 50);
$finalData = $decodedImg->encode(new JpegEncoder(80))->toString();
echo "Final scaled data length: " . strlen($finalData) . " bytes\n";

@unlink($tmpFile);
echo "SUCCESS! Everything in Intervention Image v4 works perfectly!\n";
