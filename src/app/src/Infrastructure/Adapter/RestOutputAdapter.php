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

    public function handle(array $data, int $httpCode = 200)
    {
        $this->header($httpCode);

        echo json_encode($data);
    }
}