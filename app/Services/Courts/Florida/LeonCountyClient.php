<?php

namespace App\Services\Courts\Florida;

use App\Models\CriminalCase;
use App\Models\DocketEntry;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class LeonCountyClient
{
    public function getDockets(
        CriminalCase $criminalCase
    ): array {

        $params = [
            'report' => 'full_view',

            'caseid' => 2785598,

            'jiscaseid' => 1129969,

            'defseq' => 'A',

            'chargeseq' => 1,

            'secret' => 1,
        ];

        logger()->info('Leon request', [
            'url' => config('courts.florida.leon.docket_url'),
            'params' => $params,
        ]);




        $response = Http::get(
            config('courts.florida.leon.docket_url'),
            $params
        );

        logger()->info('Leon response', [
            'status' => $response->status(),
            'length' => strlen($response->body()),
            'contains_pdf' => str_contains(
                $response->body(),
                'image_orders.asp'
            ),
            'contains_table' => str_contains(
                strtolower($response->body()),
                '<tr>'
            ),
        ]);




        return $this->parse(
            $response->body()
        );
    }





    private function parse(string $html): array
    {
        $crawler = new Crawler($html);

        $dockets = [];

        $crawler->filter('tr')->each(
            function (Crawler $row) use (&$dockets) {

                $cells = $row->filter('td');

                // Skip headers and malformed rows
                if ($cells->count() < 8) {
                    return;
                }

                $date = trim(
                    $cells->eq(0)->text()
                );

                $sequence = trim(
                    $cells->eq(1)->text()
                );

                if (!is_numeric($sequence)) {
                    return;
                }

                $code = trim(
                    $cells->eq(2)->text()
                );

                $title = trim(
                    $cells->eq(4)->text()
                );

                $documentData = [];

                $documentId = null;

                $href = null;

                $hasDocument =
                    $cells->eq(2)
                        ->filter('a')
                        ->count() > 0;

                if ($hasDocument) {

                    $href = $cells
                        ->eq(2)
                        ->filter('a')
                        ->attr('href');

                    parse_str(
                        parse_url(
                            $href,
                            PHP_URL_QUERY
                        ),
                        $documentData
                    );

                    $documentId =
                        $documentData['dktid']
                        ?? null;
                }

                $dockets[] = [

                    'sequence_number' =>
                        (int) $sequence,

                    'docket_code' =>
                        $code ?: null,

                    'filed_at' =>
                        date(
                            'Y-m-d',
                            strtotime($date)
                        ),

                    'title' =>
                        $title,

                    'has_document' =>
                        $hasDocument,

                    'input_document_id' =>
                        $documentId,

                    'raw_data' => [

                        'caseid' =>
                            $documentData['caseid']
                            ?? null,

                        'jiscaseid' =>
                            $documentData['jiscaseid']
                            ?? null,

                        'defseq' =>
                            $documentData['defseq']
                            ?? null,

                        'chargeseq' =>
                            $documentData['chargeseq']
                            ?? null,

                        'dktsource' =>
                            $documentData['dktsource']
                            ?? null,

                        'dktid' =>
                            $documentData['dktid']
                            ?? null,

                        'sexual_case' =>
                            $documentData['sexual_case']
                            ?? null,

                        'href' => $href,

                        'source' => trim(
                            $cells->eq(7)->text()
                        ),
                    ],

                    'raw_html' => $row->html(),
                ];
            }
        );

        return $dockets;
    }


    public function downloadDocument(
        DocketEntry $docketEntry
    ): string {

        $data = $docketEntry->raw_data;


        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0',

            'Referer' =>
                config('courts.florida.leon.docket_url'),

        ])->get(
            config('courts.florida.leon.document_url'),
            [
                'caseid' =>
                    $data['caseid'],

                'jiscaseid' =>
                    $data['jiscaseid'],

                'defseq' =>
                    $data['defseq'],

                'chargeseq' =>
                    $data['chargeseq'],

                'dktid' =>
                    $data['dktid'],

                'dktsource' =>
                    $data['dktsource'],

                'sexual_case' =>
                    $data['sexual_case'],
            ]
        );


        // Check that Leon actually returned a PDF
        if (
            $response->header('Content-Type')
            !== 'application/pdf'
        ) {
            throw new \Exception(
                'Leon document download failed. Content type: '
                . $response->header('Content-Type')
            );
        }


        // Check that the PDF is not empty
        if ($response->body() === '') {
            throw new \Exception(
                'Leon returned empty document.'
            );
        }


        return $response->body();
    }
}