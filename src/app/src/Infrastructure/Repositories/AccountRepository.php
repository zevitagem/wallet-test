<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entity\Account;
use App\Infrastructure\Repositories\Database\MYSQL\MYSQLCRUDRepository;

class AccountRepository extends MYSQLCRUDRepository
{
    public function getEntityClass(): string
    {
        return Account::class;
    }

    public function getAccount(int $id)
    {
        return parent::getById($id);
    }
}