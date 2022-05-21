<?php

namespace App\Infrastructure\Http;

use App\Infrastructure\Http\BaseController;
use App\Application\Services\TransactionService;

class EventController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->setService(new TransactionService());
    }

    public function index()
    {
        $this->mustPost();

        $response = $this->getService()->store($_POST);
        $content = $response->getContent();

        if ($response->isSuccessfully()) {
            return $this->output->handle((array) $content, 201);
        }

        $this->output->handle(
            ['status' => false, 'message' => $content], 500
        );
    }
}