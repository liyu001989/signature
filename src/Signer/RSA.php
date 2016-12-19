<?php

namespace Liyu\Signature\Signer;

use Liyu\Signature\Contracts\Signer;

class RSA extends AbstractSigner implements Signer
{
    /**
     * key for sign.
     *
     * @var string
     */
    protected $publicKey;

    protected $privateKey;

    protected $algo;

    public function __construct(Array $config = [])
    {
        $this->setConfig($config);
    }

    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;

        return $this;
    }

    public function getPublicKey()
    {
        if (is_file($this->publicKey)) {
            return file_get_contents($this->publicKey);
        }

        return $this->publicKey;
    }

    public function setPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;

        return $this;
    }

    public function getPrivateKey()
    {
        if (is_file($this->privateKey)) {
            return file_get_contents($this->privateKey);
        }

        return $this->privateKey;
    }

    public function setAlgo($algo)
    {
        $this->algo = $algo;

        return $this;
    }

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

        $pkeyid = openssl_pkey_get_private($this->getPrivateKey());

        openssl_sign($signString, $signature, $pkeyid, $this->getAlgo());

        openssl_free_key($pkeyid);

        return base64_encode($signature);
    }

    /**
     * verify a sign.
     *
     * @param mixed $sign
     * @param array $params
     *
     * @return bool
     */
    public function verify($signature, $data)
    {
        $signature = base64_decode($signature);

        $signString = $this->getSignString($data);

        $pubkeyid = openssl_pkey_get_public($this->getPublicKey());

        return openssl_verify($signString, $signature, $pubkeyid, $this->getAlgo()) == 1;
    }
}
