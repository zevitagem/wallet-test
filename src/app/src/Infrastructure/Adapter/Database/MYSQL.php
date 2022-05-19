<?php

namespace App\Infrastructure\Adapter\Database;

use App\Infrastructure\Contracts\Adapter\Database\SimpleDatabaseInterface;
use PDO;

class MYSQL implements SimpleDatabaseInterface
{
    public function getConfig(): array
    {
        return [
            'HOST' => 'wallet-mysql',
            'DATABASE' => 'wallet',
            'USER' => 'root',
            'PASSWORD' => '',
            'PORT' => 3306
        ];
    }

    public function connect(array $config)
    {
        return new PDO('mysql:host='.$config['HOST'].';'
            . '               port='.$config['PORT'].';'
            . '               dbname='.$config['DATABASE'],
            $config['USER'],
            $config['PASSWORD'],
            array(
            //PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ));
    }
}