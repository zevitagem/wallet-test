<?php
declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Contracts\EntityInterface;
use App\Domain\Traits\TransactionAttributes;

class Transaction implements EntityInterface
{
    use TransactionAttributes;

    public function __construct(
        string $type, int $origin, int $destination, int $amount
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
            $array['amount']
        );
    }
}