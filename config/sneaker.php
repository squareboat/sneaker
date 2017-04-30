<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Sends a notification on Exception or be silent.
    |--------------------------------------------------------------------------
    |
    | Should we email error traces?
    |
    */
    'silent' => env('SNEAKER_SILENT', false),

    /*
    |--------------------------------------------------------------------------
    | A list of the exception types that should be captured.
    |--------------------------------------------------------------------------
    |
    | For which exception class notification should be sent?
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
    | Notification Delivery Channels
    |--------------------------------------------------------------------------
    |
    | The channels on which the notification will be delivered.
    |
    */

    'notifications' => [
        'mail',
        'slack',
    ],

    /*
    |--------------------------------------------------------------------------
    | Error email recipients
    |--------------------------------------------------------------------------
    |
    | The email address used to deliver the notification.
    |
    */

    'mail' => [
        'to' => [
            // 'your@email.com',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Slack Webhook Url
    |--------------------------------------------------------------------------
    |
    | The webhook URL to which the notification should be delivered.
    |
    */

    'slack' => [
        'webhook_url' => env('SNEAKER_SLACK_WEBHOOK_URL'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Ignore Crawler Bots
    |--------------------------------------------------------------------------
    |
    | For which bots should we NOT send error notifications?
    |
    */
    'ignored_bots' => [
        'googlebot',        // Googlebot
        'bingbot',          // Microsoft Bingbot
        'slurp',            // Yahoo! Slurp
        'ia_archiver',      // Alexa
    ],
];
