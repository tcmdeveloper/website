<?php

namespace App\Jobs;

use App\Models\DocketEntry;
use App\Models\Document;
use App\Services\RandomStringGenerator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DownloadDocketDocument implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public DocketEntry $docketEntry,
        public int $userId,
    ) {}

    public function handle(RandomStringGenerator $generator): void 
    {

        /*
        |--------------------------------------------------------------------------
        | CHECK: Return if document_id is null
        |--------------------------------------------------------------------------
        */

        if (! $this->docketEntry->input_document_id) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | STEP 1: Generate encrypted query string
        |--------------------------------------------------------------------------
        */

        $encryptResponse = Http::asJson()
            ->acceptJson()
            ->withHeaders(
                [
                    'User-Agent' => 'Mozilla/5.0',
                    'Origin' => 'https://www2.miamidadeclerk.gov',
                    'Referer' => sprintf('https://www2.miamidadeclerk.gov/cjis/casesearchinfo?qs=%s', urlencode($this->docketEntry->criminalCase->clerk_qs)),
                ]
            )
            ->withCookies(
                [
                    'COCC_WEBAUTH' => config('services.miamidade.cookie'),
                ], 
                'www2.miamidadeclerk.gov'
            )
            ->post(
                'https://www2.miamidadeclerk.gov/cjis/api/ImageViewer/encrypt', [
                    'caseNumber' => $this->docketEntry->criminalCase->criminal_case_number,
                    'sessionStatus' => 'Y',
                    'docId' => $this->docketEntry->input_document_id,
                    'din' => (string) $this->docketEntry->sequence_number,
                    'pageCount' => (string) $this->docketEntry->page_count,
                    'docType' => substr($this->docketEntry->input_document_id, 0, 2),
                    'docCode' => $this->docketEntry->docket_code,
                    'docTypeDescription' => $this->docketEntry->title,
                    'fileDate' => optional($this->docketEntry->filed_at)->format('m/d/Y'),
                ]
            )
        ;


        $encryptedQS = data_get($encryptResponse->json(), 'encryptedQueryString');


        if (! $encryptedQS) {
            throw new \Exception('Could not generate encrypted query string.');
        }

        
        /*
        |--------------------------------------------------------------------------
        | STEP 2: Get document metadata
        |--------------------------------------------------------------------------
        */

        $documentResponse = Http::withHeaders(['User-Agent' => 'Mozilla/5.0'])
            ->withCookies(['COCC_WEBAUTH' => config('services.miamidade.cookie')], 'www2.miamidadeclerk.gov')
            ->get('https://www2.miamidadeclerk.gov/cjis/api/ImageViewer', ['qs' => $encryptedQS])
        ;

        $inputDocument = data_get($documentResponse->json(), 'documentInfo.0');

        if (! $inputDocument) {
            throw new \Exception('Document metadata missing.');
        }


        /*
        |--------------------------------------------------------------------------
        | STEP 3: Download PDF
        |--------------------------------------------------------------------------
        */

        $file = Http::withHeaders(['User-Agent' => 'Mozilla/5.0', 'Referer' => sprintf('https://www2.miamidadeclerk.gov/cjis/viewDocument?qs=%s', urlencode($encryptedQS)), 'Accept' => '*/*'])
            ->withCookies(['COCC_WEBAUTH' => config('services.miamidade.cookie')], 'www2.miamidadeclerk.gov')
            ->get('https://www2.miamidadeclerk.gov/cjis/api/ImageViewer/image', ['imagePath' => $inputDocument['encodedDocumentPath']])
        ;

        if ($file->header('Content-Type') !== 'application/pdf') {
            throw new \Exception('Expected PDF, got: ' . $file->header('Content-Type'));
        }


        /*
        |--------------------------------------------------------------------------
        | STEP 4: Save PDF
        |--------------------------------------------------------------------------
        */

        $documentHex = $generator->uniqueHexId();

        $pdf_path = sprintf(
            'documents/%s/%s.pdf',
            $documentHex,
            Str::uuid()
        );

        // dd($pdf_path);

        Storage::disk('public')->put(
            $pdf_path,
            $file->body()
        );


        // Insert document into the database
        
        $document = Document::create([
            'hex' => $documentHex,
            'name' => $this->docketEntry->title,
            'slug' => Str::slug($this->docketEntry->title) . '-' . $documentHex,
            'description' => 'Court docket document from the ' . $this->docketEntry->criminalCase->name . ' case.',
            'pdf_path' => $pdf_path,
            'user_id' => $this->userId,
            'criminal_case_id' => $this->docketEntry->criminalCase->id,
            'docket_entry_id' => $this->docketEntry->id,
            'is_published' => true,
            'published_at' => now(),
        ]);


            // logger()->error('This Document', [
            //     'document' => $document,
            //     'docket_entry_id' => $this->docketEntry->id,

            // ]);

        $this->docketEntry->update([
            'encoded_document_path' => $inputDocument['encodedDocumentPath'],
        ]);


        // Start processing the document
        ProcessDocument::dispatch($document->id);


    }


}