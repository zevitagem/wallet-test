<?php

namespace App\Infrastructure\Traits;

trait Configurable
{
    protected array $config;

    public function configure(array $data): self
    {
        $this->config = $data;
        return $this;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function isValidConfig($key): bool
    {
        return (!empty($this->config[$key]));
    }
}