<?php

namespace App\Infrastructure\Adapter;

use App\Infrastructure\Contracts\OutputAdapterInterface;

class RestOutputAdapter implements OutputAdapterInterface
{
    public function handle(array $data)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }
}