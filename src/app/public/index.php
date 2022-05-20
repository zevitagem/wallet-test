<?php
include_once '../vendor/autoload.php';

use App\Infrastructure\Libraries\Router;
use App\Infrastructure\Http\RestInputController;
use App\Infrastructure\Providers\BootstrapProvider;

BootstrapProvider::boot();

$controller = new RestInputController();
$router     = new Router($controller);

$controller->configure($router->extract());
$controller->handle();
