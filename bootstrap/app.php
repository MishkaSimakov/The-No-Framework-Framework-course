<?php

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

var_dump(new \App\Config\Config());