<?php

namespace App\Providers;

use App\Auth\Auth;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use App\Views\View;

class ViewShareServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    public function boot()
    {
        $container = $this->getContainer();

        $container->get(View::class)->share([
            'config' => $container->get('config'),
            'auth' => $container->get(Auth::class),
        ]);
    }

    public function register()
    {
        //
    }
}