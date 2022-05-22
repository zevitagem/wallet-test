<?php

namespace App\Infrastructure\Repositories\Database\MYSQL;

use App\Infrastructure\Repositories\Database\DatabaseRepository;
use App\Infrastructure\Contracts\Repositories\ReversibleInterface;
use Throwable;

abstract class MYSQLDatabaseBaseRepository extends DatabaseRepository implements ReversibleInterface
{
    public function beginTransaction(): void
    {
        $this->getConnectionDB()->beginTransaction();
    }

    public function commit(): void
    {
        $this->getConnectionDB()->commit();
    }

    public function rollBack(): void
    {
        $this->getConnectionDB()->rollBack();
    }

    protected function query($connection, string $sql)
    {
        try {
            return $connection->query($sql);
        } catch (\Throwable $exc) {
            $this->throwException($exc);
        }
    }

    protected function queryAll(string $query, array $params = [])
    {
        $sth = $this->getConnectionDB()->prepare($query);

        $this->execute($sth, $params);

        return $sth->fetchAll(\PDO::FETCH_CLASS, $this->getEntityClass());
    }

    protected function execute($sth, array $params = [])
    {
        try {
            $exe = (!empty($params)) ? $sth->execute($params) : $sth->execute();
            if (!$exe) {
                //dd($this->dbConnection->errorInfo());
            }

            if (hasPrintDebug()) {
                dd($sth->debugDumpParams());
            }
        } catch (Throwable $exc) {
            $this->throwException($exc);
        }

        return true;
    }
}