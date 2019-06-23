<?php

return [
    'consumer_key' => env('MPESA_CONSUMER_KEY', ''),
    'consumer_secret' => env('MPESA_CONSUMER_SECRET', ''),
    'url' => 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials',
    'settings' => array(
        'mode' => env('PAYPAL_MODE', ''),
        'Http.ConnectionTimeOut' => 30,
        'log.LogEnabled' => true,
        'log.FileName' => storage_path() . '/logs/paypal.log',
        'log.LogLevel' => 'ERROR',
    ),
];