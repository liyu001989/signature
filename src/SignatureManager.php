<?php

namespace Liyu\Signature;

use Liyu\Signature\Contracts\Signer;
use InvalidArgumentException;
use Liyu\Signature\Signer\HMAC;
use Liyu\Signature\Signer\RSA;

class SignatureManager
{
    /**
     * The application instance.
     *
     * @var @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * The array of resolved signers.
     *
     * @var array
     */
    protected $signers = [];

    /**
     * Constructor.
     *
     * @param $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Resolve a signer instance.
     *
     * @param string $name
     *
     * @return \Liyu\Signature\Constracts\Signer
     */
    public function signer($name = null)
    {
        $name = $name ?: $this->getDefaultSigner();

        if (!isset($this->signers[$name])) {
            $this->signers[$name] = $this->resolve($name);
        }

        return $this->signers[$name];
    }

    /**
     * Get the name of the default signer.
     *
     * @return string
     */
    public function getDefaultSigner()
    {
        return $this->app['config']['signature.default'];
    }

    protected function resolve($name)
    {
        $config = $this->getConfig($name);

        if (is_null($config)) {
            throw new InvalidArgumentException("Signer [{$name}] is not defined.");
        }

        $driverMethod = 'create'.ucfirst($config['driver']).'Driver';

        if (method_exists($this, $driverMethod)) {
            return $this->{$driverMethod}($config['options']);
        }

        throw new InvalidArgumentException("Signer [{$name}] is not defined.");
    }

    public function createHMACDriver($config)
    {
        return new HMAC($config);
    }

    public function createRSADriver($config)
    {
        return new RSA($config);
    }

    protected function getConfig($name)
    {
        return $this->app['config']["signature.{$name}"];
    }

    public function __call($method, $parameters)
    {
        return $this->signer()->$method(...$parameters);
    }
}
