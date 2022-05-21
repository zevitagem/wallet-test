<?php

namespace App\Domain\Contracts;

use App\Domain\UseCases\UseCaseResponse;
use App\Domain\Entity\Transaction;
use App\Domain\Contracts\UseCaseInterface;

interface TransactionUseCaseInterface extends UseCaseInterface
{
    public function handle(Transaction $transaction): UseCaseResponse;
}