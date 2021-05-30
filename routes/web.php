<?php

use App\Controllers\Auth\LoginController;
use App\Controllers\Auth\LogoutController;
use App\Controllers\Auth\RegisterController;
use App\Controllers\DashboardController;
use App\Controllers\HomeController;
use App\Middleware\Authenticated;
use App\Middleware\Guest;


$route->get('/', [HomeController::class, 'index'])->setName('home');

$route->group('', function ($route) {
    $route->get('/dashboard', [DashboardController::class, 'index'])->setName('dashboard');

    $route->post('auth/logout', [LogoutController::class, 'logout'])->setName('auth.logout');
})->middleware($container->get(Authenticated::class));

$route->group('', function ($route) {
    $route->get('auth/login', [LoginController::class, 'index'])->setName('auth.login');
    $route->post('auth/login', [LoginController::class, 'login']);

    $route->get('auth/register', [RegisterController::class, 'index'])->setName('auth.register');
    $route->post('auth/register', [RegisterController::class, 'register']);
})->middleware($container->get(Guest::class));

$route->get('/posts', [\App\Controllers\PostController::class, 'index'])->setName('posts.index');