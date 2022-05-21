<?php

namespace App\Application\Factory;

use App\Domain\UseCases\Transaction\DepositUseCase;
use App\Domain\UseCases\Transaction\WithdrawUseCase;
use App\Domain\UseCases\Transaction\TransferUseCase;
use App\Domain\Contracts\TransactionUseCaseInterface;

class TransactionStrategyFactory
{
    public static function deposit(): TransactionUseCaseInterface
    {
        return new DepositUseCase();
    }

    public static function withdraw(): TransactionUseCaseInterface
    {
        return new WithdrawUseCase();
    }

    public static function transfer(): TransactionUseCaseInterface
    {
        return new TransferUseCase();
    }
}