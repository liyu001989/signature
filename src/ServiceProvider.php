<?php

namespace Liyu\Signature;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        $this->setupConfig();
    }

    protected function setupConfig()
    {
    }
}
