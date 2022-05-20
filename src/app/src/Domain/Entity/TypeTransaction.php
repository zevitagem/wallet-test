<?php
declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Contracts\EntityInterface;
use App\Application\Traits\OperationAttributes;

class Operation implements EntityInterface
{
    use OperationAttributes;

    public function __construct(
        string $operation, float $unitCost, int $quantity
    )
    {
        $this->operation = $operation;
        $this->unitCost = $unitCost;
        $this->quantity = $quantity;
    }

    public static function fromArray(array $array): Operation
    {
        return new self(
            $array['operation'],
            $array['unit-cost'],
            $array['quantity']
        );
    }
}