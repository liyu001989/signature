<?php

namespace Liyu\Signature\Signer;

use Liyu\Signature\Contracts\Signer;

class RSA extends AbstractSigner implements Signer
{
    /**
     * public key for veirfy.
     *
     * @var mixed
     */
    protected $publicKey;

    /**
     * private key for sign.
     *
     * @var mixed
     */
    protected $privateKey;

    /**
     * algo.
     *
     * @var string
     */
    protected $algo;

    /**
     * Constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->setConfig($config);
    }

    /**
     * setPublicKey.
     *
     * @param mixed $publicKey
     *
     * @return $this
     */
    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;

        return $this;
    }

    /**
     * getPublicKey.
     *
     * @return string
     */
    public function getPublicKey()
    {
        if (is_file($this->publicKey)) {
            return file_get_contents($this->publicKey);
        }

        return $this->publicKey;
    }

    /**
     * setPrivateKey.
     *
     * @param mixed $privateKey
     *
     * @return $this
     */
    public function setPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;

        return $this;
    }

    /**
     * getPrivateKey.
     *
     * @return string
     */
    public function getPrivateKey()
    {
        if (is_file($this->privateKey)) {
            return file_get_contents($this->privateKey);
        }

        return $this->privateKey;
    }

    /**
     * setAlgo.
     *
     * @param string $algo
     *
     * @return $this
     */
    public function setAlgo($algo)
    {
        $this->algo = $algo;

        return $this;
    }

    /**
     * getAlgo.
     *
     * @return string
     */
    public function getAlgo()
    {
        if ($this->algo && in_array($this->algo, openssl_get_md_methods(true))) {
            return $this->algo;
        }

        return 'sha256';
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
