<?php

namespace App\Infrastructure\Contracts;

use App\Infrastructure\Contracts\OutputAdapterInterface;

interface InputAdapterInterface
{
    public function handle();

    public function setOutputAdapter(OutputAdapterInterface $output);
}