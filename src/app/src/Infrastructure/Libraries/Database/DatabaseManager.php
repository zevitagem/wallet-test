<?php

namespace App\Infrastructure\Libraries\Database;

use InvalidArgumentException;
use Throwable;

class DatabaseManager
{
    private static array $connection;
    private static string $lastType = '';

    public static function connect(string $type = '')
    {
        self::setType($type);

        if (!empty(self::$connection[$type])) {
            return self::getConnection($type);
        }

        try {
            $path  = self::getPath($type);
            $class = new $path();

            $class->connect($class->getConfig());
            $connection = $class->getConnection();
        } catch (Throwable $e) {
            $connection     = null;
            $class          = null;

            throw $e;
        } finally {
            self::$connection[$type] = compact('connection', 'class');
        }
    }

    private static function getPath(string $type): string
    {
        $path = 'App\\Infrastructure\\Adapter\\Database\\'.$type;

        if (!class_exists($path)) {
            throw new InvalidArgumentException(
                    sprintf('The selected type "%s" for connect in database not found',
                        $type)
            );
        }

        return $path;
    }

    public static function setType(string $type = 'mysql'): void
    {
        self::$lastType = $type;
    }

    public static function getType(): string
    {
        return self::$lastType;
    }

    public static function getConnection(string $type): mixed
    {
        return self::$connection[$type]['connection'] ?? null;
    }

    public static function getByType(string $type): mixed
    {
        return self::$connection[$type] ?? null;
    }
}