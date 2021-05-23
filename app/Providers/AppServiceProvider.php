<?php

namespace App\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\Router;
use Zend\Diactoros\ServerRequestFactory;

class AppServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        Router::class,
        'request',
        'emitter'
    ];

    public function register()
    {
        $container = $this->getContainer();

        $container->share(Router::class, function () use ($container) {
            $strategy = (new \League\Route\Strategy\ApplicationStrategy())->setContainer($container);

            return (new Router)->setStrategy($strategy);
        });
        $container->share('request', function () {
            return ServerRequestFactory::fromGlobals(
                $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
            );
        });
        $container->share('emitter', function () {
            return new \Zend\HttpHandlerRunner\Emitter\SapiEmitter;
        });
    }
}