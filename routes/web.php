<?php

use App\Controllers\HomeController;
use Zend\Diactoros\Response;

$route->get('/', [HomeController::class, 'index'])->setName('home');