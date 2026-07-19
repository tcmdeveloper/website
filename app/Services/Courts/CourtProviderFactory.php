<?php

namespace App\Services\Courts;


use App\Models\CriminalCase;
use App\Services\Courts\Florida\MiamiDadeCourtProvider;
use App\Services\Courts\Florida\LeonCountyCourtProvider;


class CourtProviderFactory
{


    public function make(CriminalCase $criminalCase): CourtProvider
    {
        return match(
            $criminalCase->court_provider
        ) {


            'miamidade' =>
                app(MiamiDadeCourtProvider::class),


            'leon' =>
                app(LeonCountyCourtProvider::class),


            default =>
                throw new \Exception(
                    "Unsupported court provider: {$criminalCase->court_provider}"
                ),

        };

    }


}