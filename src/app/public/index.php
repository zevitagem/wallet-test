<?php
include_once '../vendor/autoload.php';

//error_reporting(E_ALL);
//ini_set('display_errors', true);

use App\Infrastructure\Libraries\Router;
use App\Infrastructure\Http\RestInputController;
use App\Infrastructure\Providers\BootstrapProvider;

BootstrapProvider::boot();

$controller = new RestInputController();
$router     = new Router($controller);

$controller->configure($router->extractUrl());
$controller->handle();
