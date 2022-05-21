<?php

namespace App\Infrastructure\Repositories\Database\MYSQL;

use App\Infrastructure\Repositories\Database\MYSQL\MYSQLDatabaseBaseRepository;

abstract class MYSQLCRUDRepository extends MYSQLDatabaseBaseRepository
{
    public function getValidObjects()
    {
        $sql = "SELECT * FROM ".$this->getTable()." WHERE ".$this->getDeletedAtColumn()." IS NULL";
        $res = $this->getConnectionDB()->query($sql);

        return $res->fetchAll(\PDO::FETCH_CLASS, $this->getResourceClass());
    }

    public function getById(int $id)
    {
        $primaryKey = $this->getPrimaryKey();

        $sql = "SELECT * FROM ".$this->getTable()." "
            ."  WHERE $primaryKey = ? "
            ."  LIMIT 1";

        $sth = $this->getConnectionDB()->prepare($sql);

        $sth->bindParam(1, $id, \PDO::PARAM_INT);

        $this->execute($sth);

        return $sth->fetchObject($this->getResourceClass());
    }

    public function deleteById(int $id)
    {
        $sql = "DELETE FROM ".$this->getTable()." "
            ."  WHERE ".$this->getPrimaryKey()." = $id "
            ."  LIMIT 1";

        $sth = $this->getConnectionDB()->prepare($sql);

        $this->execute($sth);

        return ($sth->rowCount() > 0);
    }

    public function getAll()
    {
        $sql = "SELECT * FROM ".$this->getTable();

        return $this->queryAll($sql);
    }

    public function countRows()
    {
        $sql = "SELECT COUNT(*) FROM ".$this->getTable();
        $res = $this->getConnectionDB()->query($sql);

        return $res->fetchColumn();
    }

    public function save(array $data)
    {
        $primary  = $this->model->getPrimaryKey();
        $isUpdate = false;

        if (!empty($data[$primary])) {
            $id       = $data[$primary];
            $isUpdate = true;
        }

        if (array_key_exists($primary, $data)) {
            unset($data[$primary]);
        }

        return ($isUpdate) ? $this->updateById($id, $data) : $this->store($data);
    }

    public function store(array $data)
    {
        $keys = array_keys($data);

        $columnsBinds = [];
        foreach ($keys as $key) {
            $columnsBinds[$key] = ":$key";
        }

        $sql = "INSERT INTO ".$this->getTable()." (".implode(',', $keys).") VALUES (".implode(',',
                $columnsBinds).")";

        $sth = $this->getConnectionDB()->prepare($sql);
        foreach ($keys as $key) {
            $sth->bindParam($columnsBinds[$key], $data[$key]);
        }

        $this->execute($sth);

        return ($sth->rowCount() > 0)
            ? $this->getConnectionDB()->lastInsertId()
            : false;
    }

    public function updateById(int $id, array $data)
    {
        $keys = array_keys($data);

        $columnsBinds = [];
        foreach ($keys as $key) {
            $columnsBinds[] = "$key = :$key";
        }

        $sql = "UPDATE ".$this->getTable()." SET ".implode(',', $columnsBinds)." WHERE ".$this->getPrimaryKey()." = $id";

        $sth = $this->getConnectionDB()->prepare($sql);
        foreach ($keys as $key) {
            $sth->bindParam(":$key", $data[$key]);
        }

        return $this->execute($sth);
    }
}