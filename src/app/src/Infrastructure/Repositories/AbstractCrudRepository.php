<?php

namespace App\Infrastructure\Repositories;

use App\Infrastructure\Repositories\AbstractRepository;

abstract class AbstractCrudRepository extends AbstractRepository
{
    public function getDeletedAtColumn()
    {
        return $this->model::COLUMN_DELETED_AT;
    }

    public function getValidObjects()
    {
        $sql = "SELECT * FROM ".$this->getTable()." WHERE ".$this->getDeletedAtColumn()." IS NULL";
        $res = $this->getConnectionDB()->query($sql);

        return $res->fetchAll(\PDO::FETCH_CLASS, $this->getClassModel());
    }

    public function getById(int $id)
    {
        $sql = "SELECT * FROM ".$this->getTable()." "
            ."  WHERE ".$this->getPrimaryKey()." = $id "
            ."  LIMIT 1";

        $res = $this->_query($this->getConnectionDB(), $sql);

        return $res->fetchObject($this->getClassModel());
    }

    public function deleteById(int $id)
    {
        $sql = "DELETE FROM ".$this->getTable()." "
            ."  WHERE ".$this->getPrimaryKey()." = $id "
            ."  LIMIT 1";

        $sth = $this->getConnectionDB()->prepare($sql);

        $this->_execute($sth);

        return ($sth->rowCount() > 0);
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

        $sql = "INSERT INTO ".$this->getTable()." (".implode(',', $keys).") VALUES (".implode(',', $columnsBinds).")";

        $sth = $this->getConnectionDB()->prepare($sql);
        foreach ($keys as $key) {
            $sth->bindParam($columnsBinds[$key], $data[$key]);
        }

        $this->_execute($sth);

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

        return $this->_execute($sth);
    }
}