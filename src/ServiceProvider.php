<?php

namespace Liyu\Signature;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Illuminate\Foundation\Application as LaravelApplication;
use Laravel\Lumen\Application as LumenApplication;

class ServiceProvider extends LaravelServiceProvider
{
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
        $this->app->bind(['Liyu\\Signature\\SignatureManager' => 'signature'], function ($app) {
            return new SignatureManager($app);
        });
    }

    public function providers()
    {
        return ['signature', 'Liyu\\Signature\\SignatureManager'];
    }
}
