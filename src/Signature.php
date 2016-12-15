<?php

namespace Liyu\Signature;

class Signature
{
    protected $provider;

    public function __construct($provider)
    {
        $this->provider = $provider;
    }

    public function sign(Payload $payload)
    {
        $this->provider->sign($payload->get());
    }

    public function verify($sign, Payload $payload)
    {
        $this->provider->verify($sign, $payload->get());
    }
}
