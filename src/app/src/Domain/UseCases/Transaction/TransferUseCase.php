<?php

namespace App\Domain\UseCases\Transaction;

use App\Domain\Entity\Transaction;
use Throwable;
use App\Domain\Entity\Account;
use App\Domain\UseCases\UseCaseResponse;
use InvalidArgumentException;
use App\Domain\UseCases\Transaction\BaseTransactionUseCase;

class TransferUseCase extends BaseTransactionUseCase
{
    public function handle(Transaction $transaction): UseCaseResponse
    {
        $this->checkDependencies();

        $originAccount = $this
            ->getDependencie('account_service')
            ->find($transaction->getOrigin());

        $destinationAccount = $this
            ->getDependencie('account_service')
            ->find($transaction->getDestination());

        $transactionRepository = $this->getDependencie('transaction_repository');
        $transactionRepository->beginTransaction();

        try {
            $result = $this->handleWithBothExistent(
                $transaction, $originAccount, $destinationAccount
            );

            $transactionRepository->storeTransaction($transaction);
            $transactionRepository->commit();
        } catch (Throwable $exc) {
            $transactionRepository->rollBack();
            $result = $this->end(false, $exc->getMessage());
        } finally {
            return $result;
        }
    }

    private function handleWithBothExistent(
        Transaction $transaction,
        Account $originAccount,
        Account $destinationAccount
    ): UseCaseResponse
    {
        $amount = $transaction->getAmount();
        if ($amount > $originAccount->getBalance()) {
            throw new InvalidArgumentException(
                    'The amount requested for transfer is greater than available'
            );
        }

        $originAccount->decrement($amount);
        $destinationAccount->sum($amount);

        parent::updateAccount($originAccount);
        parent::updateAccount($destinationAccount);

        return parent::end(true,
            [
                'origin' => [
                    'id' => $originAccount->getId(),
                    'balance' => $originAccount->getBalance()
                ],
                'destination' => [
                    'id' => $destinationAccount->getId(),
                    'balance' => $destinationAccount->getBalance()
                ]
            ]
        );
    }
}