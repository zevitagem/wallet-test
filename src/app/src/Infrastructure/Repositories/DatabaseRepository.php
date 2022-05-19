<?php

namespace App\Infrastructure\Repositories;

use App\Infrastructure\Repositories\AbstractCrudRepository;

//use app\models\resources\DatabaseResource;

class DatabaseRepository extends AbstractCrudRepository
{

//    public function __construct()
//    {
//        parent::setModel(new DatabaseResource());
//    }

    public function getTables()
    {
        $sql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES ORDER BY TABLE_NAME ASC";
        $res = $this->getConnectionDB()->query($sql);

        return $res->fetchAll(\PDO::FETCH_COLUMN);
    }

}
