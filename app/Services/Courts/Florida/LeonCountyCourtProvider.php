<?php

namespace App\Services\Courts\Florida;

use App\Jobs\DownloadDocketDocument;
use App\Models\CriminalCase;
use App\Models\DocketEntry;
use App\Models\Document;
use App\Services\Courts\CourtProvider;
use App\Services\RandomStringGenerator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class LeonCountyCourtProvider implements CourtProvider
{
    public function __construct(
        private LeonCountyClient $client
    ) {
    }


    public function getDockets(
        CriminalCase $criminalCase
    ): array {

        $dockets = $this->client->getDockets(
            $criminalCase
        );


        foreach ($dockets as $docket) {

            $entry = DocketEntry::firstOrNew([
                'criminal_case_id' =>
                    $criminalCase->id,

                'sequence_number' =>
                    $docket['sequence_number'],
            ]);


            if (! $entry->exists) {
                $entry->hex = str()->random(11);
            }


            $entry->fill([

                'docket_code' =>
                    $docket['docket_code'],

                'filed_at' =>
                    $docket['filed_at'],

                'title' =>
                    $docket['title'],

                'has_document' =>
                    $docket['has_document'],

                'input_document_id' =>
                    $docket['input_document_id'],

                'raw_data' =>
                    $docket['raw_data'],

            ]);


            $entry->save();


            if ($entry->has_document) {

                DownloadDocketDocument::dispatch(
                    $entry,
                    $criminalCase->user_id
                );

            }

        }


        return $dockets;
    }



    public function downloadDocument(
        DocketEntry $docketEntry,
        int $userId
    ): Document {


        // Prevent duplicates
        if ($docketEntry->document) {

            return $docketEntry->document;

        }


        $file = $this->client->downloadDocument(
            $docketEntry
        );


        $documentHex = app(RandomStringGenerator::class)
            ->uniqueHexId();


        $pdfPath = sprintf(
            'documents/%s/%s.pdf',
            $documentHex,
            Str::uuid()
        );


        Storage::disk('public')->put(
            $pdfPath,
            $file
        );


        $caseName =
            $docketEntry->criminalCase
                ->criminal_case_number
                ?? 'Unknown Case';


        $document = Document::create([

            'hex' =>
                $documentHex,

            'name' =>
                $docketEntry->title,

            'slug' =>
                Str::slug($docketEntry->title)
                . '-' . $documentHex,

            'description' =>
                'Court docket document from case '
                . $caseName
                . '.',

            'pdf_path' =>
                $pdfPath,

            'user_id' =>
                $userId,

            'criminal_case_id' =>
                $docketEntry->criminalCase->id,

            'docket_entry_id' =>
                $docketEntry->id,

            'is_published' =>
                true,

            'published_at' =>
                now(),

        ]);


        $docketEntry->update([

            'encoded_document_path' =>
                $pdfPath,

        ]);


        return $document;

    }

}