<?php

namespace App\Application\Contracts;

interface UseCaseInterface
{
    public function getDependencieKeysRequired(): array;
}