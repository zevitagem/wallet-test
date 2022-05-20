<?php

namespace App\Infrastructure\Repositories\Database\MYSQL;

use App\Infrastructure\Repositories\Database\MYSQL\MYSQLDatabaseBaseRepository;
use App\Infrastructure\Contracts\Repositories\DatabaseLevelInterface;

class MYSQLDatabaseRepository extends MYSQLDatabaseBaseRepository implements DatabaseLevelInterface
{
    public function getTables()
    {
        $sql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES ORDER BY TABLE_NAME ASC";
        $res = $this->getConnectionDB()->query($sql);

        return $res->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function getTotalTables(string $database): int
    {
        $sql = "SELECT count(*) AS total_number_tables FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ?";

        $sth = $this->getConnectionDB()->prepare($sql);

        $sth->bindParam(1, $database, \PDO::PARAM_STR);

        $this->execute($sth);

        return (int) $sth->fetchObject()->total_number_tables;
    }

    public function dropDatabase(string $database): bool
    {
        $sql = "DROP DATABASE IF EXISTS {$database}";

        $sth = $this->getConnectionDB()->prepare($sql);

        $this->execute($sth);

        return true;
    }

    public function createDatabase(string $database): bool
    {
        $sql = "CREATE DATABASE IF NOT EXISTS {$database}";

        $sth = $this->getConnectionDB()->prepare($sql);

        $this->execute($sth);

        return true;
    }
}