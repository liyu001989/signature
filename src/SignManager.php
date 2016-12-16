<?php

namespace Liyu\Signature;

class SignManager
{
    protected $signer;

    public function __construct($signer)
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
        if (is_string($signData)) {
            return $signData;
        }

        // 去除空值
        $params = array_filter($signData);

        ksort($params);

        $signParams = [];

        // 拼接为key=value&key1=value1
        foreach ($params as $key => $value) {
            $signParams[] = $key.'='.$value;
        }

        // 使用&链接参数
        return implode('&', $signParams);
    }

    public function __call($name, $arguments)
    {
        call_user_func_array([$this->signer, $name], $arguments);

        return $this;
    }
}
