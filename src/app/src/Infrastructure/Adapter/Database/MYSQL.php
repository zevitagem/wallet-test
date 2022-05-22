<?php

namespace App\Infrastructure\Adapter\Database;

use App\Infrastructure\Contracts\Adapter\Database\SimpleDatabaseInterface;
use PDO;

final class MYSQL implements SimpleDatabaseInterface
{
    private $instance;

    public function getConfig(): array
    {
        return [
            'HOST' => env('HOST'),
            'DATABASE' => env('DATABASE'),
            'USER' => env('USER'),
            'PASSWORD' => env('PASSWORD'),
            'PORT' => env('PORT')
        ];
    }

    public function getConnection()
    {
        return $this->instance;
    }

    public function connect(array $config)
    {
        $this->instance = new PDO('mysql:host='.$config['HOST'].';'
            .'               port='.$config['PORT'].';'
            .'               dbname='.$config['DATABASE'], $config['USER'],
            $config['PASSWORD'],
            array(
            //PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ));
    }

    public function getAttributes()
    {
        $attributes = array(
            "AUTOCOMMIT", "ERRMODE", "CASE", "CLIENT_VERSION", "CONNECTION_STATUS",
            "ORACLE_NULLS", "PERSISTENT", "SERVER_INFO", "SERVER_VERSION",
        );

        $data = [];
        foreach ($attributes as $val) {
            $data[$val] = $this->instance->getAttribute(constant("PDO::ATTR_$val"));
        }

        return $data;
    }
}