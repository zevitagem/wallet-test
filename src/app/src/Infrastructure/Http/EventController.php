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
        $this->mustPost();

        $this->getService()->save($_POST);
    }
}