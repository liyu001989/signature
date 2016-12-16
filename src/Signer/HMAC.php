<?php

namespace Liyu\Signature\Signer;

use Liyu\Signature\Contracts\Signer;

class HMAC implements Signer
{
    /**
     * key for sign.
     *
     * @var string
     */
    protected $key;

    /**
     * sign algo.
     *
     * @var string
     */
    protected $algo;

    public function __construct($key = null, $algo = 'sha1')
    {
        $this->key = $key;
        $this->algo = $algo;
    }

    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setAlgo($key)
    {
        $this->key = $key;

        return $this;
    }

    public function getAlgo()
    {
        return $this->algo;
    }

    /**
     * make a sign for.
     *
     * @param array $params
     *
     * @return string
     */
    public function sign($signString)
    {
        return base64_encode(hash_hmac($this->algo, $signString, $this->key));
    }

    /**
     * verify a sign.
     *
     * @param mixed $sign
     * @param array $params
     *
     * @return bool
     */
    public function verify($sign, $signString)
    {
        return $sign == $this->sign($signString);
    }
}
