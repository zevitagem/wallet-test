<?php

namespace App\Application\DTO;

use App\Application\DTO\DTO;
use App\Domain\Traits\AccountAttributes;
use App\Domain\Contracts\EntityInterface;
use App\Domain\Entity\Account;
use DateTime;

final class AccountDTO extends DTO
{
    use AccountAttributes;

    public function __construct()
    {
    }

    public function toDomain(): EntityInterface
    {
        return new Account(
            $this->getId(),
            $this->getName(),
            $this->getBalance(),
            (new DateTime())->format('Y-m-d H:i:s'),
            null
        );
    }
}