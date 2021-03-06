<?php

namespace App\Providers;

use App\Cookie\CookieJar;
use App\Session\Flash;
use App\Session\SessionStore;
use League\Container\ServiceProvider\AbstractServiceProvider;

class CookieServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        CookieJar::class
    ];

    public function register()
    {
        $container = $this->getContainer();

        $container->share(CookieJar::class, function () use ($container) {
            return new CookieJar();
        });
    }
}