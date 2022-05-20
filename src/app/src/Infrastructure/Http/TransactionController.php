<?php

namespace App\Infrastructure\Http;

use App\Infrastructure\Http\BaseController;
use App\Application\Services\TransactionService;

class TransactionController extends BaseController
{
    public function __construct()
    {
        $this->setService(new TransactionService());
    }

    public function handleGet()
    {
        $this->mustGet();

        echo 'get';
    }

    public function handlePost()
    {
        $this->mustPost();

        $this->getService()->store($_POST);
    }
}