<?php

namespace App\Application\Services;

use App\Application\Services\BaseCrudService;
use App\Infrastructure\Repositories\TransactionRepository;
use App\Application\Validators\TransactionValidator;
use App\Application\Handlers\TransactionHandler;
use App\Application\DTO\TransactionDTO;

class TransactionService extends BaseCrudService
{
    public function __construct()
    {
        //parent::__construct();

        parent::setRepository(new TransactionRepository());
        parent::setHandler(new TransactionHandler());
        parent::setValidator(new TransactionValidator());
    }

    public function store(array $data)
    {
        parent::handle($data, __FUNCTION__);
        $dto = TransactionDTO::fromArray($data);
        parent::validate($dto, __FUNCTION__);

        dd($dto->toDomain());
    }
}