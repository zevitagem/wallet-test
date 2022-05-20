<?php

namespace App\Infrastructure\Repositories\Database;

use App\Infrastructure\Libraries\Database\DatabaseManager;
use App\Infrastructure\Repositories\BaseRepository;
use App\Infrastructure\Exceptions\InfraException;
use App\Infrastructure\Resources\DatabaseResource;
use Throwable;

abstract class DatabaseRepository extends BaseRepository
{
    abstract public function getEntityClass(): string;

    public function getResourceClass(): string
    {
        return DatabaseResource::class;
    }

    public function getConnectionDB()
    {
        return DatabaseManager::getConnection(DatabaseManager::getType());
    }

    protected function getTable(): string
    {
        $list = explode('\\', $this->getEntityClass());
        end($list);

        return strtolower(current($list));
    }

    protected function getPrimaryKey(): string
    {
        $const = $this->getEntityClass().'::PRIMARY_KEY';
        if (defined($const)) {
            return constant($const);
        }

        return 'id';
    }

    protected function getDeletedAtColumn(): string
    {
        $const = $this->getEntityClass().'::DELETED_AT';
        if (defined($const)) {
            return constant($const);
        }

        return 'deleted_at';
    }

    protected function throwException(Throwable $exc)
    {
        throw new InfraException($exc->getMessage());
    }
}