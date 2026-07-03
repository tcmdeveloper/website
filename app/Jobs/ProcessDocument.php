<?php

namespace App\Jobs;

use App\Models\Document;
use App\Models\DocumentPage;
use App\Services\RandomStringGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProcessDocument implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct(
        public int $documentId
    ) {}

    public function handle(): void
    {

        $generator = app(RandomStringGenerator::class);

        $document = Document::findOrFail($this->documentId);

        $inputPath = Storage::disk('public')->path($document->pdf_path);

        if (!file_exists($inputPath)) {
            throw new \Exception("PDF not found at: {$inputPath}");
        }

        $outputDir = Storage::disk('public')->path(
            "document-pages/{$document->hex}"
        );

        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        // Clear old pages if re-processing
        DocumentPage::where('document_id', $document->id)->delete();

        // Mark as processing
        $document->update([
            'status' => 'processing',
        ]);



        // Build command
        $input = escapeshellarg($inputPath);
        $output = escapeshellarg($outputDir . '/page');

        $cmd = "/opt/homebrew/bin/pdftoppm -png -r 200 {$input} {$output}";

        exec($cmd . ' 2>&1', $outputLines, $exitCode);

        // dd([
        //     'cmd' => $cmd,
        //     'exitCode' => $exitCode,
        //     'output' => $outputLines,
        // ]);



        // Get generated files
        $files = glob($outputDir . '/page-*.png');

sort($files);

foreach ($files as $index => $filePath) {
    $relativePath = str_replace(
        Storage::disk('public')->path('/document-pages'),
        '',
        $filePath
    );

    $imageSize = getimagesize($filePath);

    DocumentPage::create([
        'hex' => $generator->uniqueHexId(),
        'document_id' => $document->id,
        'page_number' => $index + 1,
        'image_path' => '/document-pages' . $relativePath,
        'width' => $imageSize[0] ?? null,
        'height' => $imageSize[1] ?? null,
    ]);
}

        $document->update([
            'pages' => count($files),
            'status' => 'ready',
        ]);
    }
}