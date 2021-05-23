<?php

namespace App\Config;

use App\Config\Loaders\Loader;

class Config
{
    protected array $config = [];
    protected array $cache = [];

    public function load(array $loaders)
    {
        foreach ($loaders as $loader) {
            if (!$loader instanceof Loader) {
                continue;
            }

            $this->config = array_merge($this->config, $loader->parse());
        }

        return $this;
    }

    public function get(string $key, $default = null)
    {
        if ($this->existsInCache($key)) {
            return $this->fromCache($key);
        }

        return $this->addToCache(
            $key,
            $this->extractFromConfig($key) ?? $default
        );
    }

    protected function extractFromConfig(string $key)
    {
        $filtered = $this->config;

        foreach (explode('.', $key) as $segment) {
            if ($this->exists($filtered, $segment)) {
                $filtered = $filtered[$segment];
                continue;
            }

            return;
        }

        return $filtered;
    }

    protected function existsInCache(string $key): bool
    {
        return isset($this->cache[$key]);
    }

    protected function fromCache(string $key)
    {
        return $this->cache[$key];
    }

    protected function addToCache(string $key, $value)
    {
        $this->cache[$key] = $value;

        return $value;
    }

    protected function exists(array $config, string $key): bool
    {
        return array_key_exists($key, $config);
    }
}