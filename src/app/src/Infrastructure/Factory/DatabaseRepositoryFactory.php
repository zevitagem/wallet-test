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
        return (isset(self::$map[$type])) ? new self::$map[$type]() : null;
    }
}