<?php

namespace Liyu\Signature\Contracts;

interface Signature
{
    public function sign($signString);

    public function verify($sign, $signString);
}
