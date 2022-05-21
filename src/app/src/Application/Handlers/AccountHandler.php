<?php

namespace App\Application\Handlers;

use App\Application\Handlers\BaseHandler;

class AccountHandler extends BaseHandler
{
    public function store()
    {
        $data = & $this->data;

        $intKeys = ['id'];
        foreach ($intKeys as $key) {
            if (isset($data[$key])) {
                $data[$key] = intval($data[$key]);
            }
        }

        if (!isset($data['name'])) {
            $data['name'] = 'test';
        }
    }
}