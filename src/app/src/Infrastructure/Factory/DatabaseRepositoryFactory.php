<?php

namespace App\Infrastructure\Factory;

use App\Infrastructure\Contracts\Repositories\DatabaseLevelInterface;
use App\Infrastructure\Repositories\Database\MYSQL\MYSQLDatabaseRepository;

class DatabaseRepositoryFactory
{
    private static array $map = [
        'MYSQL' => MYSQLDatabaseRepository::class
    ];

    public static function newDatabase(string $type): ?DatabaseLevelInterface
    {
        if (isset(self::$map[$type])) {
            return new self::$map[$type]();
        }
    }
}