<?php

namespace App\Infrastructure\Repositories;

use App\Infrastructure\Libraries\Database\DatabaseManager;
use app\models\AbstractModel;

abstract class AbstractRepository
{
    protected $model;

    public function getConnectionDB()
    {
        return DatabaseManager::getCon(DatabaseManager::getType());
    }

    public function setModel(AbstractModel $model)
    {
        $this->model = $model;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getClassModel()
    {
        return get_class($this->model);
    }

    public function getTable()
    {
        return $this->model::getTable();
    }

    public function getPrimaryKey()
    {
        return $this->model::getPrimaryKey();
    }

    public function queryAll(string $query, array $params = [])
    {
        $sth = $this->getConnectionDB()->prepare($query);

        $this->_execute($sth, $params);

        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $sql = "SELECT * FROM " . $this->getTable();

        return $this->queryAll($sql);
    }

    public function countRows()
    {
        $sql = "SELECT COUNT(*) FROM " . $this->getTable();
        $res = $this->getConnectionDB()->query($sql);

        return $res->fetchColumn();
    }

    public function getColumnsObject()
    {
        $sth = $this->getConnectionDB()->prepare("DESCRIBE " . $this->getTable());

        $this->_execute($sth);

        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function showColumns(string $table = '')
    {
        if (empty($table)) {
            $table = $this->getTable();
        }

        switch (PDOConnection::getType()) {
            case OPTION_TYPE_DB_MYSQL:
                $sql = "SHOW COLUMNS FROM " . $table;
                break;
            case OPTION_TYPE_DB_SQLSERVER:
                $sql = "SELECT COLUMN_NAME AS 'Field' FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME= '" . $table . "'";
                break;
        }

        $sth = $this->getConnectionDB()->prepare($sql);

        $this->_execute($sth);

        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    protected function _query($connection, string $sql)
    {
        try {
            return $connection->query($sql);
        } catch (\PDOException $exc) {
            $this->newException($exc);
        } catch (\Exception $exc) {
            $this->newException($exc);
        }
    }

    protected function _execute($sth, array $params = [])
    {
        try {
            $exe = (!empty($params)) ? $sth->execute($params) : $sth->execute();
            if (!$exe) {
                $this->newException($exc);
                //dd($this->dbConnection->errorInfo());
            }

            if (hasPrintDebug()) {
                dd($sth->debugDumpParams());
            }
        } catch (\PDOException $exc) {
            $this->newException($exc);
        } catch (\Exception $exc) {
            $this->newException($exc);
        }

        return true;
    }

    public function newException($exc)
    {
        throw $exc;
    }

}
