<?php

namespace App\Infrastructure\Http;

use App\Infrastructure\Contracts\InputAdapterInterface;
use App\Infrastructure\Traits\HTTPVerbs;

class RestInputController implements InputAdapterInterface
{
    use HTTPVerbs;

    public function handle()
    {

    }

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