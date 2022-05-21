<?php
declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Traits\TransactionAttributes;
use App\Domain\Entity\BaseEntity;

class Transaction extends BaseEntity
{
    use TransactionAttributes;

    public function __construct(
        string $type,
        int $origin,
        int $destination,
        int $amount,
        public string $created_at
    )
    {
        $this->type        = $type;
        $this->origin      = $origin;
        $this->destination = $destination;
        $this->amount      = $amount;
    }

    public static function fromArray(array $array): self
    {
        return new self(
            $array['type'],
            $array['origin'],
            $array['destination'],
            $array['amount'],
            $array['created_at']
        );
    }
}