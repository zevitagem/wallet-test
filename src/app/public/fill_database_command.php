<?php
include_once '../vendor/autoload.php';

use App\Infrastructure\Providers\BootstrapProvider;
use App\Infrastructure\Command\FillDatabaseController;

BootstrapProvider::boot();

$input = new FillDatabaseController();
$input->handle();

