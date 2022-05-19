<?php

namespace App\Infrastructure\Providers;

use App\Infrastructure\Libraries\Database\DatabaseManager;
use App\Infrastructure\Contracts\ProviderInterface;

class DatabaseProvider implements ProviderInterface
{
    public static function boot(): void
    {
        define('TYPE_DB', 'MYSQL');
        DatabaseManager::connect(TYPE_DB);
    }
}