<?php

namespace App\Domain\UseCases\Transaction;

use App\Domain\Entity\Account;
use App\Domain\UseCases\BaseUseCase;
use App\Domain\Contracts\TransactionUseCaseInterface;

abstract class BaseTransactionUseCase extends BaseUseCase implements TransactionUseCaseInterface
{
    public function getDependencieKeysRequired(): array
    {
        return ['account_service', 'transaction_repository'];
    }

    protected function updateAccount(Account $account)
    {
        if (empty($accountService = $this->getDependencie('account_service'))) {
            return false;
        }

        $accountData = $account->toArray();
        unset($accountData['created_at'], $accountData['deleted_at']);

        $accountService->updateById($account->getId(), $accountData);
    }
}