<?php
include_once '../vendor/autoload.php';

use App\Infrastructure\Command\LineInputController;

$data  = readline("Enter your data: ");
$input = new LineInputController();
$input->handle($data);
?>

