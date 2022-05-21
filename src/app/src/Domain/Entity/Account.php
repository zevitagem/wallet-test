<?php
declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Contracts\EntityInterface;
use App\Domain\Traits\AccountAttributes;

class Account implements EntityInterface
{
    use AccountAttributes;
    
    public function __construct(
        int $id,
        string $name,
        int $balance,
        public string $created_at,
        public ?string $deleted_at
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->balance = $balance;
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