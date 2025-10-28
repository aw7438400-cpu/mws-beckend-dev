<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOGGLE_CLIENT_SECRET'),
        'redirect' => config ('app.url'). '/auth-google-calback',
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_TOKEN'),
            'user_map' => [
                607 => 'U09GKDAL7GX',    
                633 => 'UPJEPKH8D',      
                2 => 'U05RH1086UB',
                3 => 'U05R1D18LTH',
                4 => 'U05QQ7WB22K',
                5 => 'U05SH2113AP',
                6 => 'U08BEP8LU3V',
                7 => 'U06C5BY1YT1',
                8 => 'U05RUL0FGR3',
                9 => 'U05RMHRJCTD',
                10 => 'U079EU31GGK',
            ],
        ],
    ],
];