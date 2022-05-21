<?php

namespace App\Domain\UseCases\Transaction;

use App\Domain\Entity\Transaction;
use Throwable;
use App\Domain\Entity\Account;
use App\Domain\UseCases\UseCaseResponse;
use InvalidArgumentException;
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
        Transaction $transaction, Account $account
    ): UseCaseResponse
    {
        $amount  = $transaction->getAmount();

        if ($amount > $account->getBalance()) {
            throw new InvalidArgumentException(
                    'The amount requested for withdrawal is greater than available'
            );
        }

        $account->decrement($amount);

        parent::updateAccount($account);

        return parent::end(true,
            [
                'origin' => [
                    'id' => $account->getId(),
                    'balance' => $account->getBalance()
                ]
            ]
        );
    }
}