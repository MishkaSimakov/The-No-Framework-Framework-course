<?php

if (!function_exists('base_path')) {
    function base_path(string $path = ''): string
    {
        return __DIR__ . '/..//' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('dd')) {
    function dd(...$vars): void
    {
        dump($vars);

        die();
    }
}