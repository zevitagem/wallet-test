<?php

namespace App\Application\Factory;

use App\Infrastructure\Resources\DatabaseResource;
use App\Domain\Entity\Account;

class EntityFromResourceFactory
{
    public static function account(DatabaseResource $resource): Account
    {
        //get_object_vars
        return Account::fromArray([
            'id' => $resource->getId(),
            'name' => $resource->name,
            'balance' => (int) $resource->balance,
            'created_at' => $resource->created_at,
            'deleted_at' => $resource->deleted_at,
        ]);
    }
}