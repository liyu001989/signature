# Laravel signature

Use HMAC or RSA to sign data for Laravel and lumen;

## Stap

- $signData = `['foo'=>'bar', 'a'=>'aa', 'b'=>'bb']`;

- $signString = `'a=aa&b=bb&foo=bar'`;

- use hash_hmac or rsa to sign this string。

## Install

1. composer require liyu/signature:dev-master
2. add ServiceProvider

        Liyu\Signature\ServiceProvider::class,

3. add Facade

        'Signature' => Liyu\Signature\Facade\Signature::class,

4. publish config

        php artisan vendor:publish --provider="Liyu\Signature\ServiceProvider"

## Usage

sign

        $sign = Signature::setKey('foobar')->sign(['foo'=>'bar']);
 
verify

        Signature::setKey('foobar')->verify($sign, ['foo'=>'bar']); // true

## Todo

- rsa sign

## License

[MIT LICENSE](https://github.com/liyu001989/signature/blob/master/LICENSE)
