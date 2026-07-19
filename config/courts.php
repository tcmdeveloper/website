<?php

return [

    'florida' => [
    
        'leon' => [

            'docket_url' =>
                'https://lforms.leonclerk.com/search_courts/process.asp',

            'document_url' =>
                'https://lforms.leonclerk.com/search_courts/image_orders.asp',

        ],


        'miamidade' => [

            'docket_url' =>
                env(
                    'MIAMI_DADE_DOCKET_URL'
                ),

            'document_url' =>
                env(
                    'MIAMI_DADE_DOCUMENT_URL'
                ),

        ],
    ],

];