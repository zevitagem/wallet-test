<?php

namespace App\Application\Handlers;

use App\Application\Handlers\BaseHandler;

class TransactionHandler extends BaseHandler
{
    public function store()
    {
        $data = & $this->data;

        $intKeys = ['amount', 'origin', 'destination'];
        foreach ($intKeys as $key) {
            if (isset($data[$key])) {
                $data[$key] = intval($data[$key]);
            }
        }
    }
}