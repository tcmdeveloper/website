<?php

namespace App\Jobs;

use App\Models\CriminalCase;
use App\Services\RandomStringGenerator;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;


class SyncDocketEntries implements ShouldQueue
{

    use Queueable, SerializesModels;


    public function __construct(
        public CriminalCase $criminalCase,
        public int $userId,
    ) {}


    public function handle(RandomStringGenerator $generator): void 
    {

        /*
        |--------------------------------------------------------------------------
        | Fetch case details and docket entries
        |--------------------------------------------------------------------------
        */

        $response = Http::asJson()
            ->acceptJson()
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0',
            ])
            ->withCookies([
                'COCC_WEBAUTH' => config('services.miamidade.cookie'),
            ], 'www2.miamidadeclerk.gov')
            ->post(
                'https://www2.miamidadeclerk.gov/cjis/api/case/details',
                [
                    'encodedQS' => urldecode(
                        $this->criminalCase->clerk_qs
                    ),
                    'dockets' => true,
                    'paymentPlan' => false,
                ]
            )
        ;


        /*
        |--------------------------------------------------------------------------
        | Ensure the Clerk API request succeeded before processing the response.
        |--------------------------------------------------------------------------
        */

        if (! $response->successful()) {
            throw new \Exception('Failed to fetch dockets ({$response->status()})');
        }


        /*
        |--------------------------------------------------------------------------
        | Decode the JSON payload returned by the Clerk API.
        |--------------------------------------------------------------------------
        */

        $data = $response->json();

        if (! data_get($data, 'wasAbleToGetData') || ! isset($data['info']['dockets'])) {
            throw new \Exception('Invalid docket response.');
        }


        /*
        |--------------------------------------------------------------------------
        | STEP 3: Download PDF
        |--------------------------------------------------------------------------
        */

        collect($data['info']['dockets'])
            ->sortBy('seq')
            ->each(function (array $docket) use ($generator) {
                
            $docketEntry = $this->criminalCase
                ->docketEntries()
                ->firstOrNew([
                    'sequence_number' => (int) $docket['seq'],
                ]);

            if (! $docketEntry->exists) {
                $docketEntry->hex = $generator->uniqueHexId();
            }

            $docketEntry->fill([
                'docket_code' => data_get($docket, 'docketCode'),
                'filed_at' => filled(data_get($docket, 'date'))
                    ? Carbon::parse(data_get($docket, 'date'))
                    : null,

                'title' => trim(
                    strip_tags(data_get($docket, 'docketDesc', ''))
                ),

                'has_document' => (bool) data_get($docket, 'hasImage'),
                'input_document_id' => data_get($docket, 'imageId'),
                'page_count' => data_get($docket, 'pageCount'),
               
                'raw_data' => $docket,
            ]);

            $docketEntry->save();


            /*
            |--------------------------------------------------------------------------
            | Run the DownloadDocketDocument job if this docket entry has a document
            |--------------------------------------------------------------------------
            */

            if (data_get($docket, 'hasImage') && filled(data_get($docket, 'imageId'))) {
                DownloadDocketDocument::dispatch($docketEntry, $this->userId);
            }


        });


        $this->criminalCase->update([
            'last_docket_sync_at' => now(),
        ]);


    }


}