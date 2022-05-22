<?php

namespace App\Application\DTO;

use App\Application\DTO\DTO;
use App\Domain\Traits\TransactionAttributes;
use App\Domain\Contracts\EntityInterface;
use App\Domain\Entity\Transaction;
use DateTime;

final class TransactionDTO extends DTO
{
    use TransactionAttributes;

    public function __construct()
    {
    }

    public function toDomain(): EntityInterface
    {
        return new Transaction(
            $this->getType(),
            $this->getOrigin(),
            $this->getDestination(),
            $this->getAmount(),
            (new DateTime())->format('Y-m-d H:i:s')
        );
    }
}