<?php

namespace App\Domain\UseCases\Transaction;

use App\Domain\Entity\Transaction;
use Throwable;
use App\Domain\Entity\Account;
use App\Domain\UseCases\UseCaseResponse;
use App\Domain\UseCases\Transaction\BaseTransactionUseCase;

class WithdrawUseCase extends BaseTransactionUseCase
{
    public function handle(Transaction $transaction): UseCaseResponse
    {
        $this->checkDependencies();

        $account = $this
            ->getDependencie('account_service')
            ->find($transaction->getOrigin());

        $transactionRepository = $this->getDependencie('transaction_repository');
        $transactionRepository->beginTransaction();

        try {
            $result = $this->handleWithExistentOrigin(
                $transaction, $account
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

    private function handleWithExistentOrigin(
        Transaction $transaction, Account $originAccount
    ): UseCaseResponse
    {
        $amount = $transaction->getAmount();
        if (!parent::hasEnoughBalance($amount, $originAccount)) {
            return parent::end(true, null); //throws exception before
        }

        $originAccount->decrement($amount);

        parent::updateAccount($originAccount);

        return parent::end(true,
            [
                'origin' => [
                    'id' => $originAccount->getId(),
                    'balance' => $originAccount->getBalance()
                ]
            ]
        );
    }
}