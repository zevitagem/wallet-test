<?php

namespace App\Infrastructure\Http;

use App\Infrastructure\Http\BaseController;

class TransactionController extends BaseController
{
    public function handleGet()
    {
        $this->mustGet();

        echo 'vou fazer';
    }

    public function handlePost()
    {
        $this->mustPost();

        echo 'vou fazer';
    }
}