<?php

namespace App\Infrastructure\Libraries\Migration;

use App\Infrastructure\Libraries\Migration\MigrationManager;
use App\Infrastructure\Contracts\MigrationActionInterface;

abstract class MigrationAction implements MigrationActionInterface
{
    public function __construct(private MigrationManager $manager)
    {

    }

    public function getManager(): MigrationManager
    {
        return $this->manager;
    }

    protected function out(string $value): void
    {
        if ($this->getManager()->isValidConfig('print_output')) {
            echo $value.PHP_EOL;
        }
    }
}