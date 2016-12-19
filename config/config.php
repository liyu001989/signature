<?php

return [
    'default' => 'hmac',

    'hmac' => [
        'driver' => 'HMAC',
        'options' => [
            'algo' => env('SIGNATURE_HMAC_ALGO', 'sha1'), // default
            'key' => env('SIGNATURE_HMAC_KEY'), // default hmac key
        ],
    ],
    'rsa' => [
        'driver' => 'RSA',
        'options' => [
            'algo' => env('SIGNATURE_RSA_ALGO', 'sha1'),
            'privateKey' => env('SIGNATURE_RSA_PRIVATE_KEY'), // default primary key
        ],
    ],
];
