<?php
include_once '../vendor/autoload.php';

use App\Infrastructure\Providers\BootstrapProvider;
use App\Infrastructure\Command\DatabaseFillCommand;

BootstrapProvider::boot();

$input = new DatabaseFillCommand();
$input->handle();
