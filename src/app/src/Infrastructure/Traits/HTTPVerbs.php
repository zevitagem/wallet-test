<?php

namespace App\Infrastructure\Traits;

use InvalidArgumentException;

trait HTTPVerbs
{
    public function mustPost(): void
    {
        $verb = $_SERVER['REQUEST_METHOD'];
        if ($verb !== 'POST') {
            $this->throwCaseInvalidMethod($verb);
        }
    }

    public function mustGet(): void
    {
        $verb = $_SERVER['REQUEST_METHOD'];
        if ($verb !== 'GET') {
            $this->throwCaseInvalidMethod($verb);
        }
    }

    private function throwCaseInvalidMethod(string $verb)
    {
        throw new InvalidArgumentException(
            sprintf('The requested method does not accept the submitted verb: "%s"',
                $verb)
        );
    }
}