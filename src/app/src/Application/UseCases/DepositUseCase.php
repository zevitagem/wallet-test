<?php

namespace App\Application\UseCases;

use App\Domain\Entity\Transaction;
use App\Application\Exceptions\ResourceNotFoundException;
use Throwable;
use App\Domain\Entity\Account;
use App\Application\UseCases\UseCaseResponse;
use App\Application\UseCases\BaseUseCase;
use App\Application\Contracts\TransactionUseCaseInterface;

class DepositUseCase extends BaseUseCase implements TransactionUseCaseInterface
{
    public function getDependencieKeysRequired(): array
    {
        return ['account_service', 'transaction_repository'];
    }

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
        $accountService = $this->getDependencie('account_service');

        $account->sum($transaction->getAmount());
        
        $accountData = $account->toArray();
        unset($accountData['created_at'], $accountData['deleted_at']);

        $accountService->updateById($account->getId(), $accountData);

        return $this->end(true,
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
        $accountService = $this->getDependencie('account_service');

        $accountService->store([
            'balance' => $transaction->getAmount(),
            'id' => $transaction->getDestination()
        ]);

        return $this->end(true,
            [
                'destination' => [
                    'id' => $transaction->getDestination(),
                    'balance' => $transaction->getAmount()
                ]
            ]
        );
    }
}