<?php

namespace Liyu\Signature\Contracts;

interface Signer
{
    public function sign($signString);

    public function verify($sign, $signString);
}
