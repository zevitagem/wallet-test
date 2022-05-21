<?php

namespace App\Infrastructure\Providers;

use App\Infrastructure\Libraries\Database\DatabaseManager;
use App\Infrastructure\Contracts\ProviderInterface;

class DatabaseProvider implements ProviderInterface
{
    public static function boot()
    {
        if (!defined('TYPE_DB')) {
            define('TYPE_DB', 'MYSQL');
        }

        DatabaseManager::reset(TYPE_DB);
        DatabaseManager::connect(TYPE_DB);

        return DatabaseManager::getByType(TYPE_DB);
    }
}