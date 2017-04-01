<?php

use Liyu\Signature\Signer\RSA;

class RSATest extends PHPUnit_Framework_TestCase
{
    protected $publicKey;

    protected $privateKey;

    public function testSetConfig()
    {
        $config = [
            'publicKey' => 'public.key',
            'privateKey' => 'private.key',
            'algo' => 'sha1',
        ];

        $rsa = new RSA($config);
        $this->assertEquals($rsa->getAlgo(), 'sha1');
        $this->assertEquals($rsa->getPublicKey(), 'public.key');
        $this->assertEquals($rsa->getPrivateKey(), 'private.key');

        // default
        $rsa = new RSA();
        $this->assertEquals($rsa->getAlgo(), 'sha256');

        // setConfig
        $rsa->setAlgo('md5');
        $this->assertEquals($rsa->getAlgo(), 'md5');
        $rsa->setPublicKey('foo.pub');
        $this->assertEquals($rsa->getPublicKey(), 'foo.pub');
        $rsa->setPrivateKey('foo');
        $this->assertEquals($rsa->getPrivateKey(), 'foo');
    }

    public function testSign()
    {
        $this->generatePems();
        $config = [
            'privateKey' => $this->privateKey,
            'algo' => 'sha256',
        ];

        $rsa = new RSA($config);

        $data = 'foobar';
        openssl_sign($data, $signature, $this->privateKey, 'sha256');
        $target = base64_encode($signature);
        $this->assertEquals($target, $rsa->sign($data));

        // array
        $data = [
            'b' => 'b',
            'c' => [
                'd' => 'd',
                'e' => 1,
            ],
            'a' => 'a',
        ];
        $dataString = json_encode([
            'a' => 'a',
            'b' => 'b',
            'c' => [
                'd' => 'd',
                'e' => '1',
            ],
        ]);
        openssl_sign($dataString, $signature, $this->privateKey, 'sha256');
        $target = base64_encode($signature);
        $this->assertEquals($target, $rsa->sign($data));
    }

    public function testVerify()
    {
        $this->generatePems();

        $config = [
            'publicKey' => $this->publicKey,
            'algo' => 'sha256',
        ];

        $rsa = new RSA($config);

        // string
        $data = 'foobar';
        openssl_sign($data, $signature, $this->privateKey, 'sha256');
        $target = base64_encode($signature);
        $this->assertTrue($rsa->verify($target, $data));

        $this->assertFalse($rsa->verify($target, 'fooba'));

        // array
        $data = [
            'b' => 'b',
            'c' => [
                'd' => 'd',
                'e' => 1,
            ],
            'a' => 'a',
        ];
        $dataString = json_encode([
            'a' => 'a',
            'b' => 'b',
            'c' => [
                'd' => 'd',
                'e' => '1',
            ],
        ]);
        openssl_sign($dataString, $signature, $this->privateKey, 'sha256');
        $target = base64_encode($signature);

        $this->assertTrue($rsa->verify($target, $data));
    }

    protected function generatePems()
    {
        $new_key_pair = openssl_pkey_new([
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);
        openssl_pkey_export($new_key_pair, $private_key_pem);

        $details = openssl_pkey_get_details($new_key_pair);
        $this->publicKey = $details['key'];
        $this->privateKey = $private_key_pem;
    }
}
