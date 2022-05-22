<?php

namespace App\Infrastructure\Repositories;

use App\Infrastructure\Repositories\Database\MYSQL\MYSQLCRUDRepository;
use App\Domain\Entity\Transaction;
use App\Domain\Contracts\Repositories\TransactionRepositoryInterface;

class TransactionRepository extends MYSQLCRUDRepository implements TransactionRepositoryInterface
{
    public function getEntityClass(): string
    {
        return Transaction::class;
    }

    public function storeTransaction(Transaction $transaction)
    {
        return parent::store([
            'type' => $transaction->getType(),
            'origin' => $transaction->getOrigin(),
            'destination' => $transaction->getDestination(),
            'amount' => $transaction->getAmount()
        ]);
    }
}