<?php

namespace App\Infrastructure\Adapter;

use App\Infrastructure\Contracts\OutputAdapterInterface;

class StdoutOutputAdapter implements OutputAdapterInterface
{
    public function handle(array $data)
    {
        fwrite(STDOUT, json_encode($data)).'\n';
    }
}