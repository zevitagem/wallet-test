<?php

namespace App\Domain\UseCases\Transaction;

use App\Domain\Entity\Transaction;
use App\Application\Exceptions\ResourceNotFoundException;
use Throwable;
use App\Domain\Entity\Account;
use App\Domain\UseCases\UseCaseResponse;
use App\Domain\UseCases\Transaction\BaseTransactionUseCase;

class DepositUseCase extends BaseTransactionUseCase
{
    public function handle(Transaction $transaction): UseCaseResponse
    {
        $this->checkDependencies();

        try {
            $account = $this
                ->getDependencie('account_service')
                ->find($transaction->getDestination());
        } catch (ResourceNotFoundException $exc) {
            $account = null;
        }

        $transactionRepository = $this->getDependencie('transaction_repository');
        $transactionRepository->beginTransaction();
        
        try {
            if (empty($account)) {
                $result = $this->handleWithNonExistentDestination($transaction);
            } else {
                $result = $this->handleWithExistentDestination(
                    $transaction, $account
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

    private function handleWithExistentDestination(
        Transaction $transaction, Account $account
    ): UseCaseResponse
    {
        $account->sum($transaction->getAmount());

        parent::updateAccount($account);

        return parent::end(true,
            [
                'destination' => [
                    'id' => $account->getId(),
                    'balance' => $account->getBalance()
                ]
            ]
        );
    }

    private function handleWithNonExistentDestination(
        Transaction $transaction
    ): UseCaseResponse
    {
        $amount      = $transaction->getAmount();
        $destination = $transaction->getDestination();

        $accountService = $this->getDependencie('account_service');
        $accountService->store([
            'balance' => $amount,
            'id' => $destination
        ]);

        return parent::end(true,
            [
                'destination' => [
                    'id' => $destination,
                    'balance' => $amount
                ]
            ]
        );
    }
}