<?php

namespace App\Application\Services;

use App\Application\Services\BaseCrudService;
use App\Infrastructure\Repositories\AccountRepository;
use App\Application\Factory\EntityFromResourceFactory;
use App\Application\Exceptions\ResourceNotFoundException;
use App\Application\Validators\AccountValidator;
use App\Application\Handlers\AccountHandler;
use App\Domain\Enum\ErrorsEnum;
use App\Application\DTO\AccountDTO;

class AccountService extends BaseCrudService
{
    public function __construct()
    {
        /** AccountRepositoryInterface */
        parent::setRepository(new AccountRepository());
        parent::setHandler(new AccountHandler());
        parent::setValidator(new AccountValidator());
    }

    public function find(int $id)
    {
        $result = $this->getRepository()->getAccount($id);

        if (!empty($result)) {
            return EntityFromResourceFactory::account($result);
        }

        throw new ResourceNotFoundException(ErrorsEnum::EMPTY_VALUE);
    }

    public function store(array $data)
    {
        parent::handle($data, __FUNCTION__);
        $dto = AccountDTO::fromArray($data);
        parent::validate($dto, __FUNCTION__);

        return $this->getRepository()->storeAccount($dto->toDomain());
    }

    public function updateById(int $id, array $data)
    {
        parent::handle($data, __FUNCTION__);
        $dto = AccountDTO::fromArray($data);
        parent::validate($dto, __FUNCTION__);

        return $this->getRepository()->updateAccount($dto->toDomain());
    }
}