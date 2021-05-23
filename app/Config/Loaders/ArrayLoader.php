<?php

namespace App\Config\Loaders;

use Exception;

class ArrayLoader implements Loader
{
    protected array $files;

    public function __construct(array $files)
    {
        $this->files = $files;
    }

    public function parse(): array
    {
        $parsed = [];

        foreach ($this->files as $key => $path) {
            try {
                $parsed[$key] = require $path;
            } catch (Exception $exception) {
                //
            }
        }

        return $parsed;
    }
}