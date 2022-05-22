<?php

namespace App\Application\DTO;

use App\Application\Contracts\DTOInterface;

abstract class DTO implements DTOInterface
{
    abstract public function __construct();

    public static function fromArray(array $parameters): self
    {
        $dto = new static;

        foreach ($parameters as $key => $value) {

            if (property_exists($dto, $key)) {
                $dto->{$key} = $value;
                continue;
            }

            if ($dto->trySetPropertyCaseNotExists($key, $value)) {
                continue;
            }

            throw new \InvalidArgumentException(
                sprintf('Attribute %s does not exist in %s', $key,
                    get_class($dto)
                )
            );
        }

        return $dto;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }

    protected function trySetPropertyCaseNotExists(string $key, $value): bool
    {
        return false;
    }
}