<?php

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(base_path())->safeLoad();

$loader = new \App\Config\Loaders\ArrayLoader([
    'app' => base_path('config/app.php'),
    'cache' => base_path('config/cache.php'),
]);

dd($loader->parse());

require_once __DIR__ . '/container.php';

$route = $container->get(\League\Route\Router::class);

require_once __DIR__ . '/../routes/web.php';

$response = $route->dispatch($container->get('request'));