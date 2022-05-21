<?php

namespace App\Infrastructure\Adapter;

use App\Infrastructure\Contracts\OutputAdapterInterface;

class RestOutputAdapter implements OutputAdapterInterface
{
    public function header()
    {
        header('Content-Type: application/json; charset=utf-8');
    }

    public function httpCode(int $cod)
    {
        http_response_code($cod);
    }

    public function handle(array $data, int $httpCode = 200)
    {
        $this->httpCode($httpCode);
        $this->header();

        echo json_encode($data);
    }
}