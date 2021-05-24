<?php

use App\Controllers\Auth\LoginController;
use App\Controllers\HomeController;

$route->get('/', [HomeController::class, 'index'])->setName('home');

$route->group('/auth', function ($route) {
    $route->get('/login', [LoginController::class, 'index'])->setName('auth.login');
});
