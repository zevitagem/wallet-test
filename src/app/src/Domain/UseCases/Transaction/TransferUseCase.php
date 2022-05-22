<?php

namespace App\Domain\UseCases\Transaction;

use App\Domain\Entity\Transaction;
use Throwable;
use App\Domain\Entity\Account;
use App\Domain\UseCases\UseCaseResponse;
use App\Domain\UseCases\Transaction\BaseTransactionUseCase;
use App\Application\Exceptions\ResourceNotFoundException;

class TransferUseCase extends BaseTransactionUseCase
{
    public function handle(Transaction $transaction): UseCaseResponse
    {
        $this->checkDependencies();

        $originAccount = $this
            ->getDependencie('account_service')
            ->find($transaction->getOrigin());

        try {
            $destinationAccount = $this
                ->getDependencie('account_service')
                ->find($transaction->getDestination());
        } catch (ResourceNotFoundException $exc) {
            $destinationAccount = null;
        }

        $transactionRepository = $this->getDependencie('transaction_repository');
        $transactionRepository->beginTransaction();

        try {
            if (!empty($originAccount) && !empty($destinationAccount)) {
                $result = $this->handleWithBothExistent(
                    $transaction, $originAccount, $destinationAccount
                );
            } else {
                $result = $this->handleWithOnlyOrigin(
                    $transaction, $originAccount
                );
            }

            $transactionRepository->storeTransaction($transaction);
            $transactionRepository->commit();
        } catch (Throwable $exc) {
            $transactionRepository->rollBack();
            $result = $this->end(false, $exc->getMessage());
        } finally {
            return $result;
        }
    }

    private function handleWithOnlyOrigin(
        Transaction $transaction, Account $originAccount
    ): UseCaseResponse
    {
        $amount      = $transaction->getAmount();
        $destination = $transaction->getDestination();

        if (!parent::hasEnoughBalance($amount, $originAccount)) {
            return parent::end(true, null); //throws exception before
        }

        parent::createAccount(
            $destination, $amount
        );

        $originAccount->decrement($amount);
        parent::updateAccount($originAccount);

        return parent::end(true,
            [
                'origin' => [
                    'id' => $originAccount->getId(),
                    'balance' => $originAccount->getBalance()
                ],
                'destination' => [
                    'id' => $destination,
                    'balance' => $amount
                ]
            ]
        );
    }

    private function handleWithBothExistent(
        Transaction $transaction, Account $originAccount,
        Account $destinationAccount
    ): UseCaseResponse
    {
        $amount = $transaction->getAmount();
        if (!parent::hasEnoughBalance($amount, $originAccount)) {
            return parent::end(true, null); //throws exception before
        }

        if ($originAccount->getId() != $destinationAccount->getId()) {
            $originAccount->decrement($amount);
            $destinationAccount->sum($amount);

            parent::updateAccount($originAccount);
            parent::updateAccount($destinationAccount);
        }

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