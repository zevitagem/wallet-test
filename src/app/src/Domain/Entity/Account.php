<?php
declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Contracts\EntityInterface;

class Account implements EntityInterface
{
    public function __construct(
        public int $id,
        public string $name,
        public int $balance,
        public string $created_at,
        public ?string $deleted_at
    )
    {
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    public static function fromArray(array $array): self
    {
        return new self(
            $array['id'],
            $array['name'],
            $array['balance'],
            $array['created_at'],
            $array['deleted_at']
        );
    }
}