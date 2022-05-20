<?php

namespace App\Application\Handlers;

class BaseHandler
{
    protected array $data;

    public function setData(array $data): self
    {
        $this->data =& $data;
        return $this;
    }

    public function run(string $method = ''): void
    {
        if (method_exists($this, $method)) {
            $this->{$method}();
        }
    }
}