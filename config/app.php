<?php

return [
    'name' => env('APP_NAME'),
    'debug' => env('APP_DEBUG', false),

    'providers' => [
        \App\Providers\AppServiceProvider::class,
        \App\Providers\ViewServiceProvider::class,
        \App\Providers\DatabaseServiceProvider::class,
        \App\Providers\SessionServiceProvider::class,
        \App\Providers\HashServiceProvider::class,
        \App\Providers\AuthServiceProvider::class,
        \App\Providers\FlashServiceProvider::class,
        \App\Providers\CsrfServiceProvider::class,
        \App\Providers\ValidationServiceProvider::class,
        \App\Providers\CookieServiceProvider::class,
        \App\Providers\ViewShareServiceProvider::class,
    ],

    'middleware' => [
        \App\Middleware\ShareValidationErrors::class,
        \App\Middleware\ClearValidationErrors::class,
        \App\Middleware\Authenticate::class,
        \App\Middleware\CsrfGuard::class,
    ]
];
