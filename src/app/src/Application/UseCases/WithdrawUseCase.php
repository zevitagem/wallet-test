<?php

namespace App\Application\UseCases;

use App\Domain\Entity\Transaction;
use Throwable;
use App\Domain\Entity\Account;
use App\Application\UseCases\UseCaseResponse;
use App\Application\UseCases\BaseUseCase;
use App\Application\Contracts\TransactionUseCaseInterface;
use InvalidArgumentException;

class WithdrawUseCase extends BaseUseCase implements TransactionUseCaseInterface
{
    public function getDependencieKeysRequired(): array
    {
        return ['account_service', 'transaction_repository'];
    }

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
        if ($transaction->getAmount() > $account->getBalance()) {
            throw new InvalidArgumentException(
                'The amount requested for withdrawal is greater than available'
            );
        }

        $account->decrement($transaction->getAmount());

        $accountData = $account->toArray();
        unset($accountData['created_at'], $accountData['deleted_at']);

        $accountService = $this->getDependencie('account_service');
        $accountService->updateById($account->getId(), $accountData);

        return $this->end(true,
            [
                'origin' => [
                    'id' => $account->getId(),
                    'balance' => $account->getBalance()
                ]
            ]
        );
    }
}