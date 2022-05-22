<?php

namespace App\Infrastructure\Traits;

trait AvailabilityWithDependencie
{
    protected array $dependencies = [];

    public function setDependencie(string $key, $value): void
    {
        $this->dependencies[$key] = $value;
    }

    public function getDependencie(string $key)
    {
        return $this->dependencies[$key] ?? null;
    }
}