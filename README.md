# Laravel signature

Use HMAC or RSA to sign data for Laravel and lumen;

[![Latest Stable Version](https://poser.pugx.org/liyu/signature/version)](https://packagist.org/packages/liyu/signature)
[![Total Downloads](https://poser.pugx.org/liyu/signature/downloads)](https://packagist.org/packages/liyu/signature)
[![StyleCI](https://styleci.io/repos/76261016/shield)](https://styleci.io/repos/76261016096)


## Install

For Laravel < 5.5, please use the tag [0.2.10](https://github.com/liyu001989/signature/tree/v0.2.10)

### laravel

`composer require liyu/signature`

### lumen

- bootstrap/app.php

        $app->register(Liyu\Signature\Facade\Signature::class);

### config

- you can use these in your ENV

        // default driver
        SIGNATURE_DRIVER

        // hmac algo and key
        SIGNATURE_HMAC_ALGO (default sha256)
        SIGNATURE_HMAC_KEY (default null)

        // rsa algo, public_key, private_key
        SIGNATURE_RSA_ALGO (default sha256)
        SIGNATURE_RSA_PUBLIC_KEY
        SIGNATURE_RSA_PRIVATE_KEY

- if you want to use config

        laravel
        php artisan vendor:publish

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

## Sign Steps

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

- sign string。
 

        hmac  => hmac($algo, $convertData, $key);
        // outputs lowercase hexits

        rsa => base64_encode(openssl_sign_string);

## License

[MIT LICENSE](https://github.com/liyu001989/signature/blob/master/LICENSE)
