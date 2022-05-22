<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entity\Account;
use App\Infrastructure\Repositories\Database\MYSQL\MYSQLCRUDRepository;
use App\Domain\Contracts\Repositories\AccountRepositoryInterface;

class AccountRepository extends MYSQLCRUDRepository implements AccountRepositoryInterface
{
    public function getEntityClass(): string
    {
        return Account::class;
    }

    public function getAccount(int $id)
    {
        return parent::getById($id);
    }

    public function storeAccount(Account $account)
    {
        return parent::store([
            'id' => $account->getId(),
            'name' => $account->getName(),
            'balance' => $account->getBalance()
        ]);
    }

    public function updateAccount(Account $account)
    {
        return parent::updateById($account->getId(),
            [
                //'id' => $account->getId(),
                //'name' => $account->getName(),
                'balance' => $account->getBalance()
            ]
        );
    }
}