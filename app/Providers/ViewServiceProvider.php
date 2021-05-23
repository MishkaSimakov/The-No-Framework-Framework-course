<?php

namespace App\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Twig\Environment;
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

        $loader = new FilesystemLoader(__DIR__ . '/../../views');

        $twig = new Environment($loader, [
            'cache' => false,
        ]);

        $container->share(View::class, function () use ($twig) {
            return new View($twig);
        });
    }
}