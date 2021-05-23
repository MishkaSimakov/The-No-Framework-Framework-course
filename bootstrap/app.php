<?php

session_start();

function dd(...$vars)
{
    var_dump($vars);

    die();
}

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/..//')->safeLoad();

require_once __DIR__ . '/container.php';

$route = $container->get(\League\Route\Router::class);

require_once __DIR__ . '/../routes/web.php';

$response = $route->dispatch($container->get('request'));