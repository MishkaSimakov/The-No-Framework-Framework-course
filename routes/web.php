<?php

use App\Controllers\Auth\LoginController;
use App\Controllers\HomeController;

$route->get('/', [HomeController::class, 'index'])->setName('home');

$route->group('/auth', function ($route) {
    $route->get('/login', [LoginController::class, 'index'])->setName('auth.login');
    $route->post('/login', [LoginController::class, 'login']);

    $route->post('/logout', [\App\Controllers\Auth\LogoutController::class, 'logout'])->setName('auth.logout');

    $route->get('/register', [\App\Controllers\Auth\RegisterController::class, 'index'])->setName('auth.register');
    $route->post('/register', [\App\Controllers\Auth\RegisterController::class, 'register']);
});
