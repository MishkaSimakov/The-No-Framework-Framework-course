<?php

namespace App\Providers;

use App\Views\Extensions\PathExtension;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\Router;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use App\Views\View;

class ViewServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        View::class
    ];

    public function register()
    {
        $container = $this->getContainer();

        $config = $container->get('config');

        $container->share(View::class, function () use ($config, $container) {
            $loader = new FilesystemLoader(base_path('views'));

            $twig = new Environment($loader, [
                'cache' => $config->get('cache.views.path'),
                'debug' => $config->get('app.debug'),
            ]);

            if ($config->get('app.debug')) {
                $twig->addExtension(new DebugExtension());
            }

            $twig->addExtension(new PathExtension($container->get(Router::class)));

            return new View($twig);
        });
    }
}