<?php

namespace App\Application\Services;

use App\Application\Services\BaseCrudService;
use App\Infrastructure\Repositories\TransactionRepository;
use App\Application\Validators\TransactionValidator;
use App\Application\Handlers\TransactionHandler;
use App\Application\DTO\TransactionDTO;
use App\Application\Services\AccountService;
use App\Application\Exceptions\ResourceNotFoundException;
use Throwable;

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
        $repository     = $this->getRepository();
        $accountService = $this->getDependencie('account_service');

        if ($entity->isDeposit()) {
            return $this->handleDeposit();
        }

        try {
            $account = $accountService->find($entity->getDestination());
        } catch (ResourceNotFoundException $exc) {
            $account = null;
        }

        if (empty($account)) {

            $repository->beginTransaction();

            try {
                $accountId = $accountService->store([
                    'balance' => $entity->getAmount(),
                    'id' => $entity->getDestination()
                ]);

                $repository->storeTransaction($entity);
                $repository->commit();
            } catch (Throwable $exc) {
                echo $exc->getMessage();
                $repository->rollBack();
            }

            dd($accountId);
        }
    }

    private function handleDeposit()
    {
        // implementar classe responsável pelo depósito
    }
}