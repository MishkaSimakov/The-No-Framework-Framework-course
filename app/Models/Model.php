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

    public function update(array $columns)
    {
        foreach ($columns as $column => $value) {
            $this->{$column} = $value;
        }
    }
}