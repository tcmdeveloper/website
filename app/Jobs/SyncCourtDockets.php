<?php

namespace App\Jobs;

use App\Models\CriminalCase;
use App\Services\Courts\CourtProviderFactory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;



class SyncCourtDockets implements ShouldQueue
{

    use Queueable, SerializesModels;


    public function __construct(
        public int $criminalCaseId,
        public int $userId,
    ) {
    }


    public function handle(
        CourtProviderFactory $factory
    ): void {

        $criminalCase = CriminalCase::findOrFail(
            $this->criminalCaseId
        );

        $provider = $factory->make($criminalCase);

        $dockets = $provider->getDockets($criminalCase);

        foreach ($dockets as $docket) {

            $criminalCase->docketEntries()->updateOrCreate(
                [
                    'sequence_number' => $docket['sequence_number'],
                ],
                [
                    'docket_code' => $docket['docket_code'],
                    'filed_at' => $docket['filed_at'],
                    'title' => $docket['title'],
                    'has_document' => $docket['has_document'],
                    'input_document_id' => $docket['input_document_id'],
                    'raw_html' => $docket['raw_html'],
                ]
            );
        }
    }


}