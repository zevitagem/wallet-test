<?php
include_once '../vendor/autoload.php';

define('TYPE_DB', 'MYSQL');

use App\Infrastructure\Libraries\Database\DatabaseManager;

echo 'Hello World!';

DatabaseManager::connect(TYPE_DB);
var_dump(DatabaseManager::getCon(TYPE_DB));

//use App\Infrastructure\Http\RestInputController;
//
//$input = new RestInputController();
//$input->handle($_POST);