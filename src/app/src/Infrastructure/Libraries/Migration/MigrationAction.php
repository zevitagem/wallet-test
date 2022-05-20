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
}