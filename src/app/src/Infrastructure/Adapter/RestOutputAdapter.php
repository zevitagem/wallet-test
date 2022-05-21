<?php

namespace App\Infrastructure\Adapter;

use App\Infrastructure\Contracts\OutputAdapterInterface;

class RestOutputAdapter implements OutputAdapterInterface
{
    public function header(int $httpCode)
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($httpCode);
    }

    public function handle(array $data)
    {
        $this->header(200);

        echo json_encode($data);
    }
}