<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;

class ImageService
{
    private ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(Driver::class);
    }

    /**
     * Process and store avatar image.
     * Resizes to 400x400, compresses to 80%, stores in storage/app/public/avatars.
     */
    public function storeAvatar(UploadedFile $file, ?string $oldPath = null): string
    {
        $this->deleteOld($oldPath);

        $filename = 'avatars/' . Str::uuid() . '.jpg';
        $image    = $this->manager->decodePath($file->getRealPath());
        $image->cover(400, 400);

        Storage::disk('public')->put($filename, $image->encode(new JpegEncoder(80))->toString());
        return $filename;
    }

    /**
     * Process and store portfolio/job photo.
     * Resizes to max 1200x900, compresses to 80%.
     */
    public function storePhoto(UploadedFile $file, string $folder = 'photos'): string
    {
        $filename = $folder . '/' . Str::uuid() . '.jpg';
        $image    = $this->manager->decodePath($file->getRealPath());
        $image->scaleDown(width: 1200, height: 900);

        Storage::disk('public')->put($filename, $image->encode(new JpegEncoder(80))->toString());
        return $filename;
    }

    /**
     * Store multiple photos, return array of paths.
     */
    public function storeMultiple(array $files, string $folder = 'photos'): array
    {
        $paths = [];
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $paths[] = $this->storePhoto($file, $folder);
            }
        }
        return $paths;
    }

    /**
     * Store a document file (NID, etc.) without resizing.
     */
    public function storeDocument(UploadedFile $file, string $folder = 'documents'): string
    {
        $filename = $folder . '/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
        Storage::disk('public')->putFileAs($folder, $file, basename($filename));
        return $filename;
    }

    /**
     * Delete an old file from storage.
     */
    public function deleteOld(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
