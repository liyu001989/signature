<?php

use Liyu\Signature\Signer\HMAC;

class HMACTest extends PHPUnit_Framework_TestCase
{
    public function testSetConfig()
    {
        $config = [
            'algo' => 'sha256',
            'key' => '123456',
        ];
        $hmac = new HMAC($config);
        $this->assertEquals($hmac->getKey(), 123456);
        $this->assertEquals($hmac->getAlgo(), 'sha256');

        // default
        $hmac = new HMAC();
        $this->assertEquals($hmac->getAlgo(), 'sha1');

        // set
        $hmac->setKey('654321');
        $this->assertEquals($hmac->getKey(), '654321');
        $hmac->setAlgo('md5');
        $this->assertEquals($hmac->getAlgo(), 'md5');
    }

    public function testSign()
    {
        $config = [
            'algo' => 'sha256',
            'key' => '123456',
        ];
        $hmac = new HMAC($config);

        // string
        $data = 'foobar';
        $target = base64_encode(hash_hmac('sha256', $data, '123456'));
        $this->assertEquals($hmac->sign($data), $target);

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
        $target = base64_encode(hash_hmac('sha256', $dataString, '123456'));
        $this->assertEquals($hmac->sign($data), $target);
    }

    public function testVerify()
    {
        $config = [
            'algo' => 'sha256',
            'key' => '123456',
        ];
        $hmac = new HMAC($config);

        // string
        $data = 'foobar';
        $target = base64_encode(hash_hmac('sha256', $data, '123456'));
        $ret = $hmac->verify($target, $data);
        $this->assertTrue($ret);

        $data = 'fooba';
        $target = base64_encode(hash_hmac('sha256', 'foobar', '123456'));
        $ret = $hmac->verify($target, $data);
        $this->assertFalse($ret);

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
        $target = base64_encode(hash_hmac('sha256', $dataString, '123456'));
        $ret = $hmac->verify($target, $data);
        $this->assertTrue($ret);
    }
}
