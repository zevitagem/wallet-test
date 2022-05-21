<?php

namespace App\Domain\Traits;

trait AccountAttributes
{
    public int $id;
    public string $name;
    public int $balance;

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}