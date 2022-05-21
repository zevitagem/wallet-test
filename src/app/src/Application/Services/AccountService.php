<?php

namespace App\Application\Services;

use App\Application\Services\BaseCrudService;
use App\Infrastructure\Repositories\AccountRepository;
use App\Application\Factory\EntityFromResourceFactory;
use App\Application\Exceptions\ResourceNotFoundException;
use App\Domain\Enum\ErrorsEnum;

class AccountService extends BaseCrudService
{
    public function __construct()
    {
        parent::setRepository(new AccountRepository());
    }

    public function find(int $id)
    {
        $result = $this->getRepository()->getAccount($id);

        if (!empty($result)) {
            return EntityFromResourceFactory::account($result);
        }

        throw new ResourceNotFoundException(ErrorsEnum::EMPTY_VALUE);
    }
}