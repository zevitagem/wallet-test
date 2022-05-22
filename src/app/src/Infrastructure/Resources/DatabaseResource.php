<?php
declare(strict_types=1);

namespace App\Infrastructure\Resources;

class DatabaseResource
{
    public mixed $id;

    public function getId(): int
    {
        return (int) $this->id;
    }
}