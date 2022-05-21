<?php
declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Contracts\EntityInterface;

abstract class BaseEntity implements EntityInterface
{
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}