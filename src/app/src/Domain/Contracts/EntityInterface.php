<?php

declare(strict_types=1);

namespace App\Domain\Contracts;

interface EntityInterface
{
    public static function fromArray(array $array): EntityInterface;
}
