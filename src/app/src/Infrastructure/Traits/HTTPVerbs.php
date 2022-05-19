<?php

namespace App\Infrastructure\Traits;

use RuntimeException;

trait HTTPVerbs
{
    public function mustPost()
    {
        $verb = $_SERVER['REQUEST_METHOD'];
        if ($verb !== 'POST') {
            $this->throwCaseInvalidMethod($verb);
        }
    }

    public function mustGet()
    {
        $verb = $_SERVER['REQUEST_METHOD'];
        if ($verb !== 'GET') {
            $this->throwCaseInvalidMethod($verb);
        }
    }

    private function throwCaseInvalidMethod(string $verb)
    {
        throw new RuntimeException(
            sprintf('The requested method does not accept the submitted verb: "%s"',
                $verb)
        );
    }
}