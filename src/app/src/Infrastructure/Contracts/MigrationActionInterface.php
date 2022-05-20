<?php

namespace App\Infrastructure\Contracts;

use App\Infrastructure\Libraries\Migration\MigrationManager;

interface MigrationActionInterface
{
    public function getManager(): MigrationManager;

    public function handle(): mixed;

    public function can(): bool;
}