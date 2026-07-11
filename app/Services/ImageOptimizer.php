<?php

namespace App\Services;

use App\Models\Image;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\AvifEncoder;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

class ImageOptimizer
{
    public function optimize(string $path): void
    {
        $sizes = [
            160,
            320,
            480,
            640,
            800,
            1200,
        ];

        $manager = ImageManager::usingDriver(Driver::class);

        $source = Storage::disk('public')->path("{$path}.jpg");

        $imageData = file_get_contents($source);

        $original = $manager->decodeBinary($imageData);

        foreach ($sizes as $width) {

            $image = clone $original;

            $image->scale(width: $width);

            Storage::disk('public')->put(
                "{$path}-{$width}.webp",
                (string) $image->encode(
                    new WebpEncoder(quality: 85)
                )
            );

            Storage::disk('public')->put(
                "{$path}-{$width}.avif",
                (string) $image->encode(
                    new AvifEncoder(quality: 80)
                )
            );
        }

        // Single JPEG fallback

        $fallback = clone $original;

        $fallback->scale(width: 1200);

        Storage::disk('public')->put(
            "{$path}.jpg",
            (string) $fallback->encode(
                new JpegEncoder(quality: 90)
            )
        );
    }


    // OPTIMIZE AND MARK
    
    public function optimizeImage(Image $image): void
    {
        $this->optimize($image->image_path);

        $image->update([
            'has_multiformat' => true,
        ]);
    }

}