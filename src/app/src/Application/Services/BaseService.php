<?php

namespace App\Application\Services;

use App\Application\Exceptions\ValidatorException;
use App\Application\Handlers\BaseHandler;
use App\Application\Validators\BaseValidator;
use App\Infrastructure\Repositories\BaseRepository;
use App\Infrastructure\Traits\AvailabilityWithDependencie;
use App\Application\Contracts\DTOInterface;

abstract class BaseService
{
    use AvailabilityWithDependencie;
    
    protected BaseRepository $repository;
    protected BaseValidator $validator;
    protected BaseHandler $handler;

    public function validate(DTOInterface $data, string $method): bool
    {
        $validator = $this->validator;
        $validator->setData($data);

        if ($validator->run($method) === false) {
            throw new ValidatorException($validator->translate());
        }

        return true;
    }

    public function handle(array &$data, string $method): void
    {
        $handler = $this->handler;
        $handler->setData($data);
        $handler->run($method);
    }

    public function getRepository(): BaseRepository
    {
        return $this->repository;
    }

    public function setRepository(BaseRepository $repository): self
    {
        $this->repository = $repository;
        return $this;
    }

    public function setValidator(BaseValidator $validator): self
    {
        $this->validator = $validator;
        return $this;
    }

    public function setHandler(BaseHandler $handler): self
    {
        $this->handler = $handler;
        return $this;
    }
}