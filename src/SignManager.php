<?php

namespace Liyu\Signature;

use Liyu\Signature\Contracts\Signer;

class SignManager
{
    protected $signer;

    public function __construct(Signer $signer)
    {
        $this->signer = $signer;
    }

    public function getSigner()
    {
        return $this->signer;
    }

    public function sign($signData)
    {
        $signData = $this->getSignString($signData);

        return $this->signer->sign($signData);
    }

    public function verify($sign, $signData)
    {
        $signData = $this->getSignString($signData);

        return $this->signer->verify($sign, $signData);
    }

    public function getSignString($signData)
    {
        if (!is_array($signData)) {
            return $signData;
        }

        $params = $this->multiksort($signData);
        // key=value&key1=value1
        foreach ($params as $key => $value) {
            if (is_array($value)) {
                $value = json_encode($value);
            }
            $signParams[] = $key.'='.$value;
        }

        return implode('&', $signParams);
    }

    protected function multiksort(&$params)
    {
        if (is_array($params)) {
            $params = array_filter($params);

            ksort($params);
            array_walk($params, [$this, 'multiksort']);
        }

        return $params;
    }

    public function __call($name, $arguments)
    {
        call_user_func_array([$this->signer, $name], $arguments);

        return $this;
    }
}

