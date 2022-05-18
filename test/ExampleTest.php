<?php
declare(strict_types=1);

namespace Test;

use PHPUnit\Framework\TestCase;
use App\Domain\Package\CapitalGains\Sale\ProfitCalculator;
use App\Domain\Entity\Operation;

final class DomainTest extends TestCase
{
    public function testHasToPayTax()
    {
        $service = new ProfitCalculator();
        $operation = $this->createStub(Operation::class);

        $operation->expects($this->once())
            ->method('getQuantity')
            ->with()
            ->willReturn(10);

         $operation->expects($this->once())
            ->method('getUnitCost')
            ->with()
            ->willReturn(1.00);

        $result = $service->hasToPayTax($operation);

        $this->assertFalse($result);
    }
}