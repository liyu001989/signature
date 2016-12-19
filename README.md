# Laravel signature

Use HMAC or RSA to sign data for Laravel and lumen;

## Step

- sort array and json_encode

		$data = [
		    'z' => 'z',
		    'a' => [
		        'c' => 'c',
		        'b' => 'b',
		        'a' => [
		            'b' => 'b',
		            'a' => 'a'
		        ]
		    ],
		];

		// sort and json_encode

        {"a":{"a":{"a":"a","b":"b"},"b":"b","c":"c"},"z":"z"}

- hash_hmac or rsa sign string。
- base64_encode

## Install

1. composer require liyu/signature
2. add ServiceProvider

        Liyu\Signature\ServiceProvider::class,
3. add Facade

        'Signature' => Liyu\Signature\Facade\Signature::class,
4. publish config

        php artisan vendor:publish --provider="Liyu\Signature\ServiceProvider"

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

    Signature::verify($signature, ['foo'=>'bar']);

    Signature::setKey('foobar')->verify($signature, ['foo'=>'bar']);

    Signature::signer('hmac')
        ->setAlgo('sha256')
        ->setKey('foobar')
        ->verify($sign, ['foo'=>'bar']);

    Signature::signer('rsa')
        ->setPublicKey('./public.pem')
        ->verify($signature, ['foo'=>'bar']);

## Todo

- phpdoc
- unit test

## License

[MIT LICENSE](https://github.com/liyu001989/signature/blob/master/LICENSE)
