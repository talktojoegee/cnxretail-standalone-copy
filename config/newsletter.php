<?php

use Spatie\Newsletter\Drivers\MailChimpDriver;

return [

    /*
     * The driver to use to interact with Mailchimp.
     */
    'driver' => MailChimpDriver::class,

    /*
     * The arguments used by the Mailchimp driver. These map to the
     * MAILCHIMP_APIKEY and MAILCHIMP_LIST_ID values already used by the app.
     */
    'driver_arguments' => [
        'api_key' => env('MAILCHIMP_APIKEY'),
        'endpoint' => env('MAILCHIMP_ENDPOINT'),
    ],

    /*
     * The list name to use when no list name has been specified.
     */
    'default_list_name' => 'subscribers',

    'lists' => [
        'subscribers' => [
            'id' => env('MAILCHIMP_LIST_ID'),
        ],
    ],
];
