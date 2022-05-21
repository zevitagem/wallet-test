<?php

namespace App\Application\Services;

use App\Application\Services\BaseCrudService;
use App\Infrastructure\Repositories\TransactionRepository;
use App\Application\Validators\TransactionValidator;
use App\Application\Handlers\TransactionHandler;
use App\Application\DTO\TransactionDTO;
use App\Application\Services\AccountService;
use App\Application\UseCases\DepositUseCase;
use App\Application\UseCases\WithdrawUseCase;
use App\Domain\Entity\Transaction;

class TransactionService extends BaseCrudService
{
    public function __construct()
    {
        //parent::__construct();

        parent::setRepository(new TransactionRepository());
        parent::setHandler(new TransactionHandler());
        parent::setValidator(new TransactionValidator());

        $this->setDependencie('account_service', new AccountService());
    }

    public function save(array $data)
    {
        parent::handle($data, __FUNCTION__);
        $dto = TransactionDTO::fromArray($data);
        parent::validate($dto, __FUNCTION__);

        $entity         = $dto->toDomain();
        //$repository     = $this->getRepository();
        //$accountService = $this->getDependencie('account_service');

        if ($entity->isDeposit()) {
            return $this->handleDeposit($entity);
        }

        if ($entity->isWithdraw()) {
            return $this->handleWithdraw($entity);
        }
    }

    private function handleDeposit(Transaction $transaction)
    {
        $useCase = new DepositUseCase();
        $useCase->setDependencie(
            'account_service', $this->getDependencie('account_service'));
        $useCase->setDependencie(
            'transaction_repository', $this->getRepository());

        return $useCase->handle($transaction);
    }

    private function handleWithdraw(Transaction $transaction)
    {
        $useCase = new WithdrawUseCase();
        $useCase->setDependencie(
            'account_service', $this->getDependencie('account_service'));
        $useCase->setDependencie(
            'transaction_repository', $this->getRepository());

        return $useCase->handle($transaction);
    }
}