<?php

namespace App\Infrastructure\Repositories\Database;

use App\Infrastructure\Libraries\Database\DatabaseManager;
use App\Infrastructure\Repositories\BaseRepository;
//use app\models\AbstractModel;

abstract class DatabaseRepository extends BaseRepository
{
    protected $model;

    public function getConnectionDB()
    {
        return DatabaseManager::getConnection(DatabaseManager::getType());
    }

//    public function setModel(AbstractModel $model)
//    {
//        $this->model = $model;
//    }
//
//    public function getModel()
//    {
//        return $this->model;
//    }
//
//    public function getClassModel()
//    {
//        return get_class($this->model);
//    }

    public function getTable()
    {
        return $this->model::getTable();
    }

    public function getPrimaryKey()
    {
        return $this->model::getPrimaryKey();
    }

    public function newException($exc)
    {
        throw $exc;
    }

}
