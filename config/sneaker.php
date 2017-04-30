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
    | A list of exception types that should be captured.
    |--------------------------------------------------------------------------
    |
    | For which exception type notification should be sent?
    |
    | By defautl we have set the array to '*', which means that we will capture 
    | all the exceptions that occurs in the application. To explicitly list 
    | the class define them below as:
    |
    | 'capture' => [
    |     Symfony\Component\Debug\Exception\FatalErrorException::class,
    | ],
    |
    */
    'capture' => [
        '*'
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
