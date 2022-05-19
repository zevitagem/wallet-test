<?php
include_once '../vendor/autoload.php';

use App\Infrastructure\Libraries\Router;
use App\Infrastructure\Http\RestInputController;
use App\Infrastructure\Providers\BootstrapProvider;

BootstrapProvider::boot();

$router = new Router(new RestInputController());
$router->handle();
