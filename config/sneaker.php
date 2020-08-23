<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Sends an email on Exception or be silent
    |--------------------------------------------------------------------------
    |
    | Should we email error traces?
    |
    */
    'silent' => env('SNEAKER_SILENT', true),

    /*
    |--------------------------------------------------------------------------
    | A list of the exception types that should be captured.
    |--------------------------------------------------------------------------
    |
    | For which exception class emails should be sent?
    |
    | You can also use '*' in the array which will in turn captures every
    | exception.
    |
    */
    'capture' => [
        Symfony\Component\Debug\Exception\FatalErrorException::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Error email recipients
    |--------------------------------------------------------------------------
    |
    | Email stack traces to these addresses.
    |
    */

    // 'to' => [
    //     'hello@example.com',
    // ],
    'to' => explode(",", env("SNEAKER_TO", "")),
    // set SNEAKER_TO=hello@example.com
    // or SNEAKER_TO=hello@example.com,hello1@example.com

    /*
    |--------------------------------------------------------------------------
    | Ignore Crawler Bots
    |--------------------------------------------------------------------------
    |
    | For which bots should we NOT send error emails?
    |
    */
    'ignored_bots' => [
        'yandexbot',        // YandexBot
        'googlebot',        // Googlebot
        'bingbot',          // Microsoft Bingbot
        'slurp',            // Yahoo! Slurp
        'ia_archiver',      // Alexa
    ],
];
