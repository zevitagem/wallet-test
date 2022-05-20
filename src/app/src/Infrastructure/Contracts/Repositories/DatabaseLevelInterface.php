<?php

namespace App\Infrastructure\Contracts\Repositories;

interface DatabaseLevelInterface
{
    public function getTables();

    public function getTotalTables(string $database): int;

    public function dropDatabase(string $database): bool;

    public function createDatabase(string $database): bool;
}