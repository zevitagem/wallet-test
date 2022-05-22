<?php
declare(strict_types=1);

namespace App\Domain\Contracts\Repositories;

use App\Domain\Entity\Transaction;

interface TransactionRepositoryInterface
{
    public function storeTransaction(Transaction $transaction);
}