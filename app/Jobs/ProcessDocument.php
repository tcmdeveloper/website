<?php

namespace App\Jobs;

use App\Models\Document;
use App\Models\DocumentPage;
use App\Services\ImageOptimizer;
use App\Services\RandomStringGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ProcessDocument implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct(
        public int $documentId
    ) {}

    public function handle(ImageOptimizer $optimizer): void
    {
        $generator = app(RandomStringGenerator::class);
        $document = Document::findOrFail($this->documentId);
        $inputPath = Storage::disk('public')->path(
            $document->pdf_path
        );


        if (! file_exists($inputPath)) {
            throw new \Exception(
                "PDF not found at: {$inputPath}"
            );
        }


        $watermarkPath = storage_path(
            'app/public/watermarks/metrix-document-watermark.png'
        );


        if (! file_exists($watermarkPath)) {
            throw new \Exception(
                "Watermark not found at: {$watermarkPath}"
            );
        }


        $outputDir = Storage::disk('public')->path(
            "document-pages/{$document->hex}"
        );


        if (! is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }


        // Delete existing page records
        DocumentPage::where(
            'document_id',
            $document->id
        )->delete();


        $document->update([
            'status' => 'processing',
        ]);


        // Convert PDF → PNG
        $input = escapeshellarg($inputPath);

        $output = escapeshellarg(
            $outputDir . '/page'
        );

        $cmd = "/opt/homebrew/bin/pdftoppm "
            . "-jpeg "
            . "-r 200 "
            . "{$input} "
            . "{$output}";


        exec(
            $cmd . ' 2>&1',
            $outputLines,
            $exitCode
        );

        if ($exitCode !== 0) {
            throw new \Exception(
                implode("\n", $outputLines)
            );
        }

        $files = glob(
            $outputDir . '/page-*.jpg'
        );

        sort($files);

        $manager = new ImageManager(
            new Driver()
        );



        $watermark = $manager->decodePath($watermarkPath);


        foreach ($files as $index => $filePath) {

            $image = $manager->decodePath($filePath);

            $pageWatermark = clone $watermark;

            $pageWatermark->cover(
                $image->width(),
                $image->height()
            );

            $image->insert(
                $pageWatermark,
                0,
                0
            );

            $image->save($filePath);

            $relativePath = preg_replace(
                '/\.[^.]+$/',
                '',
                str_replace(
                    Storage::disk('public')->path('/document-pages'),
                    '',
                    $filePath
                )
            );

            $imageSize = getimagesize(
                $filePath
            );

            $page = DocumentPage::create([
                'hex' => $generator->uniqueHexId(),
                'document_id' => $document->id,
                'page_number' => $index + 1,
                'image_path' => 'document-pages' . $relativePath,
                'width' => $imageSize[0] ?? null,
                'height' => $imageSize[1] ?? null,
            ]);

            $optimizer->optimizeModel($page);

        }

        $document->update([
            'pages' => count($files),
            'status' => 'ready',
        ]);
    }
}