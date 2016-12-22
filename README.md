# Laravel signature

Use HMAC or RSA to sign data for Laravel and lumen;

## Step

- convert array data

        // origin
        $data = [
            'z' => 1,
            'a' => [
                'c' => 'c',
                'b' => 'b',
                'a' => [
                    'b' => 'b',
                    'a' => 'a'
                ]
            ],
        ];

        // ksort and convert to string
        $data = [
            'a' => [
                'a' => [
                    'a' => 'a'
                    'b' => 'b',
                ]
                'b' => 'b',
                'c' => 'c',
            ],
            'z' => '1',
        ];

        // json_encode
        {"a":{"a":{"a":"a","b":"b"},"b":"b","c":"c"},"z":"1"}

- hash_hmac or rsa sign string。
- base64_encode

## Install

### laravel

1. composer require liyu/signature
2. add ServiceProvider

        Liyu\Signature\ServiceProvider::class,
3. add Facade

        'Signature' => Liyu\Signature\Facade\Signature::class,

### lumen

- bootstrap/app.php

        $app->register(Liyu\Signature\Facade\Signature::class);

### config

- you can use these in your ENV

        SIGNATURE_HMAC_ALGO
        SIGNATURE_HMAC_KEY

        SIGNATURE_RSA_ALGO
        SIGNATURE_RSA_PUBLIC_KEY
        SIGNATURE_RSA_PRIVATE_KEY

- if you want to use config

        laravel
        php artisan vendor:publish --provider="Liyu\Signature\ServiceProvider"

        lumen
        copy vendor/liyu/signature/src/config/config.php config/signature.php

## Usage

sign

    $signature = Signature::sign('foobar');

    $signature = Signature::setKey('foobar')->sign(['foo'=>'bar']);

    $signature = Signature::signer('hmac')
        ->setAlgo('sha256')
        ->setKey('foobar')
        ->sign(['foo'=>'bar']);

    $signature = Signature::signer('rsa')
        ->setPrivateKey('./private.pem')
        ->sign(['foo'=>'bar']);

verify

    // true or false

    Signature::verify($signature, 'foobar');

    Signature::setKey('foobar')->verify($signature, ['foo'=>'bar']);

    Signature::signer('hmac')
        ->setAlgo('sha256')
        ->setKey('foobar')
        ->verify($sign, ['foo'=>'bar']);

    Signature::signer('rsa')
        ->setPublicKey('./public.pem')
        ->verify($signature, ['foo'=>'bar']);

## Todo

- unit test
- middleware

## License

[MIT LICENSE](https://github.com/liyu001989/signature/blob/master/LICENSE)
