<?php

namespace App\Jobs;

use App\Models\DocketEntry;
use App\Services\Courts\CourtProviderFactory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;

class DownloadDocketDocument implements ShouldQueue
{
    use Queueable, SerializesModels;


    public function __construct(
        public DocketEntry $docketEntry,
        public int $userId,
    ) {
    }


    public function handle(
        CourtProviderFactory $factory
    ): void {

        /*
        |--------------------------------------------------------------------------
        | CHECK: Does this docket have a document?
        |--------------------------------------------------------------------------
        */

        if (! $this->docketEntry->has_document) {
            return;
        }


        if (! $this->docketEntry->input_document_id) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Resolve correct court provider
        |--------------------------------------------------------------------------
        */

        $provider = $factory->make(
            $this->docketEntry->criminalCase
        );


        /*
        |--------------------------------------------------------------------------
        | Download + save document
        |--------------------------------------------------------------------------
        */

        $document = $provider->downloadDocument(
            $this->docketEntry,
            $this->userId
        );


        /*
        |--------------------------------------------------------------------------
        | Process PDF
        |--------------------------------------------------------------------------
        */

        ProcessDocument::dispatch(
            $document->id
        );
    }
}