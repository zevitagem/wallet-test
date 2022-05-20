<?php

namespace App\Infrastructure\Repositories\Database\MYSQL;

use App\Infrastructure\Repositories\Database\DatabaseRepository;

abstract class MYSQLDatabaseBaseRepository extends DatabaseRepository
{
    public function getDeletedAtColumn()
    {
        return $this->model::COLUMN_DELETED_AT;
    }

    protected function query($connection, string $sql)
    {
        try {
            return $connection->query($sql);
        } catch (\PDOException $exc) {
            $this->newException($exc);
        } catch (\Exception $exc) {
            $this->newException($exc);
        }
    }

    public function queryAll(string $query, array $params = [])
    {
        $sth = $this->getConnectionDB()->prepare($query);

        $this->execute($sth, $params);

        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    protected function execute($sth, array $params = [])
    {
        try {
            $exe = (!empty($params)) ? $sth->execute($params) : $sth->execute();
            if (!$exe) {
                throw new $exc();
                //dd($this->dbConnection->errorInfo());
            }

//            if (hasPrintDebug()) {
//                dd($sth->debugDumpParams());
//            }
        } catch (\PDOException $exc) {
            $this->newException($exc);
        } catch (\Exception $exc) {
            $this->newException($exc);
        }

        return true;
    }
}