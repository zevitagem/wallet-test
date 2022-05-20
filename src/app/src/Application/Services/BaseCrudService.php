<?php

namespace App\Application\Services;

use App\Application\Services\BaseService;

abstract class BaseCrudService extends BaseService
{
    public function getById(int $id)
    {
        return $this->repository->getById($id);
    }

    public function updateById(int $id, array $data)
    {
        unset($data['id']);

        return $this->repository->updateById($id, $data);
    }

    public function store(array $data)
    {
        unset($data['id']);

        return $this->repository->store($data);
    }

    public function getValidObjects()
    {
        return $this->repository->getValidObjects();
    }

    public function deleteById(int $id)
    {
        return $this->repository->deleteById($id);
    }

    public function destroyAt(int $id)
    {
        return $this->updateById($id,
            [
                $this->getRepository()->getDeletedAtColumn() => date('Y-m-d H:i:s')
            ]
        );
    }
}