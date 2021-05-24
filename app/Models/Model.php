<?php

namespace App\Models;

abstract class Model
{
    public function __get(string $name)
    {
        if (property_exists($this, $name)) {
            return $this->{$name};
        }
    }

    public function __isset(string $name): bool
    {
        return property_exists($this, $name);
    }
}