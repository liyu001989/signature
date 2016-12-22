<?php

namespace Liyu\Signature\Signer;

use Liyu\Signature\Contracts\Signer;

class HMAC extends AbstractSigner implements Signer
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

    public function __construct(array $config = [])
    {
        $this->setConfig($config);
    }

    /**
     * setKey.
     *
     * @param string $key
     *
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * getKey.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * setAlgo.
     *
     * @param string $key
     *
     * @return $this
     */
    public function setAlgo($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * getAlgo.
     *
     * @return string
     */
    public function getAlgo()
    {
        return $this->algo ?: 'sha1';
    }

    /**
     * make a signature.
     *
     * @param array $params
     *
     * @return string
     */
    public function sign($data)
    {
        $signString = $this->getSignString($data);

        return base64_encode(hash_hmac($this->getAlgo(), $signString, $this->getKey()));
    }

    /**
     * verify a signature.
     *
     * @param mixed $sign
     * @param array $params
     *
     * @return bool
     */
    public function verify($signature, $data)
    {
        $signString = $this->getSignString($data);

        return $signature == $this->sign($signString);
    }
}
