<?php

namespace App\Services;

use App\Models\Video;
use App\Models\DocumentPage;
use App\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\AvifEncoder;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

class ImageOptimizer
{



protected function getSizesForModel(Model $model): array
{
    if ($model instanceof DocumentPage) {
        return [
            80,
            480,
            800,
        ];
    }

    if ($model instanceof Image) {
        return [
            160,
            320,
            480,
            640,
            800,
            1200,
        ];
    }

    if ($model instanceof Video) {
        return [
            160,
            320,
            640
        ];
    }

    return [
        160,
        320,
        480,
        640,
        800,
        1200,
    ];
}




    public function optimize(string $path, ?array $sizes = null): void
    {
        $sizes ??= [
            160,
            320,
            480,
            640,
            800,
            1200,
        ];

        $manager = ImageManager::usingDriver(
            Driver::class
        );

        // Determine whether the path already has an extension.
        // Examples:
        //
        // articles/foo/bar
        // document-pages/abc/page-01.png

        $extension = pathinfo(
            $path,
            PATHINFO_EXTENSION
        );

        $sourcePath = null;

        foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
            $candidate = "{$path}.{$ext}";

            if (Storage::disk('public')->exists($candidate)) {
                $sourcePath = $candidate;
                break;
            }
        }

        if (! $sourcePath) {
            throw new \Exception(
                "Source image not found for {$path}"
            );
        }

        // Remove the extension for generated files.
        //
        // document-pages/foo/page-01.png
        // becomes:
        //
        // document-pages/foo/page-01

        $basePath = $extension
            ? preg_replace(
                '/\.[^.]+$/',
                '',
                $path
            )
            : $path;

        $source = Storage::disk('public')->path(
            $sourcePath
        );

        if (! file_exists($source)) {
            throw new \Exception(
                "Source image not found: {$source}"
            );
        }

        $imageData = file_get_contents(
            $source
        );

        $original = $manager->decodeBinary(
            $imageData
        );


        foreach ($sizes as $width) {

            $image = clone $original;

            $image->scale(
                width: $width
            );

            Storage::disk('public')->put(
                "{$basePath}-{$width}.webp",
                (string) $image->encode(
                    new WebpEncoder(
                        quality: 85
                    )
                )
            );

            Storage::disk('public')->put(
                "{$basePath}-{$width}.avif",
                (string) $image->encode(
                    new AvifEncoder(
                        quality: 80
                    )
                )
            );
        }

        // Delete original images only if they were
        // extensionless uploads (your Image model).
        //
        // We do NOT want to delete:
        //
        // document-pages/foo/page-01.png

        if (! $extension) {
            Storage::disk('public')->delete([
                "{$path}.webp",
                "{$path}.avif",
            ]);
        }
    }

    public function optimizeImage(Image $image): void
    {
        $this->optimize(
            $image->image_path
        );

    }

    public function optimizeModel(Model $model): void
    {
        $this->optimize(
            $model->image_path,
            $this->getSizesForModel($model)
        );
        
        if (
            Schema::hasColumn(
                $model->getTable(),
                'is_optimized'
            )
        ) {
            $model->update([
                'is_optimized' => true,
            ]);
        }

    }
}