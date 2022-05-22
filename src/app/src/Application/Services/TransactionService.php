<?php

namespace App\Application\Services;

use App\Application\Services\BaseCrudService;
use App\Infrastructure\Repositories\TransactionRepository;
use App\Application\Validators\TransactionValidator;
use App\Application\Handlers\TransactionHandler;
use App\Application\DTO\TransactionDTO;
use App\Application\Services\AccountService;
use App\Application\Factory\TransactionStrategyFactory;
use App\Domain\Contracts\TransactionUseCaseInterface;
use RuntimeException;

class TransactionService extends BaseCrudService
{
    public function __construct()
    {
        /** TransactionRepositoryInterface **/
        parent::setRepository(new TransactionRepository());
        parent::setHandler(new TransactionHandler());
        parent::setValidator(new TransactionValidator());

        $this->setDependencie('account_service', new AccountService());
    }

    public function store(array $data)
    {
        parent::handle($data, __FUNCTION__);
        $dto = TransactionDTO::fromArray($data);
        parent::validate($dto, __FUNCTION__);

        $entity   = $dto->toDomain();
        $strategy = null;

        if ($entity->isDeposit()) {
            $strategy = $this->getDepositStrategy($entity);
        }
        if ($entity->isWithdraw()) {
            $strategy = $this->getWithdrawStrategy($entity);
        }
        if ($entity->isTransfer()) {
            $strategy = $this->getTransferStrategy($entity);
        }
        if (!($strategy instanceof TransactionUseCaseInterface)) {
            throw new RuntimeException('Unable to define a transaction processing strategy');
        }

        $strategy->setDependencie(
            'account_service', $this->getDependencie('account_service'));
        $strategy->setDependencie(
            'transaction_repository', $this->getRepository());

        return $strategy->handle($entity);
    }

    private function getDepositStrategy()
    {
        return TransactionStrategyFactory::deposit();
    }

    private function getWithdrawStrategy()
    {
        return TransactionStrategyFactory::withdraw();
    }

    private function getTransferStrategy()
    {
        return TransactionStrategyFactory::transfer();
    }
}