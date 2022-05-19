<?php
declare(strict_types=1);

namespace Test;

use PHPUnit\Framework\TestCase;

final class ExampleTest extends TestCase
{
    public function testFalse()
    {
        $this->assertFalse(false);
    }
}