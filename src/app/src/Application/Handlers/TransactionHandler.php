<?php

namespace App\Application\Handlers;

use App\Application\Handlers\BaseHandler;
use App\Domain\Enum\TransactionTypeEnum;

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

        if (isset($data['type'])) {
            switch ($data['type']) {
                case TransactionTypeEnum::DEPOSIT:
                    unset($data['origin']);
                    break;
                case TransactionTypeEnum::WITHDRAW:
                    unset($data['destination']);
                    break;
            }
        }
    }
}