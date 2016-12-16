<?php

namespace Liyu\Signature;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Illuminate\Foundation\Application as LaravelApplication;
use Laravel\Lumen\Application as LumenApplication;
use Liyu\Signature\Signer\HMAC;

class ServiceProvider extends LaravelServiceProvider
{
    protected $defer = true;

    public function boot()
    {
        $this->setupConfig();
    }

    protected function setupConfig()
    {
        $source = realpath(__DIR__.'/../config/config.php');

        if ($this->app instanceof LaravelApplication) {
            if ($this->app->runningInConsole()) {
                $this->publishes([
                    $source => config_path('signature.php'),
                ]);
            }
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('signature');
        }

        $this->mergeConfigFrom($source, 'signature');
    }

    public function register()
    {
        $this->app->bind(['Liyu\\Signature\\SignManager' => 'signature'], function ($app) {
            $signerName = config('signature.signer');
            // TODO not good
            switch ($signerName) {
                case 'hmac':
                default:
                    $signer = new HMAC();
                    if ($algo = config('signature'.$signerName)) {
                        $signer->setAlgo($algo);
                    }
                break;
            }

            return new SignManager($signer);
        });
    }

    public function providers()
    {
        return ['signature', 'Liyu\\Signature\\SignManager'];
    }
}
