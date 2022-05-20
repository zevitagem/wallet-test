<?php

namespace App\Application\Contracts;

use App\Domain\Contracts\EntityInterface;

interface DTOInterface
{
    public function toArray(): array;

    public static function fromArray(array $parameters): self;

    public function toDomain(): EntityInterface;
}