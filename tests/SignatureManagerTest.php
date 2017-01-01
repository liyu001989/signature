<?php

use Mockery as m;
use Liyu\Signature\SignatureManager;

class SignatureTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testDefaultSignerCanBeResolved()
    {
        $app = [
            'config' => [
                'signature.default' => 'hmac',
                'signature.hmac' => [
                    'driver' => 'hmac',
                    'options' => [
                        'algo' => 'sha256',
                        'key' => '123456',
                    ],
                ],
            ],
        ];

        $manager = new SignatureManager($app);
    }
}
