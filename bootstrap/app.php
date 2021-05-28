<?php

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(base_path())->safeLoad();

require_once __DIR__ . '/container.php';

$route = $container->get(\League\Route\Router::class);

require_once __DIR__ . '/middleware.php';

require_once __DIR__ . '/../routes/web.php';

try {
    $response = $route->dispatch($container->get('request'));
} catch (Exception $exception) {
    $handler = new \App\Exceptions\Handler(
        $exception,
        $container->get(\App\Session\SessionStore::class),
        $container->get(\App\Views\View::class)
    );

    $response = $handler->respond();
}
