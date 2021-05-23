<?php

use Zend\Diactoros\Response;

$route->get('/', [App\Controllers\HomeController::class, 'index'])->setName('home');