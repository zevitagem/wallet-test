<?php

namespace App\Infrastructure\Http;

use App\Infrastructure\Http\BaseController;
use App\Application\Services\TransactionService;

class EventController extends BaseController
{
    public function __construct()
    {
        $this->setService(new TransactionService());
    }

    public function index()
    {
        echo 'index transaction';
    }

    public function handleGet()
    {
        $this->mustGet();

        echo 'get transaction';
    }

    public function handlePost()
    {
        $this->mustPost();

        $this->getService()->store($_POST);
    }
}