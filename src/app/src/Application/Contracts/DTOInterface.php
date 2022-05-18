<?php

namespace App\Application\Contracts;

interface DTOInterface
{
    public function toArray(): array;

    public static function fromArray(array $parameters): self;
}