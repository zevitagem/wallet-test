<?php

namespace App\Application\Contracts;

use App\Application\UseCases\UseCaseResponse;
use App\Domain\Entity\Transaction;
use App\Application\Contracts\UseCaseInterface;

interface TransactionUseCaseInterface extends UseCaseInterface
{
    public function handle(Transaction $transaction): UseCaseResponse;
}