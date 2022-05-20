<?php

namespace App\Application\Services;

use App\Application\Services\BaseCrudService;
use App\Infrastructure\Repositories\AccountRepository;

class AccountService extends BaseCrudService
{
    public function __construct()
    {
        parent::setRepository(new AccountRepository());
    }

    public function find(int $id)
    {
        $result = $this->getRepository()->getAccount($id);
        //$result = $this->getRepository()->getValidObjects();

        dd($result);
    }
}