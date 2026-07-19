<?php

namespace App\Jobs;

use App\Models\CriminalCase;
use App\Services\RandomStringGenerator;
use App\Services\Courts\CourtProviderFactory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;


class SyncDocketEntries implements ShouldQueue
{

    use Queueable, SerializesModels;


    public function __construct(
        public CriminalCase $criminalCase,
        public int $userId,
    ) {}



    public function handle(
        RandomStringGenerator $generator,
        CourtProviderFactory $factory
    ): void
    {

        $court = $factory->make(
            $this->criminalCase
        );


        $dockets = $court->getDockets(
            $this->criminalCase
        );


        foreach($dockets as $docket)
        {

            $entry = $this->criminalCase
                ->docketEntries()
                ->firstOrNew([
                    'sequence_number' =>
                        $docket['sequence_number']
                ]);


            if(! $entry->exists)
            {
                $entry->hex =
                    $generator->uniqueHexId();
            }


            $entry->fill($docket);

            $entry->save();


            if(
                $docket['has_document'] &&
                filled($docket['input_document_id'])
            )
            {
                DownloadDocketDocument::dispatch(
                    $entry,
                    $this->userId
                );
            }

        }

    }
}