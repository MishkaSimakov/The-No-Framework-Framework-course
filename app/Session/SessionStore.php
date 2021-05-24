<?php

namespace App\Session;

interface SessionStore
{
    public function get(string $key, $default = null);

    public function set($key, $value = null);

    public function exists(string $key);

    public function clear(...$key);
}