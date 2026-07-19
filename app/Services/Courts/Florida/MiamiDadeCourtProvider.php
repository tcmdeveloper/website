<?php

namespace App\Services\Courts\Florida;

use App\Models\CriminalCase;
use App\Models\DocketEntry;
use App\Models\Document;
use App\Services\Courts\CourtProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class MiamiDadeCourtProvider implements CourtProvider
{


    public function getDockets(CriminalCase $criminalCase): array
    {

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
                        $criminalCase->clerk_qs
                    ),
                    'dockets' => true,
                    'paymentPlan' => false,
                ]
            );


        if (! $response->successful()) {

            throw new \Exception(
                "Failed to fetch Miami docket {$response->status()}"
            );

        }


        $data = $response->json();


        if (
            ! data_get($data, 'wasAbleToGetData') ||
            ! isset($data['info']['dockets'])
        ) {

            throw new \Exception(
                'Invalid Miami docket response.'
            );

        }


        return collect($data['info']['dockets'])
            ->sortBy('seq')
            ->map(function ($docket) {


                return [

                    'sequence_number' =>
                        (int) $docket['seq'],


                    'docket_code' =>
                        data_get(
                            $docket,
                            'docketCode'
                        ),


                    'filed_at' =>
                        filled(data_get($docket,'date'))
                            ? Carbon::parse(
                                data_get($docket,'date')
                              )
                            : null,


                    'title' =>
                        trim(
                            strip_tags(
                                data_get(
                                    $docket,
                                    'docketDesc',
                                    ''
                                )
                            )
                        ),


                    'has_document' =>
                        (bool) data_get(
                            $docket,
                            'hasImage'
                        ),


                    'input_document_id' =>
                        data_get(
                            $docket,
                            'imageId'
                        ),


                    'page_count' =>
                        data_get(
                            $docket,
                            'pageCount'
                        ),


                    'raw_data'=>$docket,

                ];


            })
            ->values()
            ->toArray();


    }



    public function downloadDocument(
        DocketEntry $docketEntry,
        int $userId
    ): Document {

        // move your existing Miami code here

        return $document;
    }

}