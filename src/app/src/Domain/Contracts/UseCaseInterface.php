<?php

namespace App\Domain\Contracts;

interface UseCaseInterface
{
    public function getDependencieKeysRequired(): array;
}