<?php

namespace App\Infrastructure\Contracts\Adapter\Database;

interface SimpleDatabaseInterface
{
    public function getConfig(): array;

    public function connect(array $config);

    public function getAttributes();

    public function getConnection();
}