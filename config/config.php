<?php

return [
    'signer' => 'hmac',

    'hmac' => [
        'algo' => env('SIGNATURE_HMAC_ALGO', 'sha1'),
    ],
    'rsa' => [
        'private_key' => env('SIGNATURE_RSA_PRIVATE_KEY'),
    ],
];
