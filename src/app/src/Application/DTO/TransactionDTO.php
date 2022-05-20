<?php

namespace App\Application\DTO;

use App\Application\DTO\DTO;
use App\Domain\Traits\TransactionAttributes;
use App\Domain\Contracts\EntityInterface;
use App\Domain\Entity\Transaction;

class TransactionDTO extends DTO
{
    use TransactionAttributes;

    public function toDomain(): EntityInterface
    {
        return new Transaction(
            $this->getType(),
            $this->getOrigin(),
            $this->getDestination(),
            $this->getAmount()
        );
    }
}