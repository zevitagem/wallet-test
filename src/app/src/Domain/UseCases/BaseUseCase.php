<?php

namespace App\Domain\UseCases;

use App\Infrastructure\Traits\AvailabilityWithDependencie;
use RuntimeException;
use App\Domain\UseCases\UseCaseResponse;
use App\Domain\Contracts\UseCaseInterface;

abstract class BaseUseCase implements UseCaseInterface
{
    use AvailabilityWithDependencie;

    protected function checkDependencies()
    {
        foreach ($this->getDependencieKeysRequired() as $key) {
            $dependencie = $this->getDependencie($key);

            if (empty($dependencie)) {
                throw new RuntimeException(
                        sprintf('Required dependency not found : "%s"', $key));
            }
        }
    }

    protected function end(bool $status, mixed $content): UseCaseResponse
    {
        return new UseCaseResponse($status, $content);
    }
}